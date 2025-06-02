<?php

namespace App\Livewire\UserProfile;

use Livewire\Component;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;

class LanguagesForm extends Component
{
    public array $languages = [];
    public array $availableLanguages = [];

    public function mount()
    {
        $this->availableLanguages = Language::pluck('name', 'id')->toArray();

        $this->languages = Auth::user()?->languages->map(function ($lang) {
            return [
                'id' => $lang->id,
                'language_id' => $lang->pivot->language_id,
                'level' => $lang->pivot->level,
                'certificate' => $lang->pivot->certificate,
            ];
        })->toArray();
    }

    public function addLanguage()
    {
        $this->languages[] = [
            'language_id' => '',
            'level' => '',
            'certificate' => '',
        ];
    }

    public function removeLanguage($index)
    {
        if (isset($this->languages[$index]['language_id'])) {
            Auth::user()->languages()->detach($this->languages[$index]['language_id']);
        }

        unset($this->languages[$index]);
        $this->languages = array_values($this->languages);
    }

    public function save()
    {
        $this->validate([
            'languages.*.language_id' => 'required|exists:languages,id',
            'languages.*.level' => 'required|string|max:255',
            'languages.*.certificate' => 'nullable|string|max:255',
        ], [
            'languages.*.language_id.required' => 'Il campo "Lingua" è obbligatorio.',
            'languages.*.language_id.exists' => 'La lingua selezionata non è valida.',
            'languages.*.level.required' => 'Il campo "Livello" è obbligatorio.',
        ]);


        // Detach & reattach per semplificare la sincronizzazione
        $syncData = [];
        foreach ($this->languages as $lang) {
            if (!empty($lang['language_id'])) {
                $syncData[$lang['language_id']] = [
                    'level' => $lang['level'],
                    'certificate' => $lang['certificate'] ?? null,
                ];
            }
        }

        Auth::user()->languages()->sync($syncData);

        session()->flash('message', 'Competenze linguistiche aggiornate!');
    }

    public function render()
    {
        return view('livewire.user-profile.languages-form');
    }
}
