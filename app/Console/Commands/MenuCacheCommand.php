<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\MenuHelper;

class MenuCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh menu cache from JSON file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            if (!class_exists(MenuHelper::class)) {
                $this->error('❌ MenuHelper class not found!');
                $this->info('Please create app/Helpers/MenuHelper.php first.');
                return 1;
            }

            MenuHelper::refreshCache();
            $this->info('✅ Menu cache refreshed successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to refresh menu cache: ' . $e->getMessage());
            return 1;
        }
    }
}
