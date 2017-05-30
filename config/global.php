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
    'URL_VERIFICACION_ASIGNACION' => env('VERIFICACION_ASIGNACION', '/teorico/public/verificarAsignacion'),
    'IMAGE_USER_DEFAULT' => env('IMAGE_USER_DEFAULT', '/teorico/public/production/images/user.png'),
    'url' => env('APP_URL', 'http://192.168.76.196'),
    'RELOAD_PCS_MONITOR' => env('RELOAD_PCS_MONITOR', '5000'),
];
