<?php

namespace Dissect\Parser\LALR1\Dumper;

use Dissect\Parser\LALR1\Analysis\Analyzer;
use PHPUnit\Framework\TestCase;

class ProductionTableDumperTest extends TestCase
{
    /**
     * @test
     */
    public function theWrittenTableShouldBeAsCompactAsPossible()
    {
        $grammar = new ExampleGrammar();
        $analyzer = new Analyzer();
        $table = $analyzer->analyze($grammar)->getParseTable();

        $dumper = new ProductionTableDumper();
        $dumped = $dumper->dump($table);

        $this->assertStringEqualsFile(__DIR__ . '/res/table/production.php', $dumped);
    }
}
