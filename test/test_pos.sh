#! /bin/sh
docker run --rm -v "$(pwd)/..:/app:ro" php:test php example_pos.php
