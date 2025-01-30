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
 * Library of functions for the QuickStats plugin.
 *
 * @package    local_quickstats
 * @copyright  2025 Arsen Anay
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Updates the active users count.
 */
function local_quickstats_update_active_users() {
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
function local_quickstats_insert_mock_data() {
    global $DB;

    $ndays = 30;
    for ($i = 0; $i < $ndays; $i++) {
        $periodstart = strtotime("-{$i} days midnight");
        $periodend = strtotime("-{$i} days 23:59:59");
        $activeuserscount = rand(1, 100);

        $record = new stdClass();
        $record->activeuserscount = $activeuserscount;
        $record->periodstart = $periodstart;
        $record->periodend = $periodend;
        $record->timecreated = time() - $i * 86400;

        $DB->insert_record('local_quickstats', $record);
    }
}
