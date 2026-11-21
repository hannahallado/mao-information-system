#!/bin/sh
# Walks every sidebar item plus sample detail pages for both roles.
# Needs the database set up and seeded (database/seed_demo.php) because farmer pages read from it.
# Usage: sh tests/check_all_routes.sh
cd "$(dirname "$0")/.."
PATHS=$(php -r '
  define("BASE_PATH", getcwd()); define("APP_PATH", BASE_PATH."/app");
  $GLOBALS["__config"] = [];
  $menu = require "config/menu.php";
  foreach ($menu as $e) { if ($e["type"]==="link") echo $e["path"],"\n"; else foreach ($e["items"] as $i) echo $i[1],"\n"; }
')
EXTRA="/ /ui-kit /farmers/1 /farmers/1/edit /farmers/1/assistance-history /farms/1 /fishery/1 /organizations/1 /monitoring/training/1 /programs/create /programs/1 /programs/1/edit /programs/1/beneficiaries /assistance/create /reports /reports/masterlist /reports/assistance-history /reports/barangay-commodity-summary /system/users/create /reports/nope /does-not-exist"
for role in agriculturist technician; do
  echo "== role: $role =="
  for p in $PATHS $EXTRA; do php tests/route_check.php "$p" "$role"; done
done
