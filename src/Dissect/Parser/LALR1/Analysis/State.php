<?php

namespace Dissect\Parser\LALR1\Analysis;

/**
 * A state in a handle-finding FSA.
 *
 * @author Jakub Lédl <jakubledl@gmail.com>
 */
class State
{
    /**
     * @var array
     */
    protected array $items = [];

    /**
     * @var array
     */
    protected array $itemMap = [];

    /**
     * @var int
     */
    protected int $number;

    /**
     * Constructor.
     *
     * @param int $number The number identifying this state.
     * @param array $items The initial items of this state.
     */
    public function __construct(int $number, array $items)
    {
        $this->number = $number;

        foreach ($items as $item) {
            $this->add($item);
        }
    }

    /**
     * Adds a new item to this state.
     *
     * @param Item $item The new item.
     */
    public function add(Item $item): void
    {
        $this->items[] = $item;

        $this->itemMap[$item->getRule()->getNumber()][$item->getDotIndex()] = $item;
    }

    /**
     * Returns an item by its rule number and dot index.
     *
     * @param int $ruleNumber The number of the rule of the desired item.
     * @param int $dotIndex The dot index of the desired item.
     *
     * @return Item The item.
     */
    public function get(int $ruleNumber, int $dotIndex): Item
    {
        return $this->itemMap[$ruleNumber][$dotIndex];
    }

    /**
     * Returns the number identifying this state.
     *
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * Returns an array of items constituting this state.
     *
     * @return array The items.
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
