<?php

namespace App\Enum\User;

enum UserDocumentType: int {
    case RG = 1;
    case CPF = 2;
    case CNH = 3;

    public static function values(): array {
        return array_reduce(
            self::cases(),
            fn($acc, $case) => $acc + [$case->value => $case->name],
            []
        );
    }

    public function label(): string {
        return match ($this) {
            self::RG => 'RG',
            self::CPF => 'CPF',
            self::CNH => 'CNH',
        };
    }
}
