<?php

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function getQuestions(): array {
    return [
        [
            'category' => 'Profile',
            'questions' => [
                [
                    'name' => 'website',
                    'question' => 'Enter the company website URL.',
                    'type' => 'text',
                    'required' => true,
                ],
                [
                    'name' => 'linkedin',
                    'question' => 'Enter the company LinkedIn page.',
                    'type' => 'text',
                ],
                [
                    'name' => 'industry',
                    'question' => 'What industry does the company primarily operate in?',
                    'answers' => [
                        'other',
                        'unknown',
                    ],
                    'type' => 'select',
                    'required' => true,
                ],
                [
                    'name' => 'employee_count',
                    'question' => "What is the company's approximate employee count?",
                    'answers' => [
                        '1 - 10',
                        '11 - 50',
                        '51 - 200',
                        '201 - 500',
                        '501 - 1,100',
                        '1,001 - 5,000',
                        '5,001 - 10,000',
                        '10,001 +',
                        'Unknown',
                    ],
                    'type' => 'radio',
                    'required' => true,
                ],
                [
                    'name' => 'annual_revenue',
                    'question' => "What is the company's approximate annual revenue?",
                    'answers' => [
                        'Under $500,000',
                        '$500,000 - $1 million',
                        '$1 million - $2.5 million',
                        '$2.5 million - $5 million',
                        '$5 million - $10 million',
                        '$10 million - $100 million',
                        '$100 million - $500 million',
                        '$500 million - $1 billion',
                        'Over $1 billion',
                        'Unknown',
                    ],
                    'type' => 'radio',
                ],
                [
                    'name' => 'location',
                    'question' => 'Where is the company primarily located?',
                    'fields' => [
                        'City',
                        'State',
                        'Country',
                    ],
                    'type' => 'text_group',
                ],
            ],
        ],
    ];
}