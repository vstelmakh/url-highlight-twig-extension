<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlightTwigExtension\Tests;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use PHPUnit\Framework\TestCase;
use VStelmakh\UrlHighlight\Encoder\HtmlSpecialcharsEncoder;
use VStelmakh\UrlHighlight\UrlHighlight;
use VStelmakh\UrlHighlightTwigExtension\UrlHighlightExtension;

class UrlHighlightExtensionTest extends TestCase
{
    /**
     * @var UrlHighlight
     */
    private $urlHighlight;

    /**
     * @var UrlHighlightExtension
     */
    private $urlHighlightExtension;

    public function setUp(): void
    {
        $encoder = new HtmlSpecialcharsEncoder();
        $this->urlHighlight = new UrlHighlight(null, null, $encoder);
        $this->urlHighlightExtension = new UrlHighlightExtension($this->urlHighlight);
    }

    public function testGetFilters(): void
    {
        /** @var TwigFilter[] $filters */
        $filters = $this->urlHighlightExtension->getFilters();
        $urlsToHtmlFilter = $filters[0];

        $name = $urlsToHtmlFilter->getName();
        self::assertSame('urls_to_html', $name);

        $callable = $urlsToHtmlFilter->getCallable();
        self::assertInstanceOf(UrlHighlight::class, $callable[0]);
        self::assertSame('highlightUrls', $callable[1]);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testUrlsToHtml(): void
    {
        $twig = $this->createTwig($this->urlHighlightExtension);

        $text = '<h1>Test</h1><div>This is example: http://example.com.</div>';
        $expected = '<h1>Test</h1><div>This is example: <a href="http://example.com">http://example.com</a>.</div>';

        $template = $twig->createTemplate('{{ text|urls_to_html }}');
        $actual = $twig->render($template, ['text' => $text]);

        self::assertSame($expected, $actual);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testUrlsToHtmlEscape(): void
    {
        $twig = $this->createTwig($this->urlHighlightExtension);

        $text = '<a href="http://example.com?a=1&b=2">http://example.com</a> and <div>http://example.com?a=1&b=2</div>';
        $expected = '&lt;a href=&quot;<a href="http://example.com?a=1&b=2">http://example.com?a=1&amp;b=2</a>&quot;&gt;<a href="http://example.com">http://example.com</a>&lt;/a&gt; and &lt;div&gt;<a href="http://example.com?a=1&b=2">http://example.com?a=1&amp;b=2</a>&lt;/div&gt;';

        $template = $twig->createTemplate('{{ text|e|urls_to_html }}');
        $actual = $twig->render($template, ['text' => $text]);

        self::assertSame($expected, $actual);
    }

    /**
     * @param UrlHighlightExtension $urlHighlightExtension
     * @return Environment
     */
    private function createTwig(UrlHighlightExtension $urlHighlightExtension): Environment
    {
        $loader = new FilesystemLoader('');
        $twig = new Environment($loader, []);
        $twig->addExtension($urlHighlightExtension);
        return $twig;
    }
}
