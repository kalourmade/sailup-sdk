<?php

declare(strict_types=1);

namespace Kalourmade\Sailup\Tests\Sms;

use Kalourmade\Sailup\Sms\Message;
use Kalourmade\Sailup\Sms\Page;
use PHPUnit\Framework\TestCase;

final class PageTest extends TestCase
{
    public function test_from_array_maps_envelope_and_results(): void
    {
        $page = Page::fromArray([
            'count' => 1,
            'next' => null,
            'previous' => null,
            'results' => [[
                'id' => 'msg_123',
                'to' => ['233241234567'],
                'sender' => 'MyBrand',
                'body' => 'Hello',
                'quantity' => 1,
                'delivery_status' => 'pending',
                'created_at' => '2026-09-12T10:00:00Z',
            ]],
        ]);

        $this->assertSame(1, $page->count);
        $this->assertNull($page->next);
        $this->assertCount(1, $page->results);
        $this->assertInstanceOf(Message::class, $page->results[0]);
    }
}
