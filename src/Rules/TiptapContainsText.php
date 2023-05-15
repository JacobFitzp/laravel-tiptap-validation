<?php

namespace JacobFitzp\LaravelTiptapValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use JacobFitzp\LaravelTiptapValidation\Concerns\Creatable;
use JacobFitzp\LaravelTiptapValidation\Helpers\TiptapContentHelper;
use JacobFitzp\LaravelTiptapValidation\Helpers\TiptapTextHelper;

/**
 * Validate Tiptap content contains text.
 *
 * @author Jacob Fitzpatrick <contact@jacobfitzp.me>
 */
class TiptapContainsText implements ValidationRule
{
    use Creatable;

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
        $value = TiptapContentHelper::attemptDecode($value);

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

        // Between minimum and maximum thresholds
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

        // Below minimum threshold
        if (! is_null($this->minimum) && $length < $this->minimum) {
            $fail(trans('tiptap-validation::messages.tiptapContainsText.minimumChars', [
                'min' => $this->minimum,
            ]));

            return;
        }

        // Above maximum threshold
        if (! is_null($this->maximum) && $length > $this->maximum) {
            $fail(trans('tiptap-validation::messages.tiptapContainsText.maximumChars', [
                'max' => $this->maximum,
            ]));
        }
    }
}
