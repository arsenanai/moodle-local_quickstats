<?php

namespace local_quickstats\task;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/quickstats/lib.php');  // Include the lib file to access the function

/**
 * Scheduled task to update active users count.
 *
 * @package   local_quickstats
 * @category  task
 */
class update_active_users extends \core\task\scheduled_task
{

    public function get_name()
    {
        return get_string('updateactiveusers', 'local_quickstats');
    }

    public function execute()
    {
        local_quickstats_update_active_users();
    }
}
