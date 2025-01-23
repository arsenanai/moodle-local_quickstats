<?php
/**
 * Settings for the QuickStats plugin.
 *
 * @package    local_quickstats
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_quickstats', get_string('pluginname', 'local_quickstats'));

    $settings->add(new admin_setting_configcheckbox(
        'local_quickstats/enableplugin',
        get_string('enableplugin', 'local_quickstats'),
        get_string('enableplugin_desc', 'local_quickstats'),
        1
    ));

    $settings->add(new admin_setting_configtext(
        'local_quickstats/days',
        get_string('days', 'local_quickstats'),
        get_string('days_desc', 'local_quickstats'),
        7,
        PARAM_INT
    ));

    $ADMIN->add('localplugins', $settings);
}
