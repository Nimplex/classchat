#!/usr/bin/env bash

if [ -z `docker ps -a -q -f name=classchat-db` ]; then
  dir=$(dirname "$0")
  if [ ! -f "$dir/Dockerfile" ]; then
    echo "Dockerfile not found" >&2;
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
else
  docker start classchat-db
fi

