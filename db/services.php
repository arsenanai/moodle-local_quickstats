<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Web service definitions for the QuickStats plugin.
 *
 * @package    local_quickstats
 * @copyright  2025 Arsen Anay
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

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
