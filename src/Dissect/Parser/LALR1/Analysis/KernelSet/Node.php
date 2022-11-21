<?php

namespace Dissect\Parser\LALR1\Analysis\KernelSet;

class Node
{
    public array $kernel;
    public int $number;

    public ?Node $left = null;
    public ?Node $right = null;

    public function __construct(array $hashedKernel, int $number)
    {
        $this->kernel = $hashedKernel;
        $this->number = $number;
    }
}
