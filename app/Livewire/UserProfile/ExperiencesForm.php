<?php

namespace App\Livewire\UserProfile;

use Livewire\Component;
use App\Models\Experience;
use Illuminate\Support\Facades\Auth;

class ExperiencesForm extends Component
{
    public array $experiences = [];

    public function mount()
    {
        $this->loadUserExperiences();
    }

    private function loadUserExperiences()
    {
        $this->experiences = [];

        $user = Auth::user();

        if ($user && $user->relationLoaded('experiences') || $user->experiences()->exists()) {
            $this->experiences = $user->experiences->map(function ($exp) {
                return [
                    'id' => $exp->id,
                    'role' => $exp->role,
                    'company' => $exp->company,
                    'description' => $exp->description,
                    'start_date' => $exp->start_date?->format('Y-m-d'),
                    'end_date' => $exp->end_date?->format('Y-m-d'),
                    'is_current' => (bool) $exp->is_current, // forza bool
                ];
            })->toArray();
        }
    }


    public function addExperience()
    {
        $this->experiences[] = [
            'id' => null,
            'role' => '',
            'company' => '',
            'description' => '',
            'start_date' => '',
            'end_date' => '',
            'is_current' => false,
        ];
    }

    public function removeExperience($index)
    {
        $experience = $this->experiences[$index];

        // Se esiste nel DB, lo rimuove
        if (!empty($experience['id'])) {
            Experience::where('id', $experience['id'])
                ->where('user_id', Auth::id())
                ->delete();
        }

        // Rimuove dal form
        unset($this->experiences[$index]);
        $this->experiences = array_values($this->experiences);
    }

    public function save()
    {
        $this->validate([
            'experiences.*.role' => 'required|string|max:255',
            'experiences.*.company' => 'required|string|max:255',
            'experiences.*.description' => 'nullable|string',
            'experiences.*.start_date' => 'nullable|date',
            'experiences.*.end_date' => 'nullable|date|after_or_equal:experiences.*.start_date',
        ], [
            'experiences.*.role.required' => 'Il campo "Ruolo" è obbligatorio.',
            'experiences.*.company.required' => 'Il campo "Azienda" è obbligatorio.',
            'experiences.*.start_date.date' => 'La data di inizio non è valida.',
            'experiences.*.end_date.date' => 'La data di fine non è valida.',
            'experiences.*.end_date.after_or_equal' => 'La data di fine deve essere successiva o uguale alla data di inizio.',
        ]);

        $user = Auth::user();
        $foundCurrent = false;

        foreach ($this->experiences as $exp) {
            // Salta esperienze vuote
            if (
                empty($exp['role']) &&
                empty($exp['company']) &&
                empty($exp['description']) &&
                empty($exp['start_date']) &&
                empty($exp['end_date'])
            ) continue;

            // Normalizza i dati
            $exp['description'] = $exp['description'] ?: null;
            $exp['start_date'] = $exp['start_date'] ?: null;
            $exp['end_date'] = $exp['end_date'] ?: null;
            $isCurrent = $exp['is_current'] ?? false;

            // Se c'è già un'altra esperienza corrente, forziamo false
            if ($isCurrent && !$foundCurrent) {
                $foundCurrent = true;
            } else {
                $isCurrent = false;
            }

            if (!empty($exp['id'])) {
                Experience::where('id', $exp['id'])
                    ->where('user_id', $user->id)
                    ->update([
                        'role' => $exp['role'],
                        'company' => $exp['company'],
                        'description' => $exp['description'],
                        'start_date' => $exp['start_date'],
                        'end_date' => $exp['end_date'],
                        'is_current' => $isCurrent,
                    ]);
            } else {
                $user->experiences()->create([
                    'role' => $exp['role'],
                    'company' => $exp['company'],
                    'description' => $exp['description'],
                    'start_date' => $exp['start_date'],
                    'end_date' => $exp['end_date'],
                    'is_current' => $isCurrent,
                ]);
            }
        }

        session()->flash('message', 'Esperienze salvate con successo!');
    }


    public function render()
    {
        return view('livewire.user-profile.experiences-form');
    }
}
