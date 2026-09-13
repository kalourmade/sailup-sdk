<?php

declare(strict_types=1);

namespace Kalourmade\Sailup\Sms;

final class Message
{
    /**
     * @param string[] $to
     */
    public function __construct(
        public readonly string $id,
        public readonly array $to,
        public readonly ?string $sender,
        public readonly string $body,
        public readonly int $quantity,
        public readonly DeliveryStatus $deliveryStatus,
        public readonly string $createdAt,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            to: is_array($data['to']) ? $data['to'] : [$data['to']],
            sender: $data['sender'] ?? null,
            body: $data['body'],
            quantity: $data['quantity'],
            deliveryStatus: DeliveryStatus::from($data['delivery_status']),
            createdAt: $data['created_at'],
        );
    }
}
