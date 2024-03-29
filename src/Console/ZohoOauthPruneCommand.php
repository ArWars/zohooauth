<?php

namespace Arwars\LaravelZohoOauth\Console;

use Illuminate\Console\Command;
use Arwars\LaravelZohoOauth\Models\ZohoOauth;

class ZohoOauthPruneCommand extends Command
{
    const TOKENS_TO_RETAIN = 10;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoauth:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all except the recent 10 Zoho Oauth tokens from database.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tokenCount = ZohoOauth::count();

        if ($tokenCount === 0) {
            $this->warn(trans('zoauth::zoauth.db_empty'));
            return 0;
        }

        ZohoOauth::latest()
            ->skip(self::TOKENS_TO_RETAIN)
            ->take($tokenCount)
            ->get()
            ->each(fn ($row) => $row->delete());

        $this->info('Old tokens removed successfully');

        return 0;
    }
}
