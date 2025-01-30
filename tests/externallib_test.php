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
 * Unit tests for the QuickStats plugin's external functions.
 *
 * @package    local_quickstats
 * @copyright  2025 Arsen Anay
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_quickstats\classes;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/webservice/tests/helpers.php');

/**
 * Unit tests for the QuickStats plugin's external functions.
 */
class externallib_test extends \externallib_advanced_testcase {

    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest(true);
        $this->create_schema();
    }

    protected function create_schema() {
        global $DB;

        $dbman = $DB->get_manager();

        // Define table local_quickstats to be created.
        $table = new \xmldb_table('local_quickstats');

        // Adding fields to table local_quickstats.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('activeuserscount', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('periodstart', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('periodend', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table local_quickstats.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_quickstats.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
    }

    /**
     * Test the execute function.
     *
     * @covers \local_quickstats\classes\externallib::get_active_users
     * @runInSeparateProcess
     */
    public function test_get_active_users() {
        global $DB;
        global $CFG;
        require_once($CFG->dirroot . '/local/quickstats/classes/externallib.php');

        // Insert mock data into the local_quickstats table.
        $ndays = 7;
        $mockdata = [];
        for ($i = 0; $i < $ndays; $i++) {
            $periodstart = strtotime("-{$i} days midnight");
            $periodend = strtotime("-{$i} days 23:59:59");
            $activeuserscount = rand(1, 100);

            $record = new \stdClass();
            $record->activeuserscount = $activeuserscount;
            $record->periodstart = $periodstart;
            $record->periodend = $periodend;
            $record->timecreated = time() - $i * 86400;

            $mockdata[] = $record;
            $DB->insert_record('local_quickstats', $record);
        }

        // Call the external function.
        $result = externallib::get_active_users();

        // Execute the return values cleaning process to simulate the web service server.
        $result = \core_external\external_api::clean_returnvalue(
            externallib::get_active_users_returns(),
            $result
        );

        // Check if the result is as expected.
        $this->assertCount($ndays, $result['labels'], 'The number of labels should match the number of days');
        $this->assertCount($ndays, $result['counts'], 'The number of counts should match the number of days');

        // Check each day's active users count.
        foreach ($mockdata as $key => $data) {
            $this->assertEquals($data->activeuserscount, $result['counts'][$key], "The count for day {$key} should match the mock data");
        }
    }
}
