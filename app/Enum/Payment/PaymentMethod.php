<?php

namespace App\Enum\Payment;

enum PaymentMethod: int {
    case PIX = 1;
    case CASH = 2;
    case CREDIT_CARD = 3;
    case DEBIT = 4;

    public static function values(): array {
        return array_reduce(
            self::cases(),
            fn($acc, $case) => $acc + [$case->value => $case->label()],
            []
        );
    }

    public function label(): string {
        return match ($this) {
            self::PIX => 'PIX',
            self::CASH=> 'DINHEIRO',
            self::CREDIT_CARD => 'CRÉDITO',
            self::DEBIT=> 'DÉBITO',
        };
    }

}
