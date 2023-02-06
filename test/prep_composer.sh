#! /bin/sh
docker run --rm -v $(pwd):/app:rw -v "$(pwd)/..:/pckg:ro" php:test composer update
