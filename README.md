### What is this?

A fixer class for the [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)
to add spaces inside array (square) braces, like:
```php
// before
$array['foo'] = ['bar'];

// after
$array[ 'foo' ] = [ 'bar' ];
```
## Usage
Require it via composer:

```bash
composer require --dev yharahuts/space-inside-array-braces-fixer
```

Then enable it in your `.php-cs-fixer` config file:

```php
// ...
$config = new PhpCsFixer\Config();

return $config
	->registerCustomFixers( [
		new \Codestyle\Fixer\SpaceInsideArrayBracesFixer(),
	] )
	->setRules( [
	    // ...
	    'Yharahuts/space_inside_array_braces' => true
    ] );
```