<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            'Italiano',
            'Inglese',
            'Spagnolo',
            'Francese',
            'Tedesco',
            'Cinese',
            'Giapponese',
            'Arabo',
            'Portoghese',
            'Russo',
        ];

        foreach ($languages as $name) {
            Language::firstOrCreate(['name' => $name]);
        }
    }
}
