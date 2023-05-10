<?php

namespace JacobFitzp\LaravelTiptapValidation\Concerns;

use JacobFitzp\LaravelTiptapValidation\Enums\TiptapValidationRuleMode;
use JacobFitzp\LaravelTiptapValidation\Rules\TiptapValidationRule;

trait Configurable
{
    protected array $nodes = [];

    /**
     * List of allowed / disallowed mark types
     *
     * @var string[]
     */
    protected array $marks = [];

    /**
     * Mode used for validation, blacklist, or whitelist
     */
    protected TiptapValidationRuleMode $mode = TiptapValidationRuleMode::BLACKLIST;

    /**
     * Nodes to blacklist / whitelist
     *
     * @param  mixed  ...$nodes
     */
    public function nodes(...$nodes): TiptapValidationRule
    {
        $this->nodes = $nodes;

        return $this;
    }

    /**
     * Marks to blacklist / whitelist
     *
     * @param  mixed  ...$marks
     */
    public function marks(...$marks): TiptapValidationRule
    {
        $this->marks = $marks;

        return $this;
    }

    /**
     * Enable whitelisting mode
     */
    public function whitelist(): TiptapValidationRule
    {
        $this->mode = TiptapValidationRuleMode::WHITELIST;

        return $this;
    }

    /**
     * Enable blacklisting mode
     */
    public function blacklist(): TiptapValidationRule
    {
        $this->mode = TiptapValidationRuleMode::BLACKLIST;

        return $this;
    }

    /**
     * Append node(s)
     *
     * @param  string|string[]  $node
     */
    public function addNode(string|array $node): TiptapValidationRule
    {
        // Add multiple nodes
        if (is_array($node)) {
            $this->nodes = [...$this->nodes, ...$node];

            return $this;
        }

        // Add single node
        $this->nodes[] = $node;

        return $this;
    }

    /**
     * Append mark(s)
     *
     * @param  string|string[]  $mark
     */
    public function addMark(string|array $mark): TiptapValidationRule
    {
        // Add multiple marks
        if (is_array($mark)) {
            $this->marks = [...$this->marks, ...$mark];

            return $this;
        }

        // Add single mark
        $this->nodes[] = $mark;

        return $this;
    }
}
