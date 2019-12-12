<?php
declare(strict_types=1);

namespace VStelmakh\TwigUrlHighlightExtension\Tests;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use VStelmakh\TwigUrlHighlightExtension\UrlHighlightExtension;
use PHPUnit\Framework\TestCase;

class UrlHighlightExtensionIntegrationTest extends TestCase
{
    /** @var Environment */
    private $twig;

    public function setUp(): void
    {
        $loader = new FilesystemLoader('');
        $this->twig = new Environment($loader, []);
        $this->twig->addExtension(new UrlHighlightExtension());
    }

    /**
     * @dataProvider formatUrlsToHtmlPreEscapeIsSafeDataProvider
     */
    public function testFormatUrlsToHtmlPreEscapeIsSafe(string $text, string $expected): void
    {
        $template = $this->twig->createTemplate('{{ text|urls_to_html }}');
        $actual = $this->twig->render($template, ['text' => $text]);

        $this->assertSame($expected, $actual);
    }

    public function formatUrlsToHtmlPreEscapeIsSafeDataProvider(): array
    {
        return [
            ['<h1>Test</h1>http://example.com', '&lt;h1&gt;Test&lt;/h1&gt;<a href="http://example.com">http://example.com</a>'],
            ['<h1>Test</h1><p>Hello http://example.com, welcome back!</p>', '&lt;h1&gt;Test&lt;/h1&gt;&lt;p&gt;Hello <a href="http://example.com">http://example.com</a>, welcome back!&lt;/p&gt;'],
        ];
    }
}
