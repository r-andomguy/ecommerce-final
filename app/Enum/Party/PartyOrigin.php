<?php

namespace App\Enum\Party;

enum PartyOrigin: int {
    case INSTAGRAM = 1;
    case WHATSAPP = 2;
    case LOCAL = 3;

    public static function values(): array {
        return array_reduce(
            self::cases(),
            fn($acc, $case) => $acc + [$case->value => $case->name],
            []
        );
    }

    public function label(): string {
        return match ($this) {
            self::INSTAGRAM => 'INSTAGRAM',
            self::WHATSAPP => 'WHATSAPP',
            self::LOCAL => 'LOCAL',
        };
    }
}
