# Twig url highlight
![Build status](https://github.com/vstelmakh/url-highlight-twig-extension/workflows/build/badge.svg?branch=master)
![PHP version](https://img.shields.io/packagist/php-v/vstelmakh/url-highlight-twig-extension)
![License](https://img.shields.io/github/license/vstelmakh/url-highlight-twig-extension)

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

To properly handle HTML entity escaped string, use [Encoder](https://github.com/vstelmakh/url-highlight#encoder).

## Configuration
Additional options could be provided via UrlHighlight constructor. For more details see: Url highlight [configuration](https://github.com/vstelmakh/url-highlight#configuration).

Example:
```php
<?php
$encoder = new HtmlSpecialcharsEncoder();
$urlHighlight = new UrlHighlight(null, null, $encoder);
$twig->addExtension(new UrlHighlightExtension($urlHighlight));
```

## Credits
[Volodymyr Stelmakh](https://github.com/vstelmakh)  
Licensed under the MIT License. See [LICENSE](LICENSE) for more information.  