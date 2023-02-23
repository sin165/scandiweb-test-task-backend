<?php

declare(strict_types=1);

namespace Tamar;

abstract class Product
{
    protected string $sku;
    protected string $name;
    protected float $price;
    protected string $type;
    protected string $attribute;
    protected array $errors = [];
    protected DB $db;
    protected array $data;
    
    public function __construct(array $data, DB $db)
    {
        $this->db = $db;
        $this->data = $data;
        $this->type = $data['type'];
    }

    public function save(): bool
    {
        $dataIsValid = $this->validateData();
        if (!$dataIsValid) {
            return false;
        }
        $saved = $this->db->insert($this->sku, $this->name, $this->price, $this->type, $this->attribute);
        if (!$saved) {
            $this->errors[] = "error when saving product";
        }
        return $saved;
    }

    protected function validateData(): bool
    {
        $this->validateSku();
        $this->validateName();
        $this->validatePrice();
        $this->validateProductSpecificData();
        if ($this->errors) {
            return false;
        } else {
            return true;
        }
    }

    protected function validateSku(): void
    {
        $this->sku = $this->testInput($this->data['sku']);
        if (!$this->sku) {
            $this->errors[] = "sku not provided";
            return;
        }
        if (!$this->db->skuIsUnique($this->sku)) {
            $this->errors[] = "sku not unique";
        }
    }

    protected function validateName(): void
    {
        $this->name = $this->testInput($this->data['name']);
        if (!$this->name) {
            $this->errors[] = "name not provided";
            return;
        }
    }

    protected function validatePrice(): void
    {
        if (!isset($this->data['price'])) {
            $this->errors[] = "price not provided";
            return;
        }
        if (!is_numeric($this->data['price'])) {
            $this->errors[] = "price invalid";
            return;
        }
        $this->price = round(floatval($this->data['price']), 2);
        if ($this->price < 0) {
            $this->errors[] = "price invalid";
        }
    }

    abstract protected function validateProductSpecificData(): void;

    public function testInput(string $data): string
    {
        $data = strip_tags($data);
        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = trim($data);
        return $data;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
