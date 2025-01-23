<?php
/**
 * Main page for the QuickStats plugin.
 *
 * @package    local_quickstats
 * @copyright  2025 Arsen Anay
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_login();
$context = context_system::instance();
$PAGE->set_url(new moodle_url('/local/quickstats/index.php'));
$PAGE->set_context($context);
$PAGE->set_title('Hello World');
$PAGE->set_heading('Hello World Page');

echo $OUTPUT->header();
echo $OUTPUT->heading('Hello, World!');
echo $OUTPUT->footer();