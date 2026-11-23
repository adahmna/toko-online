<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        // Batalkan pesanan yang menggantung (belum masuk pembayaran) & kembalikan stok
        $schedule->command('pesanan:batalkan-menggantung')->everyFiveMinutes();

        // Pesanan "Dikirim" -> "Selesai" otomatis (kurir DELIVERED atau lewat 7 hari)
        $schedule->command('pesanan:selesaikan-terkirim')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}