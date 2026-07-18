<?php

namespace App\Enums;

enum ServiceRequestStatus: string
{
    case PENDING = 'pending';
    case QUOTED = 'quoted';
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending Review',
            self::QUOTED => 'Quote Sent',
            self::ACTIVE => 'Work In Progress',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::QUOTED => 'blue',
            self::ACTIVE => 'indigo',
            self::COMPLETED => 'green',
            self::CANCELLED => 'gray',
        };
    }
}
