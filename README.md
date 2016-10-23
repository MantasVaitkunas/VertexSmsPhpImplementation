# Vertex Sms Php Implementation

This is https://vertexsms.com/docs/sms.html PHP implementation.

## Installation via composer

```bash
composer require mantasvaitkunas/vertex-sms-php-implementation
```

## Usage example

```php
$sender = new \MantasVaitkunas\VertexSmsPhpImplementation\Sender('your_api_token');
$sender->setTo('+37088888888');
$sender->setFrom('sender_id');
$sender->setMessage('sms text goes here.');
$result = $sender->send();
```
