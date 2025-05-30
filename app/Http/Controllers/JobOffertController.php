<?php

namespace App\Http\Controllers;

use App\Models\JobOffert;
use Illuminate\Http\Request;

class JobOffertController extends Controller
{
    public function index()
    {
        $jobs = JobOffert::whereHas('company', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();

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
            'benefits' => 'nullable|array',
        ]);

        $validated['skills_required'] = array_map('strtolower', $validated['skills_required'] ?? []);
        $validated['benefits'] = array_map('strtolower', $validated['benefits'] ?? []);

        $company = auth()->user()->company;

        if (!$company) {
            abort(403, 'Nessuna azienda associata a questo recruiter.');
        }

        $validated['company_id'] = $company->id;

        $job = $company->jobs()->create($validated);

        return redirect()->route('jobs.show', $job)->with('success', 'Offerta pubblicata con successo.');
    }

    public function show($id)
    {
        $job = JobOffert::whereHas('company', function ($query) {
            $query->where('user_id', auth()->id());
        })->findOrFail($id);

        return view('jobs.show', compact('job'));
    }

    public function edit($id)
    {
        $job = JobOffert::whereHas('company', function ($query) {
            $query->where('user_id', auth()->id());
        })->findOrFail($id);

        $company = auth()->user()->company;

        return view('jobs.edit', compact('job', 'company'));
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
            'benefits' => 'nullable|array',
        ]);
        
        $validated['skills_required'] = array_map('strtolower', $validated['skills_required'] ?? []);
        $validated['benefits'] = array_map('strtolower', $validated['benefits'] ?? []);

        $job->update([
            ...$validated,
            'skills_required' => json_encode($validated['skills_required'] ?? []),
            'benefits' => json_encode($validated['benefits'] ?? []),
        ]);

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
            $query->whereJsonContains('skills_required', $skill);
            
        }
        
        if ($request->filled('benefit')) {
            $benefit = $request->benefit;
            $query->whereJsonContains('benefits', $benefit);
        }

        $allSkills = JobOffert::where('is_active', true)
            ->pluck('skills_required')
            ->map(function ($skills) {
                if (is_array($skills)) {
                    return array_map('strtolower', $skills);
                }
                if (is_string($skills)) {
                    return array_map('strtolower', json_decode($skills, true) ?: []);
                }
                return [];
            })
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        $allBenefits = JobOffert::where('is_active', true)
            ->pluck('benefits')
            ->map(function ($benefits) {
                if (is_array($benefits)) {
                    return array_map('strtolower', $benefits);
                }
                if (is_string($benefits)) {
                    return array_map('strtolower', json_decode($benefits, true) ?: []);
                }
                return [];
            })
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        $savedJobs = auth()->user()->savedJobs()->paginate(10);
        // Ottieni i job filtrati con paginazione
        $jobs = $query->latest()->paginate(10)->withQueryString();

        // Passa tutto alla view
        return view('jobs.publicIndex', compact('jobs', 'allSkills', 'allBenefits', 'savedJobs'));
    }

    public function publicShow($id)
    {
        $job = JobOffert::where('is_active', true)->findOrFail($id);
        return view('jobs.publicShow', compact('job'));
    }
}
