<?php

return [
    [
        'category' => 'Company Profile',
        'questions' => [
            [
                'name' => 'website',
                'type' => 'url',
                'question' => 'Enter the company website URL.',
                'required' => true,
            ],
            [
                'name' => 'linkedin',
                'type' => 'url',
                'question' => 'Enter the company LinkedIn page.',
            ],
            [
                'name' => 'industry',
                'type' => 'select',
                'question' => 'What industry does the company primarily operate in?',
                'answers' => [
                    'other' => 'Other',
                    'unknown' => 'Unknown',
                ],
                'required' => true,
            ],
            [
                'name' => 'employee_count',
                'type' => 'select',
                'question' => 'What is the company\'s approximate employee count?',
                'answers' => [
                    '1_10' => '1 - 10',
                    '11_50' => '11 - 50',
                    '51_200' => '51 - 200',
                    '201_500' => '201 - 500',
                    '501_1000' => '501 - 1,000',
                    '1001_5000' => '1,001 - 5,000',
                    '5001_10000' => '5,001 - 10,000',
                    '10000_plus' => '10,001 +',
                    'unknown' => 'Unknown',
                ],
                'required' => true,
            ],
            [
                'name' => 'annual_revenue',
                'type' => 'select',
                'question' => "What is the company's approximate annual revenue?",
                'answers' => [
                    'under_500k' => 'Under $500,000',
                    '500k_1m' => '$500,000 - $1 million',
                    '1m_2p5m' => '$1 million - $2.5 million',
                    '2p5m_5m' => '$2.5 million - $5 million',
                    '5m_10m' => '$5 million - $10 million',
                    '10m_100m' => '$10 million - $100 million',
                    '100m_500m' => '$100 million - $500 million',
                    '500m_1b' => '$500 million - $1 billion',
                    'over_1b' => 'Over $1 billion',
                    'unknown' => 'Unknown',
                ],
            ],
        ],
    ],
];
