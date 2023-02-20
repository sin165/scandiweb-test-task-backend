<?php
declare(strict_types = 1);
namespace Tamar;

class Book extends Product
{
    protected function validateProductSpecificData()
    {
        if (! $this->data['weight']) {
            $this->errors[] = "weight not provided";
            return;
        }
        $weight = round(floatval($this->data['weight']), 2);
        if (! $weight > 0) {
            $this->errors[] = "weight invalid";
            return;
        }
        $this->attribute = 'Weight: ' . $weight . 'KG';
    }
}
