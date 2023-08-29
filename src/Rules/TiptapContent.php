<?php

namespace JacobFitzp\LaravelTiptapValidation\Rules;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rules\NotIn;
use JacobFitzp\LaravelTiptapValidation\Concerns\Creatable;
use JacobFitzp\LaravelTiptapValidation\Concerns\DecodesTiptapContent;
use JacobFitzp\LaravelTiptapValidation\Contracts\TiptapRule;
use JacobFitzp\LaravelTiptapValidation\Enums\TiptapValidationRuleMode;

/**
 * Tiptap content validation rule.
 *
 * Validates that tiptap content is in the correct format and only
 * contains nodes and marks that are allowed.
 *
 * @author Jacob Fitzpatrick <contact@jacobfitzp.me>
 */
class TiptapContent implements TiptapRule
{
    use Creatable;
    use DecodesTiptapContent;

    /**
     * Validation failed.
     */
    protected bool $fails = false;

    /**
     * List of allowed / disallowed node types.
     *
     * @var string[]
     */
    protected array $nodes = [];

    /**
     * List of allowed / disallowed mark types.
     *
     * @var string[]
     */
    protected array $marks = [];

    /**
     * Mode used for validation, blacklist, or whitelist.
     */
    protected TiptapValidationRuleMode $mode = TiptapValidationRuleMode::BLACKLIST;

    /**
     * Nodes to blacklist / whitelist
     *
     * @param  string  ...$nodes
     */
    public function nodes(...$nodes): static
    {
        $this->nodes = $nodes;

        return $this;
    }

    /**
     * Marks to blacklist / whitelist
     *
     * @param  string  ...$marks
     */
    public function marks(...$marks): static
    {
        $this->marks = $marks;

        return $this;
    }

    /**
     * Enable whitelisting mode
     */
    public function whitelist(): static
    {
        $this->mode = TiptapValidationRuleMode::WHITELIST;

        return $this;
    }

    /**
     * Enable blacklisting mode
     */
    public function blacklist(): static
    {
        $this->mode = TiptapValidationRuleMode::BLACKLIST;

        return $this;
    }

    /**
     * Validate Tiptap content.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip empty values
        if (blank($value)) {
            return;
        }

        // Attempt to decode content
        $value = $this->decode($value);

        // Unable to decode content, invalid format
        if (is_null($value)) {
            $fail(trans('tiptap-validation::messages.tiptapContent'));

            return;
        }

        // Validate root tiptap content.
        $validator = Validator::make($value, [
            'type' => 'required|string',
            'content' => 'array',
        ]);

        if ($validator->fails()) {
            $fail(trans('tiptap-validation::messages.tiptapContent'));

            return;
        }

        // Begin content validation
        if (
            filled(array_get($value, 'content')) &&
            ! $this->validateNodes(array_get($value, 'content'))
        ) {
            $fail(trans('tiptap-validation::messages.tiptapContent'));
        }
    }

    /**
     * Validate that a set of nodes are formatted correctly
     * and of allowed types.
     */
    protected function validateNodes(array $nodes): bool
    {
        // Validate node types
        $validator = Validator::make($nodes, [
            '*.type' => ['required', 'string', $this->typeValidationRule($this->nodes)],
            '*.marks.*.type' => ['required', 'string',  $this->typeValidationRule($this->marks)],
            '*' => new TiptapNode(),
        ]);

        if ($validator->fails()) {
            return false;
        }

        $passes = true;

        // Validate nested nodes recursively.
        collect($nodes)
            ->each(function (array $node) use (&$passes) {
                if (
                    filled(array_get($node, 'content')) &&
                    ! $this->validateNodes(array_get($node, 'content'))
                ) {
                    return $passes = false;
                }

                return true;
            });

        return $passes;
    }

    /**
     * Get validation rule based on the validation mode used.
     *
     * Either whitelist (In), or blacklist (NotIn)
     */
    protected function typeValidationRule(array $types): NotIn|In
    {
        return match ($this->mode) {
            TiptapValidationRuleMode::WHITELIST => Rule::in($types),
            default => Rule::notIn($types),
        };
    }
}
