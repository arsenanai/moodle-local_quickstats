<?php
/**
 * CLI script to insert mock data into the QuickStats plugin.
 *
 * @package    local_quickstats
 */

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');
require_once($CFG->dirroot . '/local/quickstats/lib.php');

local_quickstats_insert_mock_data();

echo "Mock data inserted successfully.\n";
