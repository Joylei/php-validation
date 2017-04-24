# PHP-Validation

Another fluent validation library for php

## Usage

```php
$validator = new Validator();
$validator->ruleFor('field1',function(){
    $this->isRequired('required');
    $this->isNumber('is number');
})->end()->ruleFor('field2', function(){
    $this->hasRange(5, 10, 'range(5,10)');
});

$result = $validator->validate([
    'field1' => 'aaa',
    'field2' => '20'
]);

echo $result->hasError();
echo $result->getError();
```

For more information, please see tests in the source code.

## License

MIT
