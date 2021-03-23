# Twig url highlight
[![Build status](https://github.com/vstelmakh/url-highlight-twig-extension/workflows/build/badge.svg?branch=master)](https://github.com/vstelmakh/url-highlight-twig-extension/actions)
[![Packagist version](https://img.shields.io/packagist/v/vstelmakh/url-highlight-twig-extension?color=orange)](https://packagist.org/packages/vstelmakh/url-highlight-twig-extension)
[![PHP version](https://img.shields.io/packagist/php-v/vstelmakh/url-highlight-twig-extension)](https://www.php.net/)
[![License](https://img.shields.io/github/license/vstelmakh/url-highlight-twig-extension?color=yellowgreen)](LICENSE)

Twig extension for [Url highlight](https://github.com/vstelmakh/url-highlight) library  

## Installation
Using Symfony? There is a bundle available: [Url highlight symfony bundle](https://github.com/vstelmakh/url-highlight-symfony-bundle)  

Install the latest version with:  
```bash
$ composer require vstelmakh/url-highlight-twig-extension
```

## Setup
Add extension to your twig environment:  
```php
<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use VStelmakh\UrlHighlight\UrlHighlight;
use VStelmakh\UrlHighlightTwigExtension\UrlHighlightExtension;

// create twig environment
$loader = new FilesystemLoader('/path/to/templates');
$twig = new Environment($loader, []);

// add extension
$urlHighlight = new UrlHighlight();
$twig->addExtension(new UrlHighlightExtension($urlHighlight));
```

## Usage
Use `urls_to_html` filter in your templates:  
```twig
{{ 'Basic example http://example.com'|urls_to_html }}

{# output: Basic example <a href="http://example.com">http://example.com</a> #}
```

To properly handle HTML entity escaped string, use [Encoder](https://github.com/vstelmakh/url-highlight#encoder). See configuration example below.

**Warning: the filter considers the input string being already safe, and it will print any HTML tag in it. It is the developer's responsability to sanitise the input before passing it to `urls_to_html`.**

## Configuration
Additional options could be provided via UrlHighlight constructor. For more details see: Url highlight [configuration](https://github.com/vstelmakh/url-highlight#configuration).

Example:
```php
<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use VStelmakh\UrlHighlight\Encoder\HtmlSpecialcharsEncoder;
use VStelmakh\UrlHighlight\UrlHighlight;
use VStelmakh\UrlHighlightTwigExtension\UrlHighlightExtension;

$loader = new FilesystemLoader('/path/to/templates');
$twig = new Environment($loader, []);

$encoder = new HtmlSpecialcharsEncoder();
$urlHighlight = new UrlHighlight(null, null, $encoder);
$twig->addExtension(new UrlHighlightExtension($urlHighlight));
```
Now escaped input will be handled properly:
```twig
{{ '<a href="http://example.com?a=1&b=2">Example</a>'|escape|urls_to_html }}

{# output: &lt;a href=&quot;<a href="http://example.com?a=1&b=2">http://example.com?a=1&amp;b=2</a>&quot;&gt;Example&lt;/a&gt; #}
```

## Credits
[Volodymyr Stelmakh](https://github.com/vstelmakh)  
Licensed under the MIT License. See [LICENSE](LICENSE) for more information.  
