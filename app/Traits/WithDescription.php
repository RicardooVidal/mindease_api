<?php

namespace App\Traits;

use App\Attributes\Description;
use ReflectionClassConstant;

trait WithDescription
{
    public function description(): string
    {
        $reflection = new ReflectionClassConstant(self::class, $this->name);
        $attributes = $reflection->getAttributes(Description::class);

        return $attributes[0]->newInstance()->description;
    }
}
