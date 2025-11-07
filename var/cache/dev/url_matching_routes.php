<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/infractions' => [
            [['_route' => 'infraction_list', '_controller' => 'App\\Controller\\InfractionController::list'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'infraction_create', '_controller' => 'App\\Controller\\InfractionController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/login' => [[['_route' => 'api_login'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/api/ecuries/([^/]++)/pilotes(*:71)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        71 => [
            [['_route' => 'ecurie_update_pilotes', '_controller' => 'App\\Controller\\EcurieController::updatePilotes'], ['id'], ['PATCH' => 0], null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
