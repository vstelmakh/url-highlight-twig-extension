# Twig url highlight
Twig filter to convert urls to html tags  

## Installation
Install the latest version with:  
```bash
$ composer require vstelmakh/twig-url-highlight
```

## Setup
Add extension to your twig environment:  
```php
<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use VStelmakh\Twig\Extension\UrlHighlightExtension;

// create twig environment
$loader = new FilesystemLoader('/path/to/templates');
$twig = new Environment($loader, []);

// add extension
$twig->addExtension(new UrlHighlightExtension());
```

## Usage
Use `urls_to_html` filter in your templates:  
```twig
{{ 'Basic example http://example.com'|urls_to_html }}

{# output: Basic example <a href="http://example.com">http://example.com</a> #}
```

With explicitly defined protocols:  
```twig
{{ 'Basic example http://example.com or ftp://example.com'|urls_to_html(['http', 'https']) }}

{# output: Basic example <a href="http://example.com">http://example.com</a> or ftp://example.com #}
```

## Credits
[Volodymyr Stelmakh](https://github.com/vstelmakh)  
Licensed under the MIT License. See [LICENSE](LICENSE) for more information.  