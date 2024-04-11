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

    public function test_good_link_in_a_header(): void
    {
        $processor = new Markdown();
        $res = $processor->processString("## [Link](is good)");
        $this->assertEquals('<h2><a href="is%20good">Link</a></h2>', $res);
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


    public function test_edge_case1(): void
    {
        $processor = new Markdown();
        $res = $processor->processString('### [<a href="something">Really bad</a>](is bad)');
        $this->assertEquals('<h3><a href="is%20bad">&lt;a href="something"&gt;Really bad&lt;/a&gt;</a></h3>', $res);
    }
}