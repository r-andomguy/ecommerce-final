<?php

namespace App\Enum\Negotiation;

enum NegotiationStatus: int {
    case PENDING = 1;
    case DECLINED = 2;
    case ACCEPTED = 3;

    public static function values(): array {
        return array_reduce(
            self::cases(),
            fn($acc, $case) => $acc + [$case->value => $case->label()],
            []
        );
    }

    public function label(): string {
        return match ($this) {
            self::PENDING => 'PENDENTE',
            self::DECLINED => 'RECUSADO',
            self::ACCEPTED => 'ACEITO',
        };
    }
}
