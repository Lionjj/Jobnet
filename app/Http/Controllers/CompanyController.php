<?php

namespace App\Http\Controllers;

use App\Models\Company;
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
            'website' => 'nullable|url',
            'logo' => 'nullable|image|max:2048', // max 2MB
        ]);

        $validated['user_id'] = auth()->id();
        $validated['benefits'] = array_map('strtolower', $validated['benefits'] ?? []);
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $company = Company::create($validated);

        return redirect()->route('companies.show', $company)->with('success', 'Azienda creata con successo.');
    }

    public function show($id)
    {
        $company = auth()->user()->company()->findOrFail($id);
        return view('companies.show', compact('company'));
    }

    public function edit($id)
    {
        $company = auth()->user()->company()->findOrFail($id);
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
            'website' => 'nullable|url',
            'logo' => 'nullable|image|max:2048', // max 2MB
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $validated['benefits'] = array_map('strtolower', $validated['benefits'] ?? []);
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
