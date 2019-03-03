#!/bin/bash

STARTTIME=$(date +%s)
DB_DATABASE=test_lndebate

set -euo pipefail

CHROMEDRIVER_PORT=9515

RUN_TESTS () {
    set -euo pipefail

    # syntax check
    find ./{app,bootstrap,database,config,resources/views,routes,tests}/ -type f -name '*.php' -print0 | xargs -0 -n1 -P4 php -l -n | (! grep -v "No syntax errors detected")

    npm run lint

    if ! lsof -i:$CHROMEDRIVER_PORT | grep LISTEN > /dev/null
    then
        ./vendor/laravel/dusk/bin/chromedriver-linux &
    fi

    php artisan config:clear
    php artisan cache:clear
    php artisan migrate:fresh
    ./vendor/bin/phpunit tests/Unit/ --stop-on-error --stop-on-failure
    ./vendor/bin/phpunit tests/Feature/ --stop-on-error --stop-on-failure
    # php artisan dusk --stop-on-error --stop-on-failure
}

RUN_TESTS || echo 'failed tests!';

ENDTIME=$(date +%s)
ELAPSEDTIME=$(($ENDTIME - $STARTTIME))
echo "time elapsed! $ELAPSEDTIME seconds";

pkill -P $$
