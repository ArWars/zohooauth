<?php

namespace Arwars\LaravelZohoOauth;

use Arwars\LaravelZohoOauth\Models\ZohoOauthConfig;
use http\Exception\RuntimeException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Arwars\LaravelZohoOauth\Models\ZohoOauth;

abstract class ZohoCredentials
{
    const END_POINT = '/oauth/v2/token';

    protected string $baseUrl;

    protected string $clientId;

    protected string $clientSecret;

    protected string $code;

    protected ZohoOauthConfig $config;

    public function __construct(ZohoOauthConfig $config)
    {
        $this->config = $config;
        $this->baseUrl = $config->base_oauth_url;
        $this->clientId = $config->client_id;
        $this->clientSecret = $config->client_secret;
        $this->code = $config->code;
    }

    public function getInitCredentials(): array
    {
        return array_merge($this->getClientBody(), [
            'code'       => $this->code,
            'grant_type' => 'authorization_code',
        ]);
    }

    public function getClientBody(): array
    {
        return [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];
    }

    public function makeRequestToZohoAccounts(array $body)
    {
        $response = Http::asForm()->post($this->getEndPointUrl(), $body);

        if (! $response->successful()) {
            throw new RuntimeException('Unexpected error occurred. Please try again later.');
        }

        return $response->json();
    }

    public function getEndPointUrl(): string
    {
        return $this->baseUrl.self::END_POINT;
    }

    public function saveTokensToDb(array $data)
    {
        return ZohoOauth::create($data);
    }

    protected function getRefreshCredentials(): array
    {
        return array_merge($this->getClientBody(), [
            'refresh_token' => $this->getRefreshToken(),
            'grant_type'    => 'refresh_token',
        ]);
    }

    protected function getRefreshToken()
    {
        return $this->getLatestTokenRecord()->refresh_token;
    }

    protected function getLatestTokenRecord()
    {
        return ZohoOauth::where('config_id', $this->config->id)->latest()->first();
    }

    protected function getErrorDescription(string $error)
    {
        $errorMessages = $this->getErrorMessages();
        return Arr::exists($errorMessages, $error) ? Arr::get($errorMessages, $error) : $errorMessages['default'];
    }

    protected function getErrorMessages()
    {
        return trans('zoauth::zoauth');
    }
}
