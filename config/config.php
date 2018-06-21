<?php

declare(strict_types=1);

return [

    // Forms Database Tables
    'tables' => [
        'forms' => 'forms',
        'form_responses' => 'form_responses',
    ],

    // Forms Models
    'models' => [
        'form' => \Rinvex\Forms\Models\Form::class,
        'form_response' => \Rinvex\Forms\Models\FormResponse::class,
    ],

];
