<?php
/**
 * Unit tests for the QuickStats plugin.
 *
 * @package    local_quickstats
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot . '/local/quickstats/lib.php');

class local_quickstats_lib_test extends advanced_testcase
{

    protected function setUp(): void
    {
        $this->resetAfterTest(true); // Ensure each test runs in a clean state.
        $this->create_schema();
    }

    protected function create_schema()
    {
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

    public function test_update_active_users()
    {
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
