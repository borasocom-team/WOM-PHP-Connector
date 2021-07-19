<?php
namespace WOM;

class Filter implements \JsonSerializable {
    public $aim;
    public $leftTop;
    public $rightBottom;
    public $maxAge;

    private function __construct($aim = null, $leftTop = null, $rightBottom = null, $maxAge = null) {
        $this->aim = $aim;
        $this->leftTop = $leftTop;
        $this->rightBottom = $rightBottom;
        $this->maxAge = ($maxAge == null) ? 0 : $maxAge;
    }

    public static function Create($aim = null, $leftTop = null, $rightBottom = null, $maxAge = null) {
        return new Filter($aim, $leftTop, $rightBottom, $maxAge);
    }

    public function jsonSerialize() {
        // HACK: needed to generate an associative array when all parameters are null
        $obj = array("dum" => "my");

        if ($this->aim != null && !empty($this->aim)) {
            $obj['aim'] = $this->aim;
        }

        if ($this->maxAge > 0) {
            $obj['maxAge'] = $this->maxAge;
        }

        if ($this->leftTop != null && is_array($this->leftTop) && count($this->leftTop) == 2 &&
            $this->rightBottom != null && is_array($this->rightBottom) && count($this->rightBottom) == 2) {
            $obj['bounds'] = array (
                'leftTop' => $this->leftTop,
                'rightBottom' => $this->rightBottom
            );
        }

        return $obj;
    }

}
