<?php
/**
 * Web service definitions for the QuickStats plugin.
 *
 * @package    local_quickstats
 */

$functions = [
    'local_quickstats_get_active_users' => [
        'classname'     => 'local_quickstats\classes\externallib',  // Correct namespace
        'methodname'    => 'get_active_users',
        'classpath'     => 'local/quickstats/classes/externallib.php',  // Correct file path
        'description'   => 'Returns active users count for the past N days.',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true,
    ],
];

$services = [
    'QuickStats Service' => [
        'functions'       => ['local_quickstats_get_active_users'],
        'restrictedusers' => 0,
        'enabled'         => 1,
    ],
];
