#!/bin/bash

PROJECT="/var/www/daodes"

echo "[AI GRAPH WATCHER] started..."

inotifywait -m -r -e modify,create,delete \
--format '%w%f' "$PROJECT/app" "$PROJECT/routes" "$PROJECT/resources/views" |

while read FILE
do
  echo "[CHANGE DETECTED] $FILE"

  php "$PROJECT/artisan" ai:graph:living

  echo "[GRAPH UPDATED]"
done