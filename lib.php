<?php
/**
 * Library of functions for the QuickStats plugin.
 *
 * @package    local_quickstats
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Updates the active users count.
 */
function local_quickstats_update_active_users()
{
    global $DB;

    $periodstart = strtotime('today midnight');
    $periodend = strtotime('tomorrow midnight') - 1;

    $activeuserscount = $DB->count_records_select('user', 'lastaccess >= ?', [$periodstart]);

    $record = new stdClass();
    $record->id = 1;
    $record->activeuserscount = $activeuserscount;
    $record->periodstart = $periodstart;
    $record->periodend = $periodend;
    $record->timecreated = time();

    if ($DB->record_exists('local_quickstats', ['id' => $record->id])) {
        $DB->update_record('local_quickstats', $record);
    } else {
        $DB->insert_record('local_quickstats', $record);
    }
}

/**
 * Inserts mock data into the local_quickstats table for testing.
 */
function local_quickstats_insert_mock_data()
{
    global $DB;

    $ndays = 30; // Number of days for mock data
    for ($i = 0; $i < $ndays; $i++) {
        $periodstart = strtotime("-{$i} days midnight");
        $periodend = strtotime("-{$i} days 23:59:59");
        $activeuserscount = rand(1, 100); // Random count for mock data

        $record = new stdClass();
        $record->activeuserscount = $activeuserscount;
        $record->periodstart = $periodstart;
        $record->periodend = $periodend;
        $record->timecreated = time() - $i * 86400;

        $DB->insert_record('local_quickstats', $record);
    }
}
