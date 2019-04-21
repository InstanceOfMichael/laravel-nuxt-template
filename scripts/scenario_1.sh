#!/bin/bash

set -euo pipefail
set -x

php artisan migrate:fresh
# php artisan db:seed
php artisan db:seed --class=Scenario1Seeder
