#!/bin/bash

STARTTIME=$(date +%s)
DB_DATABASE=test_lndebate

set -euo pipefail

rsync -rt --delete --delete-during . forge@192.168.0.133:/home/forge/code/lndebate
