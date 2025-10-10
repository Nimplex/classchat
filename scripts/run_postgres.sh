#!/usr/bin/env bash

if ! systemctl is-active docker > /dev/null; then
  echo "'docker.service' isn't running"
  exit 2;
fi

if [ -z $(docker ps -a -q -f name=classchat-db) ]; then
  dir=$(dirname "$0")
  if [ ! -f "$dir/Dockerfile" ]; then
    echo "'Dockerfile' not found" >&2;
    exit 1;
  fi
  docker build "$dir" -t classchat-db
  docker run -d \
    --name classchat-db \
    -e POSTGRES_DB=classchat \
    -e POSTGRES_USER=admin \
    -e POSTGRES_PASSWORD=1234 \
    -p 5432:5432 \
    --mount type=volume,src=classchat-data,dst=/var/lib/postgresql/18/docker \
    classchat-db
elif [ -z $(docker ps -q -f name=classchat-db) ]; then
  docker start classchat-db
else
  echo 'container already running'
fi

