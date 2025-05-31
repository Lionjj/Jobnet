<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Benefit;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $company = auth()->user()->company;

        if ($company) {
            return redirect()->route('companies.show', $company);
        }

        return redirect()->route('companies.create')->with('info', 'Crea la tua azienda per iniziare.');
    }


    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'company_culture' => 'nullable|string',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string|max:255',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|max:2048', // max 2MB
        ]);

        $benefitNames = $validated['benefits'] ?? [];

        $benefitIds = [];

        foreach ($benefitNames as $name) {
            $name = trim(strtolower($name));
            if ($name === '') continue;

            $benefit = Benefit::firstOrCreate(['name' => $name]);
            $benefitIds[] = $benefit->id;
        }

        $validated['user_id'] = auth()->id();

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $company = Company::create($validated);

        $company->benefits()->sync($benefitIds);

        return redirect()->route('companies.show', $company)->with('success', 'Azienda creata con successo.');
    }

    public function show($id)
    {
        $company = auth()->user()->company()->findOrFail($id);
        return view('companies.show', compact('company'));
    }

    public function edit($id)
    {
        $company = Company::with('benefits')->findOrFail($id);
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $company = auth()->user()->company()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'company_culture' => 'nullable|string',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string|max:255',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|max:2048', // max 2MB
        ]);

        $benefitNames = $validated['benefits'] ?? [];

        $benefitIds = [];

        foreach ($benefitNames as $name) {
            $name = trim(strtolower($name));  // puoi fare normalizzazione

            if ($name === '') continue;

            // cerca il benefit o crealo
            $benefit = Benefit::firstOrCreate(['name' => $name]);

            $benefitIds[] = $benefit->id;
        }

        // Ora sincronizzi i benefit associati alla company
        $company->benefits()->sync($benefitIds);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $company->update($validated);

        return redirect()->route('companies.show', $company)->with('success', 'Azienda aggiornata con successo.');
    }

    public function destroy($id)
    {
        $company = auth()->user()->company()->findOrFail($id);
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Azienda eliminata con successo.');
    }

     public function publicShow($id)
    {
        $company = Company::findOrFail($id);
        return view('companies.publicShow', compact('company'));
    }
}
