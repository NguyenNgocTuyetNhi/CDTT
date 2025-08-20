<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PendingOrder;

class CleanupExpiredPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pending-orders:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired pending orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredCount = PendingOrder::where('expires_at', '<', now())->delete();
        
        $this->info("Cleaned up {$expiredCount} expired pending orders.");
        
        return Command::SUCCESS;
    }
}
