<?php

namespace Unit;

use CommerceRun\Markdown;
use PHPUnit\Framework\TestCase;

class ParagraphTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_paragraph(): void
    {
        $processor = new Markdown();
        $res = $processor->processString("How are you?");
        $this->assertEquals('<p>How are you?</p>', $res);
    }
}