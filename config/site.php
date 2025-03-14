<?php
return [
    'prices' => [
        'ps4' => [
            'weekdays' => 30000,
            'weekends' => 80000
        ],
        'ps5' => [
            'weekdays' => 40000,
            'weekends' => 90000
        ]
    ],
    'business_hours' => [
        [
            'daysOfWeek' => [1, 2, 3, 4, 5], // Senin - Jumat
            'startTime' => '08:00',
            'endTime' => '17:00',
        ],
        [
            'daysOfWeek' => [6], // Sabtu
            'startTime' => '09:00',
            'endTime' => '23:00',
        ],
        [
            'daysOfWeek' => [0], // Minggu
            'startTime' => '08:00',
            'endTime' => '23:00',
        ],
    ],
];