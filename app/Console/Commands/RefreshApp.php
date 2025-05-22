<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RefreshApp extends Command
{
    protected $signature = 'app:refresh';
    protected $description = 'Pulisce la config e fa migrate:fresh con i seed';

    public function handle()
    {
        if (gethostname() !== 'laravel_app') {
            $this->error("❌ Devi eseguire questo comando **dentro** il container Docker (laravel_app).");
            $this->info("💡 Usa: docker exec -it laravel_app php artisan app:refresh");
            return Command::FAILURE;
        }


        $this->info('🔁 Pulizia cache config...');
        Artisan::call('config:clear');
        $this->line(Artisan::output());

        $this->info('🔄 Esecuzione migrate:fresh --seed...');
        Artisan::call('migrate:fresh', ['--seed' => true]);
        $this->line(Artisan::output());

        $this->info('✅ App ripristinata con successo!');
        return Command::SUCCESS;
    }
}
