<?php

namespace Dissect\Parser;

use LogicException;

/**
 * Represents a context-free grammar.
 *
 * @author Jakub Lédl <jakubledl@gmail.com>
 */
class Grammar
{
    /**
     * The name given to the rule the grammar is augmented with
     * when start() is called.
     */
    const START_RULE_NAME = '$start';

    /**
     * The epsilon symbol signifies an empty production.
     */
    const EPSILON = '$epsilon';

    /**
     * @var Rule[]
     */
    protected array $rules = [];

    protected array $groupedRules = [];

    protected int $nextRuleNumber = 1;

    protected int $conflictsMode = 9; // SHIFT | OPERATORS

    protected ?string $currentNonterminal = null;

    /**
     * @var string[]
     */
    private array $nonterminals = [];

    protected ?Rule $currentRule = null;

    protected array $operators = [];

    protected ?array $currentOperators = null;

    /**
     * Signifies that the parser should not resolve any
     * grammar conflicts.
     */
    const NONE = 0;

    /**
     * Signifies that the parser should resolve
     * shift/reduce conflicts by always shifting.
     */
    const SHIFT = 1;

    /**
     * Signifies that the parser should resolve
     * reduce/reduce conflicts by reducing with
     * the longer rule.
     */
    const LONGER_REDUCE = 2;

    /**
     * Signifies that the parser should resolve
     * reduce/reduce conflicts by reducing
     * with the rule that was given earlier in
     * the grammar.
     */
    const EARLIER_REDUCE = 4;

    /**
     * Signifies that the conflicts should be
     * resolved by taking operator precendence
     * into account.
     */
    const OPERATORS = 8;

    /**
     * Signifies that the parser should automatically
     * resolve all grammar conflicts.
     */
    const ALL = 15;

    /**
     * Left operator associativity.
     */
    const LEFT = 0;

    /**
     * Right operator associativity.
     */
    const RIGHT = 1;

    /**
     * The operator is nonassociative.
     */
    const NONASSOC = 2;

    public function __invoke(string $nonterminal): static
    {
        $this->currentNonterminal = $nonterminal;

        return $this;
    }

    /**
     * Defines an alternative for a grammar rule.
     *
     * @param string ...$components The components of the rule.
     *
     * @return Grammar This instance.
     */
    public function is(string ...$components): static
    {
        $this->currentOperators = null;

        if ($this->currentNonterminal === null) {
            throw new LogicException(
                'You must specify a name of the rule first.'
            );
        }

        $num = $this->nextRuleNumber++;

        $rule = new Rule($num, $this->currentNonterminal, $components);

        $this->rules[$num] =
            $this->currentRule =
            $this->groupedRules[$this->currentNonterminal][] =
            $rule;

        return $this;
    }

    /**
     * Sets the callback for the current rule.
     *
     * @param callable $callback The callback.
     *
     * @return Grammar This instance.
     */
    public function call(callable $callback): static
    {
        if ($this->currentRule === null) {
            throw new LogicException(
                'You must specify a rule first.'
            );
        }

        $this->currentRule->setCallback($callback);

        return $this;
    }

    /**
     * Returns the set of rules of this grammar.
     *
     * @return Rule[] The rules.
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    public function getRule($number): Rule
    {
        return $this->rules[$number];
    }

    /**
     * Returns the nonterminal symbols of this grammar.
     *
     * @return string[] The nonterminals.
     */
    public function getNonterminals(): array
    {
        return $this->nonterminals;
    }

    /**
     * Returns rules grouped by nonterminal name.
     *
     * @return array The rules grouped by nonterminal name.
     */
    public function getGroupedRules(): array
    {
        return $this->groupedRules;
    }

    /**
     * Sets a start rule for this grammar.
     *
     * @param string $name The name of the start rule.
     */
    public function start(string $name): void
    {
        $this->rules[0] = new Rule(0, self::START_RULE_NAME, [$name]);
    }

    /**
     * Returns the augmented start rule. For internal use only.
     *
     * @return Rule The start rule.
     */
    public function getStartRule(): Rule
    {
        if (!isset($this->rules[0])) {
            throw new LogicException("No start rule specified.");
        }

        return $this->rules[0];
    }

    /**
     * Sets the mode of conflict resolution.
     *
     * @param int $mode The bitmask for the mode.
     */
    public function resolve(int $mode): void
    {
        $this->conflictsMode = $mode;
    }

    /**
     * Returns the conflict resolution mode for this grammar.
     *
     * @return int The bitmask of the resolution mode.
     */
    public function getConflictsMode(): int
    {
        return $this->conflictsMode;
    }

    /**
     * Does a nonterminal $name exist in the grammar?
     *
     * @param string $name The name of the nonterminal.
     *
     * @return boolean
     */
    public function hasNonterminal(string $name): bool
    {
        return array_key_exists($name, $this->groupedRules);
    }

    /**
     * Defines a group of operators.
     *
     * @param string ...$ops Any number of tokens that serve as the operators.
     *
     * @return Grammar This instance for fluent interface.
     */
    public function operators(string ...$ops): static
    {
        $this->currentRule = null;

        $this->currentOperators = $ops;

        foreach ($ops as $op) {
            $this->operators[$op] = [
                'prec' => 1,
                'assoc' => self::LEFT,
            ];
        }

        return $this;
    }

    /**
     * Marks the current group of operators as left-associative.
     *
     * @return Grammar This instance for fluent interface.
     */
    public function left(): static
    {
        return $this->assoc(self::LEFT);
    }

    /**
     * Marks the current group of operators as right-associative.
     *
     * @return Grammar This instance for fluent interface.
     */
    public function right(): static
    {
        return $this->assoc(self::RIGHT);
    }

    /**
     * Marks the current group of operators as nonassociative.
     *
     * @return Grammar This instance for fluent interface.
     */
    public function nonassoc(): static
    {
        return $this->assoc(self::NONASSOC);
    }

    /**
     * Explicitly sets the associatity of the current group of operators.
     *
     * @param int $a One of Grammar::LEFT, Grammar::RIGHT, Grammar::NONASSOC
     *
     * @return Grammar This instance for fluent interface.
     */
    public function assoc(int $a): static
    {
        if (!$this->currentOperators) {
            throw new LogicException('Define a group of operators first.');
        }

        foreach ($this->currentOperators as $op) {
            $this->operators[$op]['assoc'] = $a;
        }

        return $this;
    }

    /**
     * Sets the precedence (as an integer) of the current group of operators.
     * If no group of operators is being specified, sets the precedence
     * of the currently described rule.
     *
     * @param int $i The precedence as an integer.
     *
     * @return Grammar This instance for fluent interface.
     */
    public function prec(int $i): static
    {
        if (!$this->currentOperators) {
            if (!$this->currentRule) {
                throw new LogicException('Define a group of operators or a rule first.');
            } else {
                $this->currentRule->setPrecedence($i);
            }
        } else {
            foreach ($this->currentOperators as $op) {
                $this->operators[$op]['prec'] = $i;
            }
        }

        return $this;
    }

    /**
     * Is the passed token an operator?
     *
     * @param string $token The token type.
     *
     * @return boolean
     */
    public function hasOperator(string $token): bool
    {
        return array_key_exists($token, $this->operators);
    }

    public function getOperatorInfo($token)
    {
        return $this->operators[$token];
    }
}
