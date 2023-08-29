<?php

namespace JacobFitzp\LaravelTiptapValidation\Contracts;

use Illuminate\Contracts\Validation\ValidationRule;

interface TiptapRule extends ValidationRule
{
    public static function make(): self;
}
