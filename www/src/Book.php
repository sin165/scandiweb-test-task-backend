<?php

declare(strict_types = 1);

namespace Tamar;

class Book extends Product
{
    protected function validateProductSpecificData()
    {
        if (! isset($this->data['weight'])) {
            $this->errors[] = "weight not provided";
            return;
        }
        if (! is_numeric($this->data['weight'])) {
            $this->errors[] = "weight invalid";
            return;
        }
        $weight = round(floatval($this->data['weight']), 3);
        if ($weight < 0) {
            $this->errors[] = "weight invalid";
            return;
        }
        $this->attribute = 'Weight: ' . $weight . 'KG';
    }
}
