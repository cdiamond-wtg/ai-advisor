<?php

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function getQuestions(): array {
    return [
        [
            'name' => 'company_size',
            'question' => "What is the approximate employee size of the customer's organization?",
            'answers' => [
                'answer_1' => '1-50 employees',
                'answer_2' => '51-250 employees',
                'answer_3' => '251-1,000 employees',
                'answer_4' => '1,001-5,000 employees',
                'answer_5' => 'More than 5,000 employees',
            ],
            'type' => 'radio',
        ],
        [
            'name' => 'industry',
            'question' => "What industry or sector best describes the customer's organization?",
            'answers' => [
                'answer_1' => 'Professional services',
                'answer_2' => 'Financial services or insurance',
                'answer_3' => 'Healthcare or life sciences',
                'answer_4' => 'Manufacturing, industrials, or logistics',
                'answer_5' => 'Retail, consumer goods, or ecommerge',
                'answer_6' => 'Technology, software, or telecommunications',
                'answer_7' => 'Education, nonprofit, or public sector',
                'answer_8' => 'Real estate, construction, or facilities',
                'answer_9' => 'Energy, utilities, or natural resources',
                'answer_10' => 'Media, entertainment, or publishing',
                'answer_11' => 'Travel, transportation, or hospitality',
                'answer_12' => 'Other',
            ],
            'type' => 'radio',
        ],
    ];
}