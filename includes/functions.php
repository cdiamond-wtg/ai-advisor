<?php

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function getQuestions(): array {
    return [
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
            'name' => 'employee_count',
            'question' => "What is the company's approximate employee count?",
            'answers' => [
                '1-10',
                '11-50',
                '51-200',
                '201-500',
                '501-1,100',
                '1,001-5,000',
                'More than 5,000',
                'Unknown',
            ],
            'type' => 'radio',
            'required' => true,
        ],
    ];
}