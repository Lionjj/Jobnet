<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * I comandi custom da registrare.
     *
     * @var array
     */
    protected $commannonds = [
        // Qui registri i tuoi comandi personalizzati, ad es.:
        // \App\Console\Commands\RefreshApp::class,
        \App\Console\Commands\RefreshApp::class,
    ];

    /**
     * Definisce i comandi da eseguire con schedule:run
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Registra i file dei comandi presenti nella directory `routes/console.php`
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
