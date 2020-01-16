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
use VStelmakh\UrlHighlight\UrlHighlight;
use VStelmakh\UrlHighlightTwigExtension\UrlHighlightExtension;

class UrlHighlightExtensionTest extends TestCase
{
    public function testGetFilters(): void
    {
        $urlHighlightExtension = new UrlHighlightExtension();

        /** @var TwigFilter[] $filters */
        $filters = $urlHighlightExtension->getFilters();
        $urlsToHtmlFilter = $filters[0];

        $name = $urlsToHtmlFilter->getName();
        $this->assertSame('urls_to_html', $name);

        $callable = $urlsToHtmlFilter->getCallable();
        $this->assertInstanceOf(UrlHighlight::class, $callable[0]);
        $this->assertSame('highlightUrls', $callable[1]);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testUrlsToHtml(): void
    {
        $urlHighlightExtension = new UrlHighlightExtension();
        $twig = $this->createTwig($urlHighlightExtension);

        $text = '<h1>Test</h1><div>This is example: http://example.com.</div>';
        $expected = '<h1>Test</h1><div>This is example: <a href="http://example.com">http://example.com</a>.</div>';

        $template = $twig->createTemplate('{{ text|urls_to_html }}');
        $actual = $twig->render($template, ['text' => $text]);

        $this->assertSame($expected, $actual);
    }

    /**
     * @dataProvider optionsApplyDataProvider
     * @param array|mixed[] $options
     * @param string $input
     * @param string $expected
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testIfOptionsApply(array $options, string $input, string $expected): void
    {
        $urlHighlightExtension = new UrlHighlightExtension($options);
        $twig = $this->createTwig($urlHighlightExtension);

        $template = $twig->createTemplate('{{ text|urls_to_html }}');
        $actual = $twig->render($template, ['text' => $input]);
        $this->assertSame($expected, $actual, 'Options: ' . json_encode($options));
    }

    /**
     * @return array|array[]
     */
    public function optionsApplyDataProvider(): array
    {
        return [
            [
                [
                    'match_by_tld' => false,
                ],
                'example.com',
                'example.com',
            ],
            [
                [
                    'default_scheme' => 'https',
                ],
                'example.com',
                '<a href="https://example.com">example.com</a>',
            ],
            [
                [
                    'scheme_blacklist' => ['http'],
                ],
                'http://example.com',
                'http://example.com',
            ],
            [
                [
                    'scheme_whitelist' => ['http'],
                ],
                'ftp://example.com',
                'ftp://example.com',
            ],
        ];
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
