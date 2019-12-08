<?php
declare(strict_types=1);

namespace VStelmakh\Twig\Extension\Tests;

use VStelmakh\Twig\Extension\UrlsToHtmlExtension;
use PHPUnit\Framework\TestCase;

class UrlsToHtmlExtensionTest extends TestCase
{
    /** @var UrlsToHtmlExtension */
    private $urlsToHtmlExtension;

    public function setUp(): void
    {
        $this->urlsToHtmlExtension = new UrlsToHtmlExtension();
    }

    /**
     * @dataProvider formatUrlsToHtmlDataProvider
     */
    public function testFormatUrlsToHtml(string $text, array $protocols, string $expected): void
    {
        $actual = $this->urlsToHtmlExtension->formatUrlsToHtml($text, $protocols);
        $this->assertEquals($expected, $actual);
    }

    public function formatUrlsToHtmlDataProvider(): array
    {
        return [
            ['http://example.com', [], '<a href="http://example.com">http://example.com</a>'],
            ['http://example.com/', [], '<a href="http://example.com/">http://example.com/</a>'],
            ['http://example.com/path?var=a&var2=b#anchor', [], '<a href="http://example.com/path?var=a&var2=b#anchor">http://example.com/path?var=a&var2=b#anchor</a>'],
            ['//http://example.com/path?var=a&var2=b#anchor', [], '//<a href="http://example.com/path?var=a&var2=b#anchor">http://example.com/path?var=a&var2=b#anchor</a>'],
            [':http://example.com/path?var=a&var2=b#anchor', [], ':<a href="http://example.com/path?var=a&var2=b#anchor">http://example.com/path?var=a&var2=b#anchor</a>'],
            [':http://example.com/path?var=a&var2=b#anchor:', [], ':<a href="http://example.com/path?var=a&var2=b#anchor">http://example.com/path?var=a&var2=b#anchor</a>:'],
            [',http://example.com/path?var=a&var2=b#anchor.', [], ',<a href="http://example.com/path?var=a&var2=b#anchor">http://example.com/path?var=a&var2=b#anchor</a>.'],
            ['Url http://example.com, in the middle of the text', [], 'Url <a href="http://example.com">http://example.com</a>, in the middle of the text'],
            ['Url: http://example.com/. In the middle of the text.', [], 'Url: <a href="http://example.com/">http://example.com/</a>. In the middle of the text.'],
            ['Some text here http://example.com/path/here?var=1qwe=asd, and than other text. And another url https://example2.com/ here.', [], 'Some text here <a href="http://example.com/path/here?var=1qwe=asd">http://example.com/path/here?var=1qwe=asd</a>, and than other text. And another url <a href="https://example2.com/">https://example2.com/</a> here.'],
            ['http://example.com', ['https'], 'http://example.com'],
            ['https://example.com', ['https'], '<a href="https://example.com">https://example.com</a>'],
            ['Some text here http://example.com. Secure not allowed: https://example.com.', ['http'], 'Some text here <a href="http://example.com">http://example.com</a>. Secure not allowed: https://example.com.'],
        ];
    }
}
