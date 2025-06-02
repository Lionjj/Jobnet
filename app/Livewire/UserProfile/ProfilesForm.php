<?php

namespace App\Livewire\UserProfile;

use Livewire\Component;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ProfilesForm extends Component
{
    public array $profiles = [];

    public function mount()
    {
        $this->profiles = Auth::user()->profiles->map(fn($p) => [
            'id' => $p->id,
            'type' => $p->type,
            'url' => $p->url,
        ])->toArray();
    }

    public function addProfile()
    {
        $this->profiles[] = ['id' => null, 'type' => '', 'url' => ''];
    }

    public function removeProfile($index)
    {
        if (!empty($this->profiles[$index]['id'])) {
            Profile::find($this->profiles[$index]['id'])?->delete();
        }

        unset($this->profiles[$index]);
        $this->profiles = array_values($this->profiles);
    }

    public function save()
    {
        $this->validate([
            'profiles.*.type' => 'required|string|max:255',
            'profiles.*.url' => 'required|url|max:255',
        ], [
            'profiles.*.type.required' => 'Il tipo del profilo è obbligatorio.',
            'profiles.*.url.required' => 'L\'URL è obbligatorio.',
        ]);

        $user = Auth::user();

        foreach ($this->profiles as $profile) {
            if (!empty($profile['id'])) {
                Profile::where('id', $profile['id'])->where('user_id', $user->id)->update($profile);
            } else {
                $user->profiles()->create($profile);
            }
        }

        session()->flash('message', 'Profili aggiornati con successo!');
    }

    public function render()
    {
        return view('livewire.user-profile.profiles-form');
    }
}
