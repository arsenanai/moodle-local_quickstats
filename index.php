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
 * Main page for the QuickStats plugin.
 *
 * @package    local_quickstats
 * @copyright  2025 Arsen Anay
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
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
$PAGE->requires->css('/local/quickstats/styles.css');

echo $OUTPUT->header();
echo $OUTPUT->heading('QuickStats');
echo '<p><a href="?update=1" class="btn btn-primary">Update Active Users</a></p>';
echo '<canvas id="quickstats-chart" width="380" height="180"></canvas>';
echo $OUTPUT->footer();
