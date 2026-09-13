<?php

declare(strict_types=1);

namespace Kalourmade\Sailup\Sms;

enum DeliveryStatus: string
{
    case Pending = 'pending';
    case Delivered = 'delivered';
    case Failed = 'failed';
    case Expired = 'expired';
    case Rejected = 'rejected';
}
