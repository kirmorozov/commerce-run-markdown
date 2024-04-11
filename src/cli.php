<?php
require "vendor/autoload.php";

$input = fopen('php://stdin','r');

$processor = new \CommerceRun\Markdown();

$generator = $processor->process($input);

foreach($generator as $chunk) {
    echo $chunk;
}
