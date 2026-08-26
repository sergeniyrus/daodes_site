#!/bin/bash

PATCH_FILE="/var/www/daodes/ai_last_patch.txt"

while true
do
  if [ -f "$PATCH_FILE" ]; then
    echo "Patch detected, applying..."

    php /var/www/daodes/ai/engine/run_apply.php "$PATCH_FILE"

    rm -f "$PATCH_FILE"
  fi

  sleep 2
done