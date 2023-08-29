<?php

namespace JacobFitzp\LaravelTiptapValidation\Helpers;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Helper class for working with raw tiptap content.
 *
 * @author Jacob Fitzpatrick <jacob@codefy.co.uk>
 */
class TiptapContentHelper
{
    /**
     * Attempt to decode tiptap content to an array that we
     * can apply validation rules to.
     *
     * This does not guarantee that the content is in a
     * valid format, simply parses it into a type that
     * we can work with further down the line.
     */
    public static function decode(mixed $value): ?array
    {
        // Content is already an array, so we don't need to do anything.
        if (is_array($value)) {
            return $value;
        }

        // Content is an arrayable object, we can convert it as it.
        if ($value instanceof Arrayable) {
            return $value->toArray();
        }

        // Catch any exceptions and treat them as blank content.
        try {
            // Cast to string and attempt to json decode
            return (array) json_decode($value, true, 512, JSON_THROW_ON_ERROR);

        } catch (\Exception $exception) {
            return null;
        }
    }
}
