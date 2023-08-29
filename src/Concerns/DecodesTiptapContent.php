<?php

namespace JacobFitzp\LaravelTiptapValidation\Concerns;

use JacobFitzp\LaravelTiptapValidation\Helpers\TiptapContentHelper;

/**
 * Provides methods for working with raw tiptap content.
 *
 * @author Jacob Fitzpatrick <jacob@codefy.co.uk>
 */
trait DecodesTiptapContent
{
    /**
     * @see TiptapContentHelper::decode()
     */
    protected function decode(mixed $value): ?array
    {
        return TiptapContentHelper::decode($value);
    }
}
