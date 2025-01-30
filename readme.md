QuickStats Plugin
=================

CLI Commands
------------

*   **Insert Mock Data**  
    `php /bitnami/moodle/local/quickstats/cli/insert_mock_data.php`
*   **Execute Scheduled Task**  
    `php /bitnami/moodle/admin/cli/scheduled_task.php --execute="\local_quickstats\task\update_active_users"`
*   **Initialize PHPUnit**  
    `php admin/tool/phpunit/cli/init.php`
*   **Run PHPUnit Tests for External Library**  
    `vendor/bin/phpunit local/quickstats/tests/externallib_test.php`
*   **Run PHPUnit Tests for Library**  
    `vendor/bin/phpunit local/quickstats/tests/lib_test.php`