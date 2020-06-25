<?php


namespace WOM;


class Filter implements \JsonSerializable
{

    public $aim;
    public $leftTop;
    public $rightBottom;
    public $maxAge;

    private function __construct(string $aim, array $leftTop, array $rightBottom, int $maxAge)
    {
        $this->aim = $aim;
        $this->leftTop = $leftTop;
        $this->rightBottom = $rightBottom;
        $this->maxAge = $maxAge;
    }

    public static function Create(string $aim, array $leftTop, array $rightBottom, int $maxAge){
        return new Filter($aim, $leftTop, $rightBottom, $maxAge);
    }

    public function jsonSerialize()
    {
        return array(
            'Aim' => $this->aim,
            'MaxAge' => $this->maxAge,
            'Bounds' => array (
                'LeftTop' => $this->leftTop,
                'RightBottom' => $this->rightBottom
            )
         );
    }
}