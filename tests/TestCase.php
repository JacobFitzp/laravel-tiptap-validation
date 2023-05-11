<?php

namespace JacobFitzp\LaravelTiptapValidation\Tests;

use JacobFitzp\LaravelTiptapValidation\TiptapValidationServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            TiptapValidationServiceProvider::class,
        ];
    }
}
