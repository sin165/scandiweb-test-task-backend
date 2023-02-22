<?php

declare(strict_types = 1);

namespace Tamar;

class Config
{
    protected array $config = [
        'db' => [
            'host' => 'db',
            'user' => 'user',
            'pass' => 'test',
            'name' => 'myDb',
        ],
    ];

    public function __get(string $name)
    {
        return $this->config[$name] ?? null;
    }
}
