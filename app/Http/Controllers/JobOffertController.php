<?php

namespace App\Http\Controllers;

use App\Models\JobOffert;
use App\Models\Skill;
use App\Models\Benefit;
use App\Models\User;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Notifications\JobMatchedNotification;

class JobOffertController extends Controller
{
    public function index()
    {
        $jobs = JobOffert::whereHas('company', function ($query) {
            $query->where('user_id', auth()->id());
        })->paginate(5);

        return view('jobs.index', compact('jobs'));
    }

    public function create()
    {
        $company = auth()->user()->company;

        if (! $company) {
            return redirect()->route('companies.create')->with('error', 'Devi prima creare un’azienda per poter pubblicare un’offerta.');
        }

        return view('jobs.create', compact('company'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'job_type' => 'required|string',
            'contract_type' => 'required|string',
            'experience_level' => 'required|string',
            'ral' => 'nullable|numeric',
            'skills_required' => 'nullable|array',
            'skills_required.*' => 'string|max:255',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string|max:255',
        ]);

        $company = auth()->user()->company;

        if (!$company) {
            abort(403, 'Nessuna azienda associata a questo recruiter.');
        }

        $validated['company_id'] = $company->id;

        $skillsLowercase = array_map('strtolower', $validated['skills_required'] ?? []);
        $benefitsLowercase = array_map('strtolower', $validated['benefits'] ?? []);

        $benefitIds = $this->findOrCreateBenefits($benefitsLowercase);
        $skillIds = $this->findOrCreateSkills($skillsLowercase);

        unset($validated['skills_required'], $validated['benefits']);

        $job = $company->jobs()->create($validated);

        $job->benefits()->sync($benefitIds);
        $job->skills()->sync($skillIds);

        // Notifical'offerta di lavoro agli utenti candidati che hanno le competenze teconche richieste
        $skillNames = Skill::whereIn('id', $skillIds)->pluck('name')->map(fn($name) => strtolower($name))->toArray();

        User::whereHas('skills', function ($query) use ($skillNames) {
            $query->whereIn(\DB::raw('LOWER(name)'), $skillNames);
        })->chunk(100, function ($users) use ($job) {
            foreach ($users as $user) {
                $user->notify(new JobMatchedNotification($job));
            }
        });

        return redirect()->route('jobs.show', $job)->with('success', 'Offerta pubblicata con successo.');
    }

    public function show(Request $request, $id)
    {
        $job = JobOffert::whereHas('company', function ($query) {
            $query->where('user_id', auth()->id());
        })->findOrFail($id);

        // Carica tutti i filtri disponibili
        $allLanguages = Language::all();
        $allSkills = Skill::all();

        // Applica filtri alle candidature
        $applications = $job->applications()
            ->with('user.languages', 'user.skills', 'user.experiences', 'user.profiles')
            ->when($request->name, fn($q) => $q->whereHas('user', fn($q2) =>
                $q2->where('name', 'like', "%{$request->name}%")))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->language, fn($q) => $q->whereHas('user.languages', fn($q2) =>
                $q2->where('languages.id', $request->language)))
            ->when($request->skill, fn($q) => $q->whereHas('user.skills', fn($q2) =>
                $q2->where('skills.id', $request->skill)))
            ->when($request->has_experience !== null, function ($q) use ($request) {
                if ($request->has_experience == '1') {
                    return $q->whereHas('user.experiences');
                } elseif ($request->has_experience == '0') {
                    return $q->whereDoesntHave('user.experiences');
                }
            })
            ->paginate(5)
            ->appends($request->all());

        return view('jobs.show', compact('job', 'applications', 'allLanguages', 'allSkills'));
    }



    public function edit($id)
    {
        $job = JobOffert::whereHas('company', function ($query) {
            $query->where('user_id', auth()->id());
        })->findOrFail($id);

        $company = auth()->user()->company;

        // Estrarre array di stringhe con i nomi
        $skills = $job->skills()->pluck('name')->toArray();
        $benefits = $job->benefits()->pluck('name')->toArray();

        return view('jobs.edit', compact('job', 'company', 'skills', 'benefits'));
    }


    public function update(Request $request, $id)
    {
        $job = JobOffert::whereHas('company', function ($query) {
            $query->where('user_id', auth()->id());
        })->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'job_type' => 'required|string',
            'contract_type' => 'required|string',
            'experience_level' => 'required|string',
            'ral' => 'nullable|numeric',
            'skills_required' => 'nullable|array',
            'skills_required.*' => 'string|max:255',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string|max:255',
        ]);
        
        $skillsLowercase = array_map('strtolower', $validated['skills_required'] ?? []);
        $benefitsLowercase = array_map('strtolower', $validated['benefits'] ?? []);

        $benefitIds = $this->findOrCreateBenefits($benefitsLowercase);
        $skillIds = $this->findOrCreateSkills($skillsLowercase);

        unset($validated['skills_required'], $validated['benefits']);

        $job->update($validated);

        $job->benefits()->sync($benefitIds);
        $job->skills()->sync($skillIds);

        return redirect()->route('jobs.show', $job)->with('success', 'Offerta aggiornata con successo.');
    }

    public function destroy($id)
    {
        $job = JobOffert::whereHas('company', function ($query) {
            $query->where('user_id', auth()->id());
        })->findOrFail($id);

        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'Offerta eliminata con successo.');
    }

    public function publicIndex(Request $request)
    {
        $query = JobOffert::where('is_active', true);
        
        // Filtri applicati
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }
        
        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }
        
        if ($request->filled('experience_level')) {
            $query->where('experience_level', $request->experience_level);
        }
        
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('skill')) {
            $skill = $request->skill;
            $query->whereHas('skills', function ($q) use ($skill) {
                $q->where('name', $skill);
            });
        }
        
        if ($request->filled('benefit')) {
            $benefit = $request->benefit;
            $query->whereHas('benefits', function ($q) use ($benefit) {
                $q->where('name', $benefit);
            });
        }

        $allSkills = Skill::whereHas('jobOfferts', function ($query) {
            $query->where('is_active', true);
        })->orderBy('name')->get()->pluck('name');

        $allBenefits = Benefit::whereHas('jobOfferts', function ($query) {
            $query->where('is_active', true);
        })->orderBy('name')->get()->pluck('name');

        $savedJobs = auth()->user()->savedJobs()->with('company')->paginate(5);

        $jobs = $query->with(['company', 'skills', 'benefits'])->latest()->paginate(5)->withQueryString();

        // Passa tutto alla view
        return view('jobs.publicIndex', compact('jobs', 'allSkills', 'allBenefits', 'savedJobs'));
    }

    public function publicShow($id)
    {
        $job = JobOffert::where('is_active', true)->findOrFail($id);
        return view('jobs.publicShow', compact('job'));
    }

    private function findOrCreateBenefits(array $names): array
    {
        $ids = [];

        foreach ($names as $name) {
            $benefit = Benefit::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();

            if (!$benefit) {
                $benefit = Benefit::create(['name' => $name]);
            }

            $ids[] = $benefit->id;
        }

        return $ids;
    }

    private function findOrCreateSkills(array $names): array
    {
        $ids = [];

        foreach ($names as $name) {
            $skill = Skill::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();

            if (!$skill) {
                $skill = Skill::create(['name' => $name]);
            }

            $ids[] = $skill->id;
        }

        return $ids;
    }

}
