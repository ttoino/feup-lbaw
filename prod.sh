#!/bin/bash

docker run --rm -it -p 9000:80 --name=lbaw2265-prod -e DB_HOST='localhost' \
    -e DB_DATABASE='postgres' -e DB_SCHEMA='lbaw2265' \
    -e DB_USERNAME='postgres' -e DB_PASSWORD='pg!password' \
    -e APP_URL='http://localhost:9000' -e ASSET_URL='http://localhost:9000' \
    git.fe.up.pt:5050/lbaw/lbaw2223/lbaw2265
