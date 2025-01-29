<?php
/**
 * Task definitions for the QuickStats plugin.
 *
 * @package    local_quickstats
 */

defined('MOODLE_INTERNAL') || die();

$tasks = [
    [
        'classname' => 'local_quickstats\task\update_active_users',
        'blocking'  => 0,
        'minute'    => '0',
        'hour'      => '0',
        'day'       => '*',
        'dayofweek' => '*',
        'month'     => '*'
    ]
];
