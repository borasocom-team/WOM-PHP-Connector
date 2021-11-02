#! /bin/sh
docker run --rm -v "$(pwd):/app:ro" -v "$(pwd)/../keys:/keys:ro" php:test php example_instrument.php
