<?php
namespace WOM;

class Voucher implements \JsonSerializable {
    public $aim;
    public $latitude;
    public $longitude;
    public $datetime;
    public $count;

    private function __construct($aim, $latitude, $longitude, $datetime, $count) {
        if(!is_string($aim)) {
            throw new InvalidArgumentException('Aim must be a string');
        }
        if(!is_float($latitude) || !is_float($longitude)) {
            throw new InvalidArgumentException('Latitude ang longitude must be floats');
        }
        if(!is_integer($count)) {
            throw new InvalidArgumentException('Count must be an integer');
        }

        $this->aim = $aim;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->datetime = $datetime;
        $this->count = $count;
    }

    public static function Create($aim, $latitude, $longitude, $datetime, $count = NULL) {
        if(!$count) {
            $count = 1;
        }

        return new Voucher($aim, $latitude, $longitude, $datetime, $count);
    }

    public function jsonSerialize() {
        return array(
            'aim' => $this->aim,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'timestamp' => $this->datetime->format(DATE_ATOM),
            'count' => $this->count
        );
    }

}
