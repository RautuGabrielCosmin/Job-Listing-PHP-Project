#!/usr/bin/env bash

set -euo pipefail

APP_REPO="https://github.com/RautuGabrielCosmin/Job-Listing-PHP-Project.git"
APP_DIR="job-listing-php"
MYSQL_DATA="$PWD/mysql-data"
MYSQL_ROOT_PW="${MYSQL_ROOT_PW:-joblist_root_pw}"
MYSQL_DB="job_listings"

# 1. Clone (idempotent)
if [[ ! -d "$APP_DIR/.git" ]]; then
  git clone "$APP_REPO" "$APP_DIR"
fi
cd "$APP_DIR"

docker build -t joblist-php .

docker network create joblist-net >/dev/null 2>&1 || true

docker run -d --name joblist-mysql \
  --network joblist-net \
  -v "$MYSQL_DATA":/var/lib/mysql \
  -e MYSQL_ROOT_PASSWORD="$MYSQL_ROOT_PW" \
  -e MYSQL_DATABASE="$MYSQL_DB" \
  -p 3306:3306 \
  mysql:8.0 --default-authentication-plugin=mysql_native_password

echo "⏳  Waiting for MySQL to accept connections…"
until docker exec joblist-mysql mysql -uroot -p"$MYSQL_ROOT_PW" -e "SELECT 1" &>/dev/null; do
  sleep 2
done
docker exec -i joblist-mysql mysql -uroot -p"$MYSQL_ROOT_PW" "$MYSQL_DB" < job_listings.sql

docker run -d --name joblist-web \
  --network joblist-net \
  -e DB_HOST=joblist-mysql \
  -p 8080:80 \
  joblist-php
