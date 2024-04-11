# Basic Markdown

Supports Headers and links.

```bash
php src/cli.php < tests/Unit/test1.txt
```

For large inputs utilizes generators to process streaming input such as `php://input`

```php
$processor = new \CommerceRun\Markdown();

$generator = $processor->process($input);

foreach($generator as $chunk) {
    echo $chunk;
}
```
