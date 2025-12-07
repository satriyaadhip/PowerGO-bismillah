<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Http\Controllers\GraphController;

class UpdateKwhBalanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kwh-balance:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update kwh_balance for all prabayar customers based on actual usage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting kwh_balance update for all prabayar customers...');
        
        // Get all prabayar customers
        $customers = Customer::where('billing_type', 'prabayar')->get();
        
        if ($customers->isEmpty()) {
            $this->info('No prabayar customers found.');
            return 0;
        }
        
        $updated = 0;
        $controller = app(GraphController::class);
        
        foreach ($customers as $customer) {
            try {
                // Call public method to update kwh_balance
                $controller->updateKwhBalanceFromUsage($customer);
                
                $customer->refresh();
                $updated++;
                
                $this->line("Updated customer ID {$customer->id}: kwh_balance = {$customer->kwh_balance}");
            } catch (\Exception $e) {
                $this->error("Failed to update customer ID {$customer->id}: " . $e->getMessage());
            }
        }
        
        $this->info("Successfully updated {$updated} customer(s).");
        return 0;
    }
}
