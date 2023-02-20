<?php

declare(strict_types = 1);

namespace Tamar;

use PDO;

class DB
{
    private static PDO $pdo;
    
    public function __construct(array $config)
    {
        try {
            static::$pdo = new PDO(
                'mysql:host=' . $config['host'] . ';dbname=' . $config['name'], 
                $config['user'], 
                $config['pass']
            );
            static::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            die('Connectionn Failed : ' . $e->getMessage());
        }
    }

    public static function pdo(): PDO ////////////////////// do I need this???
    {
        return static::$pdo;
    }

    public function products(): array
    {
        $query = 'SELECT * FROM products';
        $stmt = static::$pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function skuIsUnique(string $sku): bool
    {
        $query = 'SELECT id FROM products WHERE sku = "' . $sku . '"';
        $stmt = static::$pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return !isset($result->id);
    }

    public function insert(string $sku, string $name, float $price, string $type, string $attribute): bool
    {
        try {
            $query = 'INSERT INTO products (sku, name, price, type, attribute)
            VALUES (:sku, :name, :price, :type, :attribute)';
            $stmt = static::$pdo->prepare($query);

            $stmt->bindValue(':sku', $sku);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':price', $price);
            $stmt->bindValue(':type', $type);
            $stmt->bindValue(':attribute', $attribute);

            $stmt->execute();
            return true;
        } catch(\PDOException) {
            return false;
        }
    }
}
