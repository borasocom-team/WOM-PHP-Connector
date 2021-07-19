#! /bin/sh
docker run --rm -v $(pwd)/..:/app:rw php:test composer update
