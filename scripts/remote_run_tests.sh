#!/bin/bash

STARTTIME=$(date +%s)
APP_ENV=testing
DB_DATABASE=test_lndebate
DB_USERNAME=test_lndebate
DB_PASSWORD=test_lndebate
APP_DEBUG=true
APP_LOG_LEVEL=debug

set -euo pipefail
    set -x

# rsync -rt --delete --delete-during .

SERVER="forge@192.168.0.133"

SRC="."
DST="$SERVER:/home/forge/code/lndebate"
rsync -ah --delete \
    --exclude=.env \
    --exclude=node_modules \
    --include .git \
    --exclude-from="$(git -C $SRC ls-files --exclude-standard -oi --directory >.git/ignores.tmp && echo .git/ignores.tmp)" \
    $SRC $DST

ssh $SERVER bash <<EOF

    set -euo pipefail
    set -x
    cd ~/code/lndebate

    psql -V
    node -v
    php -v
    yarn install --no-progress --pure-lockfile
    composer install

    sudo -u postgres /usr/bin/psql -c "SELECT 1 FROM pg_user WHERE usename = '$DB_USERNAME'" | grep -q 1 || sudo -u postgres /usr/bin/psql -c "CREATE ROLE $DB_USERNAME LOGIN PASSWORD '$DB_PASSWORD';"
    sudo -u postgres /usr/bin/dropdb --if-exists --echo $DB_DATABASE;
    sudo -u postgres /usr/bin/createdb --echo --owner=$DB_USERNAME $DB_DATABASE;

    ./scripts/run_tests.sh
EOF

ENDTIME=$(date +%s)
ELAPSEDTIME=$(($ENDTIME - $STARTTIME))
echo "time elapsed! $ELAPSEDTIME seconds";

rsync -r \
    $DST/tests/Browser/screenshots/*.png \
    $SRC/tests/Browser/remote-screenshots \

# visudo
# forge ALL = NOPASSWD: /usr/bin/psql /usr/bin/dropdb /usr/bin/createdb /bin/grep
