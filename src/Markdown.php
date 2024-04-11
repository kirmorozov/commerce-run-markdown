<?php

namespace CommerceRun;

class Markdown
{

    public function __construct()
    {

    }

    protected function makeGenerator($input)
    {
        if ($input instanceof \Generator) {
            return $input;
        }
        $buffer = "";
        if (is_resource($input)) {
            while (($line = fgets($input)) !== false) {
                if ($line == "\n") {
                    yield $buffer;
                    $buffer = "";
                } else {
                    $buffer .= $line;
                }
            }
        } else {
            foreach (explode("\n", $input) as $line) {
                if ($line == "") {
                    $oldBuffer = $buffer;
                    $buffer = "";
                    yield $oldBuffer;

                } else {
                    $buffer .= $line . PHP_EOL;
                }
            }
        }
        if (!empty($buffer)) {
            $buffer = rtrim($buffer);
            yield $buffer;
        }
    }

    public function processString(string $string): string
    {
        return rtrim($this->collect($this->process($string)));
    }

    public function process($input): \Generator
    {
        $generator = $this->makeGenerator($input);

        foreach ($generator as $chunk) {
            yield $this->processChunk($chunk)."\n\n";
        }
    }
    public function collect(\Generator $generator)
    {
        $res = "";
        foreach ($generator as $item) {
            $res .= (string)$item;
        }
        return $res;
    }

    public function processChunk($chunk)
    {
        $len = strlen($chunk);
        $tag = 'p';
        $startPosition = 0;
        if ($chunk[0] == '#') {
            $level = 0;
            while ($startPosition < $len &&  $chunk[$startPosition] =='#') {
                $level++;
                $startPosition++;
            }
            if ($level < 7) {
                $tag = 'h'. $level;
                $startPosition++;
            } else {
                $startPosition = 0;
            }
        }

        // Create wrapper document for the chank
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $element = $doc->createElement($tag);
        $this->safeProcessContent($chunk, $startPosition, $element, $doc);
        $result = $doc->saveHTML($element);
        unset($element);
        unset($doc);

        return $result;
    }

    protected function safeProcessContent($content, $startPosition,\DOMNode $element, \DOMDocument $doc)
    {
        $len = strlen($content);
        if ($content[$len-1] == "\n") {
            $len -=1;
        }
        $start = $startPosition;
        $movingPointer = $startPosition;

        $states = [-1,-1,-1,-1];
        while ($movingPointer < $len) {

            if ($states[0] == -1 && $content[$movingPointer] == '[') {
                $states[0] = $movingPointer;
            }
            if ($states[0] >= 0 && $content[$movingPointer] == ']') {
                $states[1] = $movingPointer;
            } else
            if ($states[1] > 0 && $content[$movingPointer] == '(') {
                $states[2] = $movingPointer;
                // just pair of square brackets no round after
                if ($states[2] -1 != $states[1]) {
                    $states = [-1,-1,-1,-1];
                }
            } else
            if ($states[2] > 0 && $content[$movingPointer] == ')') {
                $states[3] = $movingPointer;
            }
            // found tag for the link
            if ($states[3] > 0) {
                // fill in content before the link
                $textNode = $doc->createTextNode(substr($content,$start, $states[0]-$start));
                $element->appendChild($textNode);
                // Add a tag
                $aTag = $doc->createElement('a', substr($content, $states[0]+1, $states[1] - $states[0] -1));
                $aTag->setAttribute('href', substr($content, $states[2]+1, $states[3]-$states[2] -1));
                $element->appendChild($aTag);
                // move start pointer
                $start = $movingPointer+1;
                // reset states
                $states = [-1,-1,-1,-1];
            }
            $movingPointer++;
        }

        // fill in leftover
        if ($start != $movingPointer) {
            $textNode = $doc->createTextNode(substr($content, $start, $movingPointer - $start));
            $element->appendChild($textNode);
        }

    }
}