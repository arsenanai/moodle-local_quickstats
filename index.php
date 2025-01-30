<?php
/**
 * Main page for the QuickStats plugin.
 *
 * @package    local_quickstats
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/local/quickstats/lib.php');
require_once($CFG->dirroot . '/local/quickstats/classes/externallib.php');
require_once($CFG->libdir . '/adminlib.php');

require_login();

$context = context_system::instance();
$PAGE->set_url(new moodle_url('/local/quickstats/index.php'));
$PAGE->set_context($context);
$PAGE->set_title('QuickStats');
$PAGE->set_heading('QuickStats Page');
$PAGE->requires->js_call_amd('local_quickstats/chart', 'init');
$PAGE->requires->css('/local/quickstats/styles.css'); // Add CSS if needed

echo $OUTPUT->header();
echo $OUTPUT->heading('QuickStats');
echo '<p><a href="?update=1" class="btn btn-primary">Update Active Users</a></p>';
echo '<canvas id="quickstats-chart" width="380" height="180"></canvas>';
echo $OUTPUT->footer();
