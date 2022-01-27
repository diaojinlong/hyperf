<?php
return [
    'key' => env('JWT_KEY', ''),
    'iss' => env('JWT_ISS', ''),
    'aud' => env('JWT_AUD', ''),
    'exp_seconds' => env('JWT_EXP_SECONDS', 7200),
    'leeway_seconds' => env('JWT_LEEWAY_SECONDS', 60),
];