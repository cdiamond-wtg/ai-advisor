<?php

// send request to api
function sendRequest(string $url, array $options, array $header): ?array {  # change return type
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30,
    ) + $options);
    $response = curl_exec($curl);
    return json_decode($response, true);
}

// get environment url for crm
function getEnvironmentUrl(): string {
    return rtrim(getenv('MS_ENVIRONMENT_URL'), '/');
}

// get access token for crm reader app
function getAccessToken(): ?string {

    # add caching --> cache access token and expiration time, and reuse until expired
    # --> only request new token once cached token expired

    $tenantId = getenv('MS_TENANT_ID');
    $clientId = getenv('MS_CLIENT_ID');
    $clientSecret = getenv('MS_CLIENT_SECRET');
    $environmentUrl = getEnvironmentUrl();
    if (!$tenantId || !$clientId || !$clientSecret || !$environmentUrl) return null;

    $url = 'https://login.microsoftonline.com/' . rawurlencode($tenantId) . '/oauth2/v2.0/token';
    $header = ['Content-Type: application/x-www-form-urlencoded'];
    $options = [
        CURLOPT_POSTFIELDS => http_build_query([
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => $environmentUrl . '/.default',
        ], '', '&', PHP_QUERY_RFC3986)
    ];
    $data = sendRequest($url, $options, $header);

    return is_array($data) && array_key_exists('access_token', $data) ? $data['access_token'] : null;
}

// get account data from crm
function getAccountData(string $account, ?string $accessToken): ?array {

    $environmentUrl = getEnvironmentUrl();
    if (!$environmentUrl) null;

    $query = http_build_query([
        '$select' => 'accountid,name,revenue,numberofemployees',
        '$filter' => "name eq '{$account}'",
        '$top' => 1,
    ], '', '&', PHP_QUERY_RFC3986);
    $url = rtrim($environmentUrl, '/') . '/api/data/v9.2/accounts?' . $query;
    $options = [CURLOPT_HTTPGET => true];
    $header = [
        'Authorization: Bearer ' . $accessToken,
        'Accept: application/json',
        'OData-MaxVersion: 4.0',
        'OData-Version: 4.0',
    ];
    $data = sendRequest($url, $options, $header);

    return is_array($data) && array_key_exists('value', $data) ? $data['value'] : null;
}

// map account data to profile questions

$token = getAccessToken();
var_dump(getAccountData('10Pearls', $token));