#! /bin/sh
docker run --rm -v "$(pwd):/app:ro" -v "$(pwd)/../keys:/keys:ro" -v "$(pwd)/../src:/app/vendor/digitsrl/php-wom-connector/src:ro" php:test php example_instrument.php
