Hippy
=====

[![Build Status](https://secure.travis-ci.org/rcrowe/Hippy.png)](http://travis-ci.org/rcrowe/Hippy)

Hippy is a simple PHP client for sending messages to a HipChat room. It is designed for incidental notifications from an application.

Hippy does one thing and one thing well, sending messages to a Hipchat room.

Installation
------------

Add `rcrowe\hippy` as a requirement to composer.json:

```javascript
{
    "require": {
        "rcrowe/hippy": "0.6.*"
    }
}
```

Update your packages with `composer update` or install with `composer install`.

Usage
-----

```php
$transport = new rcrowe\Hippy\Transport\Guzzle($token, $room, $from);
$hippy = new rcrowe\Hippy\Client($transport);

$message = new rcrowe\Hippy\Message(true, Message::BACKGROUND_YELLOW);
$message->setText('test');

$hippy->send($message);
```

Hippy also provides a static interface just like v0.5 and below.

```php
Hippy::init($token, $room, $from);
Hippy::html('<a href="#">test failed</a>');
```

Maybe you want to add the message to a queue and send it at the end.

```php
Hippy::init($token, $room, $from);
Hippy::add('test 1');
Hippy::addHtml('test 2');
Hippy::go();
```

Phing
-----

Use [Phing](http://www.phing.info/) for builds and want to send messages to Hipchat? Then checkout out [phing-hipchat](https://github.com/rcrowe/phing-hipchat).

Tests
-----

To run all tests

    $> phpunit tests

License
-------

Hippy is released under the MIT public license.
