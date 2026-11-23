<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncOwnerMasterCommand extends Command
{
    protected $signature = 'products:sync-owner-master
        {--dry-run : Deprecated}
        {--apply : Deprecated}';

    protected $description = 'Disabled: this legacy command can insert or delete products';

    public function handle(): int
    {
        $this->error(
            'This legacy command is disabled because it can change product IDs and delete products. '
            . 'Use products:sync-owner-codes-v4 --dry-run or --apply instead.'
        );

        return self::FAILURE;
    }
}
