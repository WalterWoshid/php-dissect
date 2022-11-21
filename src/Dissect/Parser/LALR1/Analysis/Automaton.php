<?php

namespace Dissect\Parser\LALR1\Analysis;

/**
 * A finite-state automaton for recognizing
 * grammar productions.
 *
 * @author Jakub Lédl <jakubledl@gmail.com>
 */
class Automaton
{
    /**
     * @var array
     */
    protected array $states = [];

    /**
     * @var array
     */
    protected array $transitionTable = [];

    /**
     * Adds a new automaton state.
     *
     * @param State $state The new state.
     */
    public function addState(State $state): void
    {
        $this->states[$state->getNumber()] = $state;
    }

    /**
     * Adds a new transition in the FSA.
     *
     * @param int $origin The number of the origin state.
     * @param string $label The symbol that triggers this transition.
     * @param int $dest The destination state number.
     */
    public function addTransition(int $origin, string $label, int $dest): void
    {
        $this->transitionTable[$origin][$label] = $dest;
    }

    /**
     * Returns a state by its number.
     *
     * @param int $number The state number.
     *
     * @return State The requested state.
     */
    public function getState(int $number): State
    {
        return $this->states[$number];
    }

    /**
     * Does this automaton have a state identified by $number?
     *
     * @param $number
     *
     * @return boolean
     */
    public function hasState($number): bool
    {
        return isset($this->states[$number]);
    }

    /**
     * Returns all states in this FSA.
     *
     * @return array The states of this FSA.
     */
    public function getStates(): array
    {
        return $this->states;
    }

    /**
     * Returns the transition table for this automaton.
     *
     * @return array The transition table.
     */
    public function getTransitionTable(): array
    {
        return $this->transitionTable;
    }
}
