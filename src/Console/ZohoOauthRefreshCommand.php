<?php

namespace Arwars\LaravelZohoOauth\Console;

use Arwars\LaravelZohoOauth\Models\ZohoOauthConfig;
use Arwars\LaravelZohoOauth\ZohoOauthInit;
use Illuminate\Console\Command;
use Arwars\LaravelZohoOauth\ZohoOauthRefresh;

class ZohoOauthRefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoauth:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new access token from refresh token.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $configs = ZohoOauthConfig::all();
        foreach($configs as $config) {
            $zohoOAuth = new ZohoOauthRefresh($config);
            $this->info($zohoOAuth->generateNewRefreshToken());
        }

        return 0;
    }
}
