<?php

namespace JacobFitzp\LaravelTiptapValidation\Helpers;

/**
 * Helper for working with text contained inside
 * tiptap content.
 *
 * @author Jacob Fitzpatrick <jacob@codefy.co.uk>
 */
class TiptapTextHelper
{
    /**
     * Count the number of characters in tiptap content.
     *
     * @param  array  $nodes The nodes to search for text within.
     * @return int The number of characters found within the provided nodes.
     */
    public static function countCharacters(array $nodes): int
    {
        $characters = 0;
        $textProperties = config('tiptap-validation.textProperties');

        foreach ($nodes as $node) {
            foreach ($textProperties as $textProperty) {
                $characters += (
                    strlen(array_get($node, $textProperty))
                    - substr_count(array_get($node, $textProperty), ' ')
                );
            }

            foreach (array_get($node, 'marks', []) as $mark) {
                foreach ($textProperties as $textProperty) {
                    $characters += (
                        strlen(array_get($mark, $textProperty))
                        - substr_count(array_get($mark, $textProperty), ' ')
                    );
                }
            }

            // Nested content
            if (filled(array_get($node, 'content'))) {
                $characters += self::countCharacters(array_get($node, 'content'));
            }
        }

        return $characters;
    }
}
