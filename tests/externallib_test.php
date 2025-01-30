<?php
/**
 * Unit tests for the QuickStats plugin's external functions.
 *
 * @package    local_quickstats
 */

namespace local_quickstats\classes;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/webservice/tests/helpers.php');

class externallib_test extends \externallib_advanced_testcase
{

    protected function setUp(): void
    {
        $this->resetAfterTest(true);
        $this->create_schema();
    }

    protected function create_schema()
    {
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
    public function test_get_active_users()
    {
        global $DB;
        global $CFG;
        require_once($CFG->dirroot . '/local/quickstats/classes/externallib.php');

        // Insert mock data into the local_quickstats table.
        $ndays = 7;
        for ($i = 0; $i < $ndays; $i++) {
            $periodstart = strtotime("-{$i} days midnight");
            $periodend = strtotime("-{$i} days 23:59:59");
            $activeuserscount = rand(1, 100);

            $record = new \stdClass();
            $record->activeuserscount = $activeuserscount;
            $record->periodstart = $periodstart;
            $record->periodend = $periodend;
            $record->timecreated = time() - $i * 86400;

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
    }
}
