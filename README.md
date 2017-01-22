CedarMap Package
===================

### Requirements:
- php ~5.6.*

Instalation:
==========
First add package name to your composer requirements
```json
"require": {
    "mammutgroup/cedar": "dev"
}
```

Next, update Composer from the Terminal:
>composer update

Next, add your new provider to the providers array of config/app.php:

```php
'providers' => [
    // ...
    Cedar\CedarServiceProvider::class,
    // ...
  ]
```

Next, add class alias to the aliases array of config/app.php:

```php
'aliases' => [
   // ...
      'Cedar' => Cedar\CedarFacade::class
    // ...
]
```

Finally, run:
> php artisan vendor:publish

Ho to use:
====
```php

$geocode = (new Cedar\Cedar('v1'))
    ->load('geocode')
    ->setParamByKey('title', 'ونک');

    dd($geocode->getJson())
```
