#!/bin/bash
# echo "Starting Composer Script"
# docker run --rm -it -v "$(pwd)":/app composer composer $*
docker-compose run --rm composer $@