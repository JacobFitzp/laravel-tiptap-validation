<?php

namespace JacobFitzp\LaravelTiptapValidation\Rules;

use Closure;
use JacobFitzp\LaravelTiptapValidation\Concerns\Creatable;
use JacobFitzp\LaravelTiptapValidation\Concerns\DecodesTiptapContent;
use JacobFitzp\LaravelTiptapValidation\Contracts\TiptapRule;
use JacobFitzp\LaravelTiptapValidation\Helpers\TiptapTextHelper;

/**
 * Tiptap contains text validation rule.
 *
 * Validates that tiptap content contains text, and is within an
 * option character count range.
 *
 * @author Jacob Fitzpatrick <contact@jacobfitzp.me>
 */
class TiptapContainsText implements TiptapRule
{
    use Creatable;
    use DecodesTiptapContent;

    protected ?int $minimum = null;

    protected ?int $maximum = null;

    /**
     * Minimum number of characters required.
     *
     * @return $this
     */
    public function minimum(int $min): TiptapContainsText
    {
        $this->minimum = $min;

        return $this;
    }

    /**
     * Maximum number of characters required.
     *
     * @return $this
     */
    public function maximum(int $max): TiptapContainsText
    {
        $this->maximum = $max;

        return $this;
    }

    /**
     * Number of characters required.
     *
     * @return $this
     */
    public function between(int $min, int $max): TiptapContainsText
    {
        return $this
            ->minimum($min)
            ->maximum($max);
    }

    /**
     * Validate content.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = $this->decode($value);

        // Invalid content
        if (! is_array($value) || blank($value)) {
            $fail(trans('tiptap-validation::messages.tiptapContainsText.noText'));

            return;
        }

        // Count text characters in content
        $length = TiptapTextHelper::countCharacters(array_get($value, 'content', []));

        // No text found
        if ($length === 0) {
            $fail(trans('tiptap-validation::messages.tiptapContainsText.noText'));

            return;
        }

        // Out-of-range of character thresholds
        if (
            ! is_null($this->minimum) &&
            ! is_null($this->maximum) && (
                $length < $this->minimum ||
                $length > $this->maximum
            )
        ) {
            $fail(trans('tiptap-validation::messages.tiptapContainsText.betweenChars', [
                'min' => $this->minimum,
                'max' => $this->maximum,
            ]));

            return;
        }

        // Below minimum character count threshold
        if (! is_null($this->minimum) && $length < $this->minimum) {
            $fail(trans('tiptap-validation::messages.tiptapContainsText.minimumChars', [
                'min' => $this->minimum,
            ]));

            return;
        }

        // Above maximum character count threshold
        if (! is_null($this->maximum) && $length > $this->maximum) {
            $fail(trans('tiptap-validation::messages.tiptapContainsText.maximumChars', [
                'max' => $this->maximum,
            ]));
        }
    }
}
