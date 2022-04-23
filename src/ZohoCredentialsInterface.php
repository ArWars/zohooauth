<?php

namespace Arwars\LaravelZohoOauth;

interface ZohoCredentialsInterface
{
    public function prepareData(array $responseData): array;
}
