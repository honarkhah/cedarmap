Iran Vehicle Namaa Number Package
===================

### Requirements:
- php ~5.4.* 

Instalation:
==========
First add package name to your composer requirements
```json
"require": {
    "namaa/namaa": "dev"
}
```

Next, update Composer from the Terminal:
>composer update

Next, add your new provider to the providers array of config/app.php:

```php
'providers' => [
    // ...
    Namaa\NamaaServiceProvider::class,
    // ...
  ]
```

Next, add class alias to the aliases array of config/app.php:

```php
'aliases' => [
   // ...
      'Namaa' => Namaa\NamaaFacade::class
    // ...
]
```

Finally, run:
> php artisan vendor:publish

Ho to use:
====
```php
  $namaa = new Namaa\Namaa();
  $plak = 21 .
      ' ب ' .
      488 .
      ' - ' .
      88 .
      ' ایران';

  $r = $namaa->setNamaa($plak);
  print_r($r->getparsedData()); exit;
  print_r($namaa->isCab());
```

### Get namaa as image
```php
  $namaa->getImage('path/to/export/image.png');
```

### Add date to image
```php
  $namaa->withDate('95-05-01')->getImage('path/to/export/image.png');
```