<?php
return [
    'environment' => 'live', // Cambiar a 'test' si estás en desarrollo
    'key' => env('REDSYS_KEY', 'YOUR_REDSYS_KEY'), // Tu clave secreta de Redsys
    'merchant_code' => env('REDSYS_MERCHANT_CODE', 'YOUR_MERCHANT_CODE'),
    'terminal' => env('REDSYS_TERMINAL', '1'),
    'url_notification' => env('REDSYS_URL_NOTIFICATION', 'https://tu-dominio.com/callback-url'),
    'url_ok' => env('REDSYS_URL_OK', 'https://tu-dominio.com/pago-exito'),
    'url_ko' => env('REDSYS_URL_KO', 'https://tu-dominio.com/pago-fallo'),
];
