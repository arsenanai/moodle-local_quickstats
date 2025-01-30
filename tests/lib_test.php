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
 * Unit tests for the QuickStats plugin.
 *
 * @package    local_quickstats
 * @copyright  2025 Arsen Anay
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/local/quickstats/lib.php');

/**
 * Unit tests for the QuickStats plugin.
 */
class local_quickstats_lib_test extends advanced_testcase {

    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest(true); // Ensure each test runs in a clean state.
        $this->create_schema();
    }

    protected function create_schema() {
        global $DB;

        $dbman = $DB->get_manager();

        // Define table local_quickstats to be created.
        $table = new xmldb_table('local_quickstats');

        // Adding fields to table local_quickstats.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('activeuserscount', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('periodstart', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('periodend', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table local_quickstats.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for local_quickstats.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
    }

    /**
     * Test the update_active_users function.
     */
    public function test_update_active_users() {
        global $DB;

        // Start a transaction to isolate the test.
        $transaction = $DB->start_delegated_transaction();

        // Create test users with last access time.
        $user1 = $this->getDataGenerator()->create_user(['lastaccess' => strtotime('today midnight') + 100]);
        $user2 = $this->getDataGenerator()->create_user(['lastaccess' => strtotime('today midnight') + 200]);

        // Ensure the users are created.
        $this->assertNotEmpty($user1);
        $this->assertNotEmpty($user2);

        // Run the update function.
        local_quickstats_update_active_users();

        // Fetch the most recent record from the database.
        $record = $DB->get_record_sql('SELECT * FROM {local_quickstats} ORDER BY timecreated DESC LIMIT 1');

        // Check if the active users count is updated.
        $this->assertNotEmpty($record, 'The record should not be empty');
        $this->assertEquals(2, $record->activeuserscount, 'The active users count should be 2');
        $this->assertEquals(strtotime('today midnight'), $record->periodstart, 'The period start should be today midnight');
        $this->assertEquals(strtotime('tomorrow midnight') - 1, $record->periodend, 'The period end should be tomorrow midnight - 1 second');

        // Commit the transaction to ensure data is written.
        $transaction->allow_commit();
    }
}
