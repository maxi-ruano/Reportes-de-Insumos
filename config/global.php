<?php

return [

    'RELOAD_BLOQUEO_TEORICO' => env('RELOAD_BLOQUEO_TEORICO', '300000'),
    'URL_FOTOS' => env('URL_FOTOS', '5000'),
    'IMAGENES_PREGUNTAS' => env('IMAGENES_PREGUNTAS', 'http://192.168.76.200/etlnuevo/assets/images/'),
    'URL_EXAMEN_TEORICO' => env('URL_EXAMEN_TEORICO', '/teorico/public/'),

    'LETRAS' => env('LETRAS', 'ABCDEFGHYJKLMNÑOPQRSTUVWXYZ'),
    //ajax
    'GUARDAR_RESPUESTA_EXAMEN' => env('GUARDAR_RESPUESTA_EXAMEN', '/teorico/public/guardar_respuesta'),
    'URL_COMPUTADORAS_MONITOR' => env('URL_COMPUTADORAS_MONITOR', '/teorico/public/computadorasMonitor'),
    'url' => env('APP_URL', 'http://192.168.76.33'),
    'RELOAD_PCS_MONITOR' => env('RELOAD_PCS_MONITOR', '5000'),
];
