#!/bin/bash

STARTTIME=$(date +%s)
APP_ENV=testing
DB_CONNECTION=test_pgsql
DB_DATABASE=test_lndebate
DB_USERNAME=test_lndebate
DB_PASSWORD=test_lndebate
APP_DEBUG=true
APP_LOG_LEVEL=debug

set -euo pipefail

CHROMEDRIVER_PORT=9515
# NPM_RUN_DEV_PORT=3000

DEL_CONFIG_CACHE () {
    php artisan config:clear
    php artisan cache:clear
    composer dump-autoload
    [ -f boostrap/cache/config.php ] && rm boostrap/cache/config.php;
    return 0;
}

RUN_TESTS () {
    set -euo pipefail

    # syntax check
    find ./{app,bootstrap,database,config,resources/views,routes,tests}/ -type f -name '*.php' -print0 | xargs -0 -n1 -P4 php -l -n | (! grep -v "No syntax errors detected")

    npm run lint

    # if ! lsof -i:$NPM_RUN_DEV_PORT | grep LISTEN > /dev/null
    # then
    #     npm run dev &
    # fi
    if ! lsof -i:$CHROMEDRIVER_PORT | grep LISTEN > /dev/null
    then
        ./vendor/laravel/dusk/bin/chromedriver-linux &
    fi

    DEL_CONFIG_CACHE;
    php artisan migrate:fresh --seed

    DEL_CONFIG_CACHE;
    ./vendor/bin/phpunit tests/Unit/
    # ./vendor/bin/phpunit tests/Unit/ --stop-on-error --stop-on-failure

    DEL_CONFIG_CACHE;
    ./vendor/bin/phpunit tests/Feature/
    # ./vendor/bin/phpunit tests/Feature/ --stop-on-error --stop-on-failure

    DEL_CONFIG_CACHE;
    php artisan dusk
    # php artisan dusk --stop-on-error --stop-on-failure
}

RUN_TESTS || echo 'failed tests!';

ENDTIME=$(date +%s)
ELAPSEDTIME=$(($ENDTIME - $STARTTIME))
echo "time elapsed! $ELAPSEDTIME seconds";

pkill -P $$
