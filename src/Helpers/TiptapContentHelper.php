<?php

namespace JacobFitzp\LaravelTiptapValidation\Helpers;

class TiptapContentHelper
{
    public static function attemptDecode(mixed $value): ?array
    {
        // Already an array, don't need to do anything
        if (is_array($value)) {
            return $value;
        }

        // Attempt to decode json
        if (is_string($value)) {
            try {
                $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);

                if (is_array($value) && filled($value)) {
                    return $value;
                }
            } catch (\Exception $exception) {
                return null;
            }
        }

        // Unable to decode tiptap content
        return null;
    }
}
