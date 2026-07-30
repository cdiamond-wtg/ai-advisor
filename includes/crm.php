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

// get choice metadata --> maps integers to labels for option set columns
function getChoiceOptions(string $table, string $column, ?string $accessToken): ?array {
    
    $environmentUrl = getEnvironmentUrl();
    if (!$environmentUrl) return null;

    $url = $environmentUrl . "/api/data/v9.2/EntityDefinitions(LogicalName='{$table}')" .
        "/Attributes(LogicalName='{$column}')/Microsoft.Dynamics.CRM.PicklistAttributeMetadata" .
        '?$select=LogicalName&$expand=OptionSet,GlobalOptionSet&LabelLanguages=1033';
    $options = [CURLOPT_HTTPGET => true];
    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Accept: application/json',
        'OData-MaxVersion: 4.0',
        'OData-Version: 4.0',
    ];
    $data = sendRequest($url, $options, $headers);

    if (!is_array($data)) return null;
    $optionSet = $data['GlobalOptionSet'] ?? $data['OptionSet'] ?? null;
    if (!is_array($optionSet) || !isset($optionSet['Options'])) return null;

    $mapping = [];
    foreach ($optionSet['Options'] as $option) {
        if (!isset($option['Value'])) continue;
        $label = $option['Label']['UserLocalizedLabel']['Label'] ?? null;
        if ($label === null) continue;
        $mapping[(int) $option['Value']] = $label;
    }

    return $mapping;
}

// get account data from crm
function getAccountData(string $account, ?string $accessToken): ?array {

    $environmentUrl = getEnvironmentUrl();
    if (!$environmentUrl) return null;

    $query = http_build_query([
        '$select' => 'accountid,name,websiteurl,wtg_industry,numberofemployees,revenue,'
            . 'address1_city,address1_stateorprovince,address1_country,'
            . 'customertypecode,description',
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

    return is_array($data) && array_key_exists('value', $data) ? $data['value'][0] : null;
}

// map account data to profile questions
function mapAccountData(array $data): ?array {
    if ($data === null) return null;
    return [
        'website' => $data['websiteurl'] ?? '',
        'industry' => $data['wtg_industry'] ?? '',
        'employee_count' => $data['numberofemployees'] ?? '',
        'annual_revenue' => $data['revenue'] ?? '',
        'location' => [
            'city' => $data['address1_city'] ?? '',
            'state' => $data['address1_stateorprovince'] ?? '',
            'country' => $data['address1_country'] ?? '',
        ],
        'relationship' => $data['customertypecode'] ?? '',
        'description' => $data['description'] ?? '',
    ];
}

$token = getAccessToken();
$data = getAccountData('Advantage Surveillance', $token);
$map = mapAccountData($data);
var_dump($map);
var_dump(getChoiceOptions('account', 'customertypecode', $token));