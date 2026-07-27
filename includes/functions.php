<?php

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function getQuestions(): array {
    return [
        [
            'name' => 'dummy_answer',
            'question' => 'Dummy question?',
            'answers' => [
                'answer_1' => 'Answer 1',
                'answer_2' => 'Answer 2',
                'answer_3' => 'Answer 3',
            ],
            'type' => 'radio',
        ],
        [
            'name' => 'dummy_answer_2',
            'question' => 'Dummy question 2?',
            'answers' => [
                'answer_1' => 'Answer 1',
                'answer_2' => 'Answer 2',
                'answer_3' => 'Answer 3',
            ],
            'type' => 'checkbox',
        ],
    ];
}