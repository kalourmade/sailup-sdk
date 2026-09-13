<?php

declare(strict_types=1);

namespace Kalourmade\Sailup\Sms;

use Kalourmade\Sailup\Http\ErrorMapper;
use Kalourmade\Sailup\Http\HttpTransport;

final class SmsResource
{
    public function __construct(private readonly HttpTransport $transport)
    {
    }

    /**
     * @param string[] $to
     */
    public function send(string $from, array $to, string $body): Message
    {
        $response = $this->transport->request('POST', '/sms/', body: [
            'from' => $from,
            'to' => $to,
            'body' => $body,
        ]);

        ErrorMapper::throwIfError($response['status'], $response['body']);

        return Message::fromArray(json_decode($response['body'], true, flags: JSON_THROW_ON_ERROR));
    }

    public function list(int $page = 1, int $pageSize = 30): Page
    {
        $response = $this->transport->request('GET', '/sms/', query: [
            'page' => $page,
            'page_size' => $pageSize,
        ]);

        ErrorMapper::throwIfError($response['status'], $response['body']);

        return Page::fromArray(json_decode($response['body'], true, flags: JSON_THROW_ON_ERROR));
    }

    public function get(string $messageId): Message
    {
        $response = $this->transport->request('GET', "/sms/{$messageId}/");

        ErrorMapper::throwIfError($response['status'], $response['body']);

        return Message::fromArray(json_decode($response['body'], true, flags: JSON_THROW_ON_ERROR));
    }
}
