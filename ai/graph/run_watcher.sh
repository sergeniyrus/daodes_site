#!/bin/bash

PROJECT="/var/www/daodes"

echo "[DAODES GRAPH WATCHER STARTED]"

while true
do
    inotifywait -r -e modify,create,delete \
    "$PROJECT/app" \
    "$PROJECT/routes" \
    "$PROJECT/resources/views" \
    --timeout 10

    if [ $? -eq 0 ]; then
        echo "[CHANGE DETECTED] rebuilding graph..."

        php "$PROJECT/artisan" ai:graph:living

        echo "[GRAPH UPDATED]"
    fi
done