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
        $company = auth()->user()->company();

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
}
