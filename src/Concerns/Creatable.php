<?php

namespace JacobFitzp\LaravelTiptapValidation\Concerns;

/**
 * Provides a static function for instantiating a class ::make().
 *
 * @author Jacob Fitzpatrick <jacob@codefy.co.uk>
 */
trait Creatable
{
    public static function make(): self
    {
        return new self();
    }
}
