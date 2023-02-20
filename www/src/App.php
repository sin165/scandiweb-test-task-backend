<?php

declare(strict_types = 1);

namespace Tamar;

class App
{
    private string $method;
    private DB $db;/////////////////////// es productshi xo ar gadavitano. mgoni egre jobia, ki.

    public function __construct(protected Config $config, protected array $request)
    {
        $this->method = $request['method'];
        $this->db = new DB($config->db);
        header('Access-Control-Allow-Origin: http://localhost:3000');
        header('Content-Type: application/json');
    }

    public function run()
    {
        if($this->method === 'GET') {
            $this->list();
        }
        if($this->method === 'POST') {
            $postData = [];
            foreach ($_POST as $key => $value) {
                $postData[$key] = $value;
            }
            $this->add($postData);
        }
    }

    private function list()
    {
        $list = $this->db->products();
        echo json_encode($list);
    }

    private function add(array $postData)
    {
        $type = $postData['type'];
        if ($type !== 'book' && $type !== 'furniture' && $type !== 'dvd') {
            echo json_encode(['error' => "type not allowed"]);
            return;
        }
        $class = ucfirst($type);
        $product = new $class($postData, $this->db);
        $saved = $product->save();
        if (!$saved) {
            $errors = $product->getErrors();
            echo json_encode(['error' => $errors]);
            return;
        }
        
        $this->list();
    }
}
