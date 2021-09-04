# Laravel Route Find

Find the route name and file path with number from any real URLs.

## Summary

- [About](#about)
- [Features](#features)
- [Installation](#installation)
- [Examples](#examples)
- [Compatibility table](#compatibility-table)
- [Tests](#tests)

## About

Sometimes I loose a few seconds to figure out where is my controller for a given route. It gets worse when I use Route::resource, or any route grouping method, which makes it hard to just find the declared route.

I found it could be cool to just give a "real" URL, find the correct controller.

## Features

- A command line which takes your "real" URL in parameter, and find the controller file path with the number of the line (to let you clic and go to the file from your code editor)
- Works both with "full" URL (https://example.com/user) or path (/user)
- You can use an optional argument to ask for a specific HTTP method and find the correct controller method

## Installation

```bash
composer require --dev khalyomede/laravel-route-find
```

The command line will automatically be registered without any other manipulation. Check it works correctly by running:

```bash
khalyomede@pc > php artisan route:find --help
```

## Examples

- [1. Find a route from a path](#1-find-a-route-from-a-path)
- [2. Precise the HTTP method](#2-precise-the-http-method)
- [3. Find using a full URL](#3-find-using-a-full-url)

### 1. Find a route from a path

In this example, we will try to find the route info from an URL.

```bash
khalyomede@pc > php artisan route:find /user/45/cart/27
+------------------------------------------------+-------------------------+----------------+-------------+
| controller                                     | path                    | name           | middlewares |
+------------------------------------------------+-------------------------+--------------- +-------------+
| app/Http/Controllers/UserCartController.php:54 | user/{user}/cart/{cart} | user.cart.show | web, auth   |
+------------------------------------------------+-------------------------+----------------+-------------+
```

Tips: On VSCode, hold [CTRL] and left clic on the file path to go directly to the file.

### 2. Precise the HTTP method

By default, this command will assume you find a route attached to the GET HTTP method. In this example we will tell the command to find a POST route instead.

```bash
khalyomede@pc > php artisan route:find /user/45/cart --method POST
+------------------------------------------------+------------------+-----------------+-------------+
| controller                                     | path             | name            | middlewares |
+------------------------------------------------+------------------+-----------------+-------------+
| app/Http/Controllers/UserCartController.php:24 | user/{user}/cart | user.cart.store | web, auth   |
+------------------------------------------------+------------------+-----------------+-------------+
```

### 3. Find using a full URL

In this example, we will just copy/paste the URL from our browser to find the route.

```bash
khalyomede@pc > php artisan route:find http://example.com/user/45/cart/27
+------------------------------------------------+-------------------------+----------------+-------------+
| controller                                     | path                    | name           | middlewares |
+------------------------------------------------+-------------------------+--------------- +-------------+
| app/Http/Controllers/UserCartController.php:54 | user/{user}/cart/{cart} | user.cart.show | web, auth   |
+------------------------------------------------+-------------------------+----------------+-------------+
```

## Compatibility table

| Package version | Laravel version | PHP version | Compatible |
|-----------------|-----------------|-------------|------------|
|           0.1.0 |             8.* |         8.* |      ✅     |
|                 |             8.* |         7.* |      ❌     |

## Tests

```bash
composer install
composer install-checker
composer run test
composer run analyse
composer run lint
composer run check
composer outdated --direct
```
