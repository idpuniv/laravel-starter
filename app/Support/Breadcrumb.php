<?php

namespace App\Support;

class Breadcrumb
{
    protected static array $crumbs = [];

    public static function add(string $title, ?string $route = null, $params = []): self
    {
        static::$crumbs[] = [
            'title' => $title,
            'url' => $route ? route($route, $params) : null,
        ];
        return new static;
    }

    public static function get(): array
    {
        return static::$crumbs;
    }

    public static function clear(): void
    {
        static::$crumbs = [];
    }
}