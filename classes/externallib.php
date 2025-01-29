<?php
/**
 * External functions for the QuickStats plugin.
 *
 * @package    local_quickstats
 */

namespace local_quickstats\classes;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

class externallib extends \external_api
{
    public static function get_active_users_parameters()
    {
        return new \external_function_parameters([]);
    }

    public static function get_active_users()
    {
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

    public static function get_active_users_returns()
    {
        return new \external_single_structure([
            'labels' => new \external_multiple_structure(new \external_value(PARAM_TEXT, 'Date')),
            'counts' => new \external_multiple_structure(new \external_value(PARAM_INT, 'Active users count')),
        ]);
    }
}
