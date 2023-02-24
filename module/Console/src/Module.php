<?php

namespace Console;

final class Module
{
    /**
     * @return array
     */
    public function getConfig(): array
    {
        return require __DIR__ . '/../config/module.config.php';
    }
}