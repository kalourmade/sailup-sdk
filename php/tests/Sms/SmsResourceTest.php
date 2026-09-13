<?php

declare(strict_types=1);

namespace Kalourmade\Sailup\Tests\Sms;

use Kalourmade\Sailup\Exceptions\AuthenticationError;
use Kalourmade\Sailup\Sms\SmsResource;
use Kalourmade\Sailup\Tests\Fakes\FakeHttpTransport;
use PHPUnit\Framework\TestCase;

final class SmsResourceTest extends TestCase
{
    private function messageJson(): string
    {
        return json_encode([
            'id' => 'msg_123',
            'to' => ['233241234567'],
            'sender' => 'MyBrand',
            'body' => 'Hello',
            'quantity' => 1,
            'delivery_status' => 'pending',
            'created_at' => '2026-09-12T10:00:00Z',
        ], JSON_THROW_ON_ERROR);
    }

    public function test_send_posts_to_sms_and_returns_message(): void
    {
        $transport = new FakeHttpTransport([
            ['status' => 202, 'body' => $this->messageJson()],
        ]);
        $resource = new SmsResource($transport);

        $message = $resource->send('MyBrand', ['233241234567'], 'Hello');

        $this->assertSame('msg_123', $message->id);
        $this->assertSame('POST', $transport->requests[0]['method']);
        $this->assertSame('/sms/', $transport->requests[0]['path']);
        $this->assertSame(
            ['from' => 'MyBrand', 'to' => ['233241234567'], 'body' => 'Hello'],
            $transport->requests[0]['body']
        );
    }

    public function test_list_gets_sms_with_pagination_query(): void
    {
        $envelope = json_encode([
            'count' => 1,
            'next' => null,
            'previous' => null,
            'results' => [json_decode($this->messageJson(), true)],
        ], JSON_THROW_ON_ERROR);

        $transport = new FakeHttpTransport([
            ['status' => 200, 'body' => $envelope],
        ]);
        $resource = new SmsResource($transport);

        $page = $resource->list(page: 2, pageSize: 50);

        $this->assertSame(1, $page->count);
        $this->assertCount(1, $page->results);
        $this->assertSame('GET', $transport->requests[0]['method']);
        $this->assertSame('/sms/', $transport->requests[0]['path']);
        $this->assertSame(['page' => 2, 'page_size' => 50], $transport->requests[0]['query']);
    }

    public function test_get_fetches_single_message_by_id(): void
    {
        $transport = new FakeHttpTransport([
            ['status' => 200, 'body' => $this->messageJson()],
        ]);
        $resource = new SmsResource($transport);

        $message = $resource->get('msg_123');

        $this->assertSame('msg_123', $message->id);
        $this->assertSame('/sms/msg_123/', $transport->requests[0]['path']);
    }

    public function test_error_status_raises_mapped_exception(): void
    {
        $transport = new FakeHttpTransport([
            ['status' => 401, 'body' => '{}'],
        ]);
        $resource = new SmsResource($transport);

        $this->expectException(AuthenticationError::class);
        $resource->get('msg_123');
    }
}
