<?php

namespace Dissect\Parser\LALR1\Dumper;

use Dissect\Parser\LALR1\Analysis\Analyzer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AutomatonDumperTest extends TestCase
{
    protected AutomatonDumper $dumper;

    protected function setUp(): void
    {
        $analyzer = new Analyzer();
        $automaton = $analyzer->analyze(new ExampleGrammar())->getAutomaton();
        $this->dumper = new AutomatonDumper($automaton);
    }

    #[Test]
    public function dumpDumpsTheEntireAutomaton()
    {
        $this->assertStringEqualsFile(
            __DIR__ . '/res/graphviz/automaton.dot',
            $this->dumper->dump()
        );
    }

    #[Test]
    public function dumpStateDumpsOnlyTheSpecifiedStateAndTransitions()
    {
        $this->assertStringEqualsFile(
            __DIR__ . '/res/graphviz/state.dot',
            $this->dumper->dumpState(2)
        );
    }
}
