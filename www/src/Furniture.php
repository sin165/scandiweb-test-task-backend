<?php

declare(strict_types = 1);

namespace Tamar;

class Furniture extends Product
{
    protected function validateProductSpecificData()
    {
        if (! isset($this->data['height']) || ! isset($this->data['width']) || ! isset($this->data['length'])) {
            $this->errors[] = "dimensions not provided";
            return;
        }
        if (! is_numeric($this->data['height']) || ! is_numeric($this->data['width']) || ! is_numeric($this->data['length'])) {
            $this->errors[] = "dimensions invalid";
            return;
        }
        $height = round(floatval($this->data['height']));
        $width = round(floatval($this->data['width']));
        $length = round(floatval($this->data['length']));
        if ($height < 0 || $width < 0 || $length < 0) {
            $this->errors[] = "dimensions invalid";
            return;
        }
        $this->attribute = 'Dimension: ' . $height . 'x' . $width . 'x' . $length;
    }
}
