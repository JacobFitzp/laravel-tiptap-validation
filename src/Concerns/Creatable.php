<?php

namespace JacobFitzp\LaravelTiptapValidation\Concerns;

trait Creatable
{
    public static function create(): self
    {
        return new self();
    }
}
