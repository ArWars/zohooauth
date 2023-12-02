<?php

namespace Arwars\LaravelZohoOauth\Console;

use Arwars\LaravelZohoOauth\Models\ZohoOauthConfig;
use Illuminate\Console\Command;
use Arwars\LaravelZohoOauth\ZohoOauthInit;

class ZohoOauthInitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoauth:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize Zoho oauth refresh_token and access_token.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info(app(ZohoOauthInit::class)->initializeTokens());
        return 0;
    }
}
