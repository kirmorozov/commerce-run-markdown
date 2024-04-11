<?php

namespace Unit;

use PHPUnit\Framework\TestCase;
use CommerceRun\Markdown;

class LinkTest extends TestCase
{

    public function test_good_link(): void
    {
        $processor = new Markdown();
        $res = $processor->processString("[Link](is good)");
        $this->assertEquals('<p><a href="is%20good">Link</a></p>', $res);
    }

    public function test_broken_link(): void
    {
        $processor = new Markdown();
        $res = $processor->processString("[Link](is broken");
        $this->assertEquals("<p>[Link](is broken</p>", $res);
    }

    public function test_broken_link2(): void
    {
        $processor = new Markdown();
        $res = $processor->processString("[Link] (is broken)");
        $this->assertEquals("<p>[Link] (is broken)</p>", $res);
    }

    public function test_broken_link3(): void
    {
        $processor = new Markdown();
        $res = $processor->processString("[Link]is broken)");
        $this->assertEquals("<p>[Link]is broken)</p>", $res);
    }
}