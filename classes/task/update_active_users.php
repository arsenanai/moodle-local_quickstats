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
 * Scheduled task to update active users count.
 *
 * @package   local_quickstats
 * @category  task
 * @copyright 2025 Arsen Anay
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_quickstats\task;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/quickstats/lib.php');

/**
 * Class update_active_users
 *
 * Scheduled task to update active users count.
 */
class update_active_users extends \core\task\scheduled_task {

    /**
     * Returns the name of the scheduled task.
     *
     * @return string Task name.
     */
    public function get_name() {
        return get_string('updateactiveusers', 'local_quickstats');
    }

    /**
     * Executes the scheduled task.
     */
    public function execute() {
        local_quickstats_update_active_users();
    }
}
