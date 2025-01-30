php /bitnami/moodle/local/quickstats/cli/insert_mock_data.php

php /bitnami/moodle/admin/cli/scheduled_task.php --execute="\local_quickstats\task\update_active_users"

php admin/tool/phpunit/cli/init.php
vendor/bin/phpunit local/quickstats/tests/externallib_test.php
    vendor/bin/phpunit local/quickstats/tests/lib_test.php