<?php

namespace Arwars\LaravelZohoOauth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ZohoOauth;

class ZohoOauthConfig extends Model
{
    use HasFactory;

    protected $table = 'zoho_oauth_config';

    public function oauth() {
        return $this->belongsTo(ZohoOauth::class, 'id', 'config_id');
    }
}
