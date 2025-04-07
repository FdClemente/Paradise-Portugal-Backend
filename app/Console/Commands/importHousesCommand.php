<?php

namespace App\Console\Commands;

use App\Services\Wordpress\ImportService;
use Illuminate\Console\Command;

class importHousesCommand extends Command
{
    protected $signature = 'import:houses';

    protected $description = 'Import houses from wordpress';

    public function handle(): void
    {
        $service = new ImportService();

        $service->import();
    }
}
