<?php

namespace App\Enums;

enum TicketStatus: string
{
    case OPEN = 'open';
    case ANSWERED = 'answered';
    case PENDING = 'pending';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match($this) {
            self::OPEN => 'Open',
            self::ANSWERED => 'Answered',
            self::PENDING => 'Pending Support',
            self::CLOSED => 'Closed',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::OPEN => 'red',
            self::ANSWERED => 'green',
            self::PENDING => 'yellow',
            self::CLOSED => 'gray',
        };
    }
}
