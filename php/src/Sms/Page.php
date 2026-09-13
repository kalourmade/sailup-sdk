<?php

declare(strict_types=1);

namespace Kalourmade\Sailup\Sms;

final class Page
{
    /**
     * @param Message[] $results
     */
    public function __construct(
        public readonly int $count,
        public readonly ?string $next,
        public readonly ?string $previous,
        public readonly array $results,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            count: $data['count'],
            next: $data['next'] ?? null,
            previous: $data['previous'] ?? null,
            results: array_map(static fn (array $m) => Message::fromArray($m), $data['results']),
        );
    }
}
