<?php

declare(strict_types=1);

namespace Tamar;

class App
{
    private string $method;
    private DB $db;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $config = new Config();
        $this->db = new DB($config->db);
        header('Access-Control-Allow-Origin: ' . $config->allowedOrigin);
        header('Content-Type: application/json');
    }

    public function run(): void
    {
        if ($this->method === 'GET') {
            $this->list();
        }
        if ($this->method === 'POST') {
            $postData = [];
            foreach ($_POST as $key => $value) {
                $postData[$key] = $value;
            }
            if (isset($postData['delete'])) {
                $this->delete($postData['delete']);
            } else {
                $this->add($postData);
            }
        }
    }

    private function list(): void
    {
        $list = $this->db->products();
        echo json_encode($list);
    }

    private function add(array $postData): void
    {
        $type = $postData['type'] ?? null;
        if ($type !== 'book' && $type !== 'furniture' && $type !== 'dvd') {
            echo json_encode(['error' => "type not allowed"]);
            return;
        }
        $class = '\\Tamar\\' . ucfirst($type);
        $product = new $class($postData, $this->db);
        $saved = $product->save();
        if (!$saved) {
            $errors = $product->getErrors();
            echo json_encode(['error' => $errors]);
            return;
        }
        $this->list();
    }

    private function delete(array $productsToDelete): void
    {
        $deleted = $this->db->delete($productsToDelete);
        if (!$deleted) {
            echo json_encode(['error' => ['error when deleting products']]);
            return;
        }
        $this->list();
    }
}
