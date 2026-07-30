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
    return rtrim(getenv('MS_CRM_ENVIRONMENT_URL'), '/');
}

// get access token for crm reader app
function getAccessToken(): ?string {

    # add caching --> cache access token and expiration time, and reuse until expired
    # --> only request new token once cached token expired

    $tenantId = getenv('MS_TENANT_ID');
    $clientId = getenv('MS_CRM_CLIENT_ID');
    $clientSecret = getenv('MS_CRM_CLIENT_SECRET');
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

    $account = str_replace("'", "\'", $account);  // escape single quotes
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

    return is_array($data) && isset($data['value'][0]) ? $data['value'][0] : null;
}

// map account data to profile questions
function mapAccountData(?array $data, ?string $accessToken): ?array {
    if ($data === null) return null;

    // map employee count and annual revenue to specified ranges
    $employees = (int) $data['numberofemployees'] ?? 0;
    if ($employees >= 1 && $employees <= 10) $employees = '1_10';
    elseif ($employees >= 11 && $employees <= 50) $employees = '11_50';
    elseif ($employees >= 51 && $employees <= 200) $employees = '51_200';
    elseif ($employees >= 201 && $employees <= 500) $employees = '201_500';
    elseif ($employees >= 501 && $employees <= 1000) $employees = '501_1000';
    elseif ($employees >= 1001 && $employees <= 5000) $employees = '1001_5000';
    elseif ($employees >= 5001 && $employees <= 10000) $employees = '5001_10000';
    elseif ($employees >= 10001) $employees = '10001_plus';
    else $employees = '';

    $revenue = (int) $data['revenue'] ?? 0;
    if ($revenue < 5e5) $revenue = 'under_500k';
    elseif ($revenue >= 5e5 && $revenue < 1e6) $revenue = '500k_1m';
    elseif ($revenue >= 1e6 && $revenue < 2.5e6) $revenue = '1m_2p5m';
    elseif ($revenue >= 2.5e6 && $revenue < 5e6) $revenue = '2p5m_5m';
    elseif ($revenue >= 5e6 && $revenue < 1e7) $revenue = '5m_10m';
    elseif ($revenue >= 1e7 && $revenue < 1e8) $revenue = '10m_100m';
    elseif ($revenue >= 1e8 && $revenue < 5e8) $revenue = '100m_500m';
    elseif ($revenue >= 5e8 && $revenue <= 1e9) $revenue = '500m_1b';
    elseif ($revenue > 1e9) $revenue = 'over_1b';

    return [
        'website' => $data['websiteurl'] ?? '',
        'industry' => $data['wtg_industry'],
        'employee_count' => $employees,
        'annual_revenue' => $revenue,
        'location' => [
            'city' => $data['address1_city'] ?? '',
            'state' => $data['address1_stateorprovince'] ?? '',
            'country' => $data['address1_country'] ?? '',
        ],
        'relationship' => $data['customertypecode'],
        'description' => $data['description'] ?? '',
    ];
}

// send json response to browser
function sendJsonResponse(array $response): never {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response, JSON_UNESCAPED_SLASHES);
    exit;
}


// handle form request --> load profile data from crm
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && 
    ($_POST['action'] ?? '') === 'load_profile'
) {
    $company = trim($_POST['company'] ?? '');

    if ($company === '') {
        sendJsonResponse([
            'profile_found' => false,
            'message' => 'Enter a company name or complete the profile manually.',
        ]);
    }

    try {
        $token = getAccessToken();
        if ($token === null) {
            sendJsonResponse([
                'profile_found' => false,
                'message' => 'Cannot connect to CRM. Enter company profile manually.',
            ]);
        }

        $data = getAccountData($company, $token);
        if ($data === null) {
            sendJsonResponse([
                'profile_found' => false,
                'message' => 'Company not found in CRM. Try a different name or enter company profile manually.',
            ]);
        }

        $profile = mapAccountData($data, $token);
        if ($profile === null) {
            sendJsonResponse([
                'profile_found' => false,
                'message' => 'CRM profile could not be loaded. Enter manually.',
            ]);
        }

        sendJsonResponse([
            'profile_found' => true,
            'message' => 'Company profile loaded from CRM. Review and edit information before submitting.',
            'profile' => $profile,
        ]);

    } catch (Throwable $exception) {
        error_log('CRM profile lookup failed: ' . $exception->getMessage());
        sendJsonResponse([
            'profile_found' => false,
            'message' => 'CRM profile unavailable. Enter company profile manually.',
        ]);
    }
}

// handle form request --> get choices from crm for dropdown menu
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['action'] ?? '') === 'load_choices'
) {
    $choiceCols = [
        'relationship' => 'customertypecode',
        'industry' => 'wtg_industry',
    ];
    $col = $_POST['column'] ?? '';
    if (!isset($choiceCols[$col])) {
        sendJsonResponse([
            'success' => false,
            'choices' => (object) [],
        ]);
    }

    $token = getAccessToken();
    if ($token === null) {
        sendJsonResponse([
            'success' => false,
            'choices' => (object) [],
        ]);
    }

    $choices = getChoiceOptions('account', $choiceCols[$col], $token) ?? [];
    sendJsonResponse([
        'success' => !empty($choices),
        'choices' => (object) $choices,
    ]);
}