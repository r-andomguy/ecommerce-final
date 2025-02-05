<?php

namespace App\Enum\Party;

enum PartyRole: int {
    case CUSTOMER = 1;
    case SUPPLIER = 2;

    public static function values(): array {
        return array_reduce(
            self::cases(),
            fn($acc, $case) => $acc + [$case->value => $case->name],
            []
        );
    }

    public function label(): string {
        return match ($this) {
            self::CUSTOMER => 'CLIENTE',
            self::SUPPLIER => 'FORNECEDOR',
        };
    }
}
