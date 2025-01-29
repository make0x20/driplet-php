# Driplet PHP Library
A PHP library for broadcasting messages through WebSocket connections using the [Driplet](https://github.com/make0x20/driplet) microservice. This library provides a way to generate JWT tokens and send messages the microservice.

## Requirements

- PHP 8.3 or higher
- Composer

## Installation
You can install the package via composer:

```bash
composer require make0x20/driplet
```

## Usage

### Sending a message

```php
use Driplet\Client\DripletClient;

// Initialize the client
$client = new DripletClient(
    'https://your-driplet-server.com/api/default/message',
    'your-secret-key'
);

// Create and send a message
$success = $client->createMessage()
    ->setMessage(['content' => 'Hello, World!'])
    ->setTopic('notifications')
    ->include()
        ->setTarget('roles', ['admin', 'editor'])
    ->exclude()
        ->setTarget('users', [123])
    ->build();

if ($success) {
    echo "Message sent successfully!";
}
```

## Generate JWT tokens

```php
use Driplet\Token\JwtManager;

// Initialize the JWT manager
$jwtManager = new JwtManager('your-jwt-secret', 60); // 60 seconds expiration

// Generate a token with custom claims
$token = $jwtManager->generateToken([
    'uid' => 123,
    'roles' => ['user', 'admin']
]);
```

## Message structure

```php
[
    'nonce' => 'random-unique-string',
    'timestamp' => 1234567890,
    'message' => [
        // Your message content
    ],
    'target' => [
        'include' => [
            // Targets to include
        ],
        'exclude' => [
            // Targets to exclude
        ]
    ],
    'topic' => 'your-topic'
]
```

## Target system

The target system allows you to specify which clients should receive the message:

```php
$client->createMessage()
    ->setMessage(['event' => 'update'])
    ->setTopic('system')
    ->include()
        ->setTarget('roles', ['admin'])
        ->setTarget('departments', ['IT'])
    ->exclude()
        ->setTarget('users', [123, 456])
    ->build();
```

