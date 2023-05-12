<?php

namespace JacobFitzp\LaravelTiptapValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use JacobFitzp\LaravelTiptapValidation\Concerns\Creatable;
use JacobFitzp\LaravelTiptapValidation\Enums\TiptapValidationRuleMode;
use JacobFitzp\LaravelTiptapValidation\Helpers\TiptapContentHelper;

/**
 * Tiptap content validation rule
 *
 * @author Jacob Fitzpatrick <contact@jacobfitzp.me>
 */
class TiptapContent implements ValidationRule
{
    use Creatable;

    /**
     * Validation failed.
     */
    protected bool $fails = false;

    /**
     * List of allowed / disallowed node types.
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
     * @param  mixed  ...$nodes
     */
    public function nodes(...$nodes): TiptapContent
    {
        $this->nodes = $nodes;

        return $this;
    }

    /**
     * Marks to blacklist / whitelist
     *
     * @param  mixed  ...$marks
     */
    public function marks(...$marks): TiptapContent
    {
        $this->marks = $marks;

        return $this;
    }

    /**
     * Enable whitelisting mode
     */
    public function whitelist(): TiptapContent
    {
        $this->mode = TiptapValidationRuleMode::WHITELIST;

        return $this;
    }

    /**
     * Enable blacklisting mode
     */
    public function blacklist(): TiptapContent
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
        if (empty($value)) {
            return;
        }

        // Attempt to decode content
        $value = TiptapContentHelper::attemptDecode($value);

        // Unable to decode content, invalid format
        if (is_null($value)) {
            $fail(trans('tiptap-validation::messages.tiptapContent'));

            return;
        }

        // Validate root tiptap content.
        $validator = Validator::make($value, ['type' => 'required|string', 'content' => 'array']);

        if ($validator->fails()) {
            $fail(trans('tiptap-validation::messages.tiptapContent'));

            return;
        }

        // Begin content validation
        if (
            ! empty($value['content']) &&
            ! $this->validateNodes($value['content'])
        ) {
            $fail(trans('tiptap-validation::messages.tiptapContent'));
        }
    }

    /**
     * Recursively validate nodes.
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

        // Validate nested content
        collect($nodes)
            ->each(function (array $node) use (&$passes) {
                if (
                    ! empty($node['content']) &&
                    ! $this->validateNodes($node['content'])
                ) {
                    $passes = false;
                    return false;
                }
            });

        return $passes;
    }

    /**
     * Type validation rule
     */
    protected function typeValidationRule(array $types): mixed
    {
        return match ($this->mode) {
            TiptapValidationRuleMode::WHITELIST => Rule::in($types),
            default => Rule::notIn($types),
        };
    }
}
