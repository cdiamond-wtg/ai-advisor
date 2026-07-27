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
                '1-50 employees',
                '51-250 employees',
                '251-1,000 employees',
                '1,001-5,000 employees',
                'More than 5,000 employees',
            ],
            'type' => 'radio',
        ],
        [
            'name' => 'industry',
            'question' => "What industry or sector best describes the customer's organization?",
            'answers' => [
                'Professional services',
                'Financial services or insurance',
                'Healthcare or life sciences',
                'Manufacturing, industrials, or logistics',
                'Retail, consumer goods, or ecommerge',
                'Technology, software, or telecommunications',
                'Education, nonprofit, or public sector',
                'Real estate, construction, or facilities',
                'Energy, utilities, or natural resources',
                'Media, entertainment, or publishing',
                'Travel, transportation, or hospitality',
                'Other',
            ],
            'subfields' => [
                0 => 'Consulting, advisory, legal, accounting, staffing, agencies, or other service-based businesses.',
                1 => 'Banking, lending, wealth management, payments, insurance, claims, or related financial services.',
                2 => 'Providers, clinics, life sciences, health services, medical operations, or related organizations.',
                3 => 'Manufacturing, distribution, transportation, warehousing, supply chain, or industrial operations.',
                4 => 'Retailers, brands, ecommerce businesses, consumer products, or marketplace businesses.',
                5 => 'Software, IT services, SaaS, telecom, digital platforms, or technology-enabled businesses.',
                6 => 'Schools, universities, nonprofits, government agencies, public services, or civic organizations.',
                7 => 'Real estate, property management, construction, architecture, engineering, or facilities management.',
                8 => 'Energy, utilities, mining, oil and gas, renewables, water, or natural resources.',
                9 => 'Media companies, publishers, studios, creative agencies, entertainment, or content businesses.',
                10 => 'Travel, hospitality, passenger transportation, hotels, food service, tourism, or related services.',
                11 => '',
            ],
            'type' => 'radio',
        ],
    ];
}