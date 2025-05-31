@props(['company' => null])

@php
    $company = $company ?? new \App\Models\Company;
@endphp

<div class="space-y-6 bg-white p-6 rounded-lg shadow">
    {{-- Nome --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nome azienda</label>
        <input type="text" name="name" required
               placeholder="Es. Tech Solutions S.r.l."
               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
               value="{{ old('name', $company->name) }}">
    </div>

    {{-- Settore --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Settore</label>
        <input type="text" name="industry"
               placeholder="Es. Informatica, Finanza, Sanità"
               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
               value="{{ old('industry', $company->industry) }}">
    </div>

    {{-- Descrizione --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Descrizione</label>
        <textarea name="description" rows="4"
                  placeholder="Breve descrizione dell'azienda, attività principali, valori..."
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">{{ old('description', $company->description) }}</textarea>
    </div>

    {{-- Sede --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Sede</label>
        <input type="text" name="location"
               placeholder="Es. Milano, Italia"
               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
               value="{{ old('location', $company->location) }}">
    </div>

    {{-- Mission --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Mission</label>
        <textarea name="mission" rows="2"
                  placeholder="Scopo principale dell’azienda, obiettivi a lungo termine..."
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">{{ old('mission', $company->mission) }}</textarea>
    </div>

    {{-- Vision --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Vision</label>
        <textarea name="vision" rows="2"
                  placeholder="Come l'azienda immagina il futuro e il proprio ruolo nel mondo..."
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">{{ old('vision', $company->vision) }}</textarea>
    </div>

    {{-- Cultura aziendale --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cultura aziendale</label>
        <textarea name="company_culture" rows="2"
                  placeholder="Es. approccio collaborativo, orari flessibili, meritocrazia..."
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">{{ old('company_culture', $company->company_culture) }}</textarea>
    </div>

    {{-- Website --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
        <input type="url" name="website"
               placeholder="https://www.azienda.it"
               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
               value="{{ old('website', $company->website) }}">
    </div>

    {{-- Logo --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Logo aziendale</label>
        <input type="file" name="logo"
            class="block w-full text-sm text-gray-700 border rounded-md file:bg-blue-600 file:text-white file:border-none file:px-4 file:py-2 file:rounded-md file:cursor-pointer" />

        @if ($company->logo)
            <div class="mt-2">
                <p class="text-sm text-gray-500 mb-1">Logo attuale:</p>
                <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo {{ $company->name }}" class="h-16 rounded shadow">
            </div>
        @endif
    </div>

    {{-- Benefits --}}
    @php
        // Se è una collezione Eloquent la converti in array
        $benefits = old('benefits') ?? ($company->benefits->toArray() ?? []);
    @endphp

    @livewire('company-benefits-input', ['benefits' => $benefits])


</div>
