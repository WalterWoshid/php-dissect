<?php

namespace Dissect\Parser\LALR1\Dumper;

use Dissect\Parser\LALR1\Analysis\Analyzer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DebugTableDumperTest extends TestCase
{
    #[Test]
    public function itDumpsAHumanReadableParseTableWithExplainingComments()
    {
        $grammar = new ExampleGrammar();
        $analyzer = new Analyzer();
        $result = $analyzer->analyze($grammar);

        $dumper = new DebugTableDumper($grammar);
        $dumped = $dumper->dump($result->getParseTable());

        $this->assertStringEqualsFile(__DIR__ . '/res/table/debug.php', $dumped);
    }
}
