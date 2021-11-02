#! /bin/sh
docker run --rm -v $(pwd):/app:rw composer:latest composer update
