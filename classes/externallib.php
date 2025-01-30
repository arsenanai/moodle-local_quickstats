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
 * External functions for the QuickStats plugin.
 *
 * @package    local_quickstats
 * @copyright  2025 Arsen Anay
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_quickstats\classes;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * Class externallib
 *
 * Provides external functions for the QuickStats plugin.
 */
class externallib extends \external_api {

    /**
     * Returns description of method parameters.
     *
     * @return \external_function_parameters
     */
    public static function get_active_users_parameters() {
        return new \external_function_parameters([]);
    }

    /**
     * Returns active users count for the past N days.
     *
     * @return array of active users count.
     */
    public static function get_active_users() {
        global $DB;

        $params = self::validate_parameters(self::get_active_users_parameters(), []);

        $config = get_config('local_quickstats');
        $ndays = isset($config->ndays) ? (int) $config->ndays : 7;

        $data = ['labels' => [], 'counts' => []];
        $sql = "
            SELECT periodstart, activeuserscount
            FROM {local_quickstats}
            WHERE periodstart >= :starttime
            ORDER BY periodstart DESC
            LIMIT $ndays
        ";
        $records = $DB->get_records_sql($sql, ['starttime' => strtotime("-{$ndays} days midnight")]);

        foreach ($records as $record) {
            $data['labels'][] = date('Y-m-d', $record->periodstart);
            $data['counts'][] = (int) $record->activeuserscount;
        }

        return $data;
    }

    /**
     * Returns description of method result value.
     *
     * @return \external_single_structure
     */
    public static function get_active_users_returns() {
        return new \external_single_structure([
            'labels' => new \external_multiple_structure(new \external_value(PARAM_TEXT, 'Date')),
            'counts' => new \external_multiple_structure(new \external_value(PARAM_INT, 'Active users count')),
        ]);
    }
}
