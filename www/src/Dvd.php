<?php

declare(strict_types = 1);

namespace Tamar;

class Dvd extends Product
{
    protected function validateProductSpecificData()
    {
        if (! isset($this->data['size'])) {
            $this->errors[] = "size not provided";
            return;
        }
        if (! is_numeric($this->data['size'])) {
            $this->errors[] = "size invalid";
            return;
        }
        $size = round(floatval($this->data['size']), 2);
        if ($size < 0) {
            $this->errors[] = "size invalid";
            return;
        }
        $this->attribute = 'size: ' . $size . ' MB';
    }
}
