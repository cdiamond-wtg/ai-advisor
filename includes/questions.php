<?php

return [
    [
        'category' => 'Company Profile',
        'questions' => [
            [
                'name' => 'company_focus',
                'question' => 'What is the company\'s primary business focus?',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'company_size',
                'question' => 'What is the approximate company size?',
                'type' => 'radio',
                'answers' => [
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ],
                'required' => true,
            ],
        ],
    ],
    [
        'category' => 'Business Needs',
        'questions' => [
            [
                'name' => 'primary_challenge',
                'question' => 'What is the company\'s primary business challenge?',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'ai_experience',
                'question' => 'Has the company used AI before?',
                'type' => 'radio',
                'answers' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                    'unknown' => 'Unknown',
                ],
                'required' => true,
            ],
        ],
    ],
];
