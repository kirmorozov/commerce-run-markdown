<?php

namespace Unit;

use PHPUnit\Framework\TestCase;
use CommerceRun\Markdown;
class HeaderTest extends TestCase
{

    public function test_h1(): void
    {
        $processor = new Markdown();
        $res = $processor->processString("# Header");
        $this->assertEquals("<h1>Header</h1>", $res);
    }

    public function test_h2(): void
    {
        $processor = new Markdown();
        $res = $processor->processString("## Header");
        $this->assertEquals("<h2>Header</h2>", $res);
    }

    public function test_h3(): void
    {
        $processor = new Markdown();
        $res = $processor->processString("### Header");
        $this->assertEquals("<h3>Header</h3>", $res);
    }
    public function test_h4(): void
    {
        $processor = new Markdown();
        $res = $processor->processString("#### Header");
        $this->assertEquals("<h4>Header</h4>", $res);
    }
    public function test_h5(): void
    {
        $processor = new Markdown();
        $res = $processor->processString("##### Header");
        $this->assertEquals("<h5>Header</h5>", $res);
    }
    public function test_h6(): void
    {
        $processor = new Markdown();
        $res = $processor->processString("###### Header");
        $this->assertEquals("<h6>Header</h6>", $res);
    }

    public function test_h7(): void
    {
        $processor = new Markdown();
        $res = $processor->processString("####### Header");
        $this->assertEquals("<p>####### Header</p>", $res);
    }
}