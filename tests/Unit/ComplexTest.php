<?php

namespace Unit;

use CommerceRun\Markdown;
use PHPUnit\Framework\TestCase;

class ComplexTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function test_complex($inputFile, $outputFile): void
    {
        $processor = new Markdown();

        $input = fopen(__DIR__.'/'.$inputFile, 'r');
        $outputBody = "";
        $generator = $processor->process($input);
        foreach ($generator as $chunk) {
            $outputBody .= $chunk;
        }
        fclose($input);
        $this->assertEquals(file_get_contents(__DIR__.'/'.$outputFile), rtrim($outputBody));
    }

    /**
     * @dataProvider dataProvider
     */
    public function test_string($inputFile, $outputFile): void
    {
        $processor = new Markdown();

        $input = file_get_contents(__DIR__.'/'.$inputFile);
        $output = file_get_contents(__DIR__.'/'.$outputFile);

        $outputBody = $processor->processString($input);
        $this->assertEquals($output, rtrim($outputBody));

    }

    /**
     * @group long_running
     * @dataProvider dataProvider
     */
    public function test_long_run_100000($inputFile, $outputFile): void
    {
        $processor = new Markdown();

        $input = file_get_contents(__DIR__.'/'.$inputFile);
        $output = file_get_contents(__DIR__.'/'.$outputFile);

        for ($i = 0; $i< 100000; $i++) {
            $outputBody = $processor->processString($input);
            $this->assertEquals($output, rtrim($outputBody));
        }
    }

    public static function dataProvider()
    {
        return [
            ['test1.txt', 'test1_result.txt'],
            ['test2.txt', 'test2_result.txt']
            ];

    }
}