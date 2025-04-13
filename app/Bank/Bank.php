<?php

declare(strict_types=1);

namespace App\Bank;

enum Bank: string
{
    case SPARDA = 'SPARDA';
    case PAYPAL = 'PAYPAL';
    case DKB = 'DKB';
    case TRADE_REPUBLIC = 'TRADE_REPUBLIC';

    public function makeParser(int $bankAccountId): Parser
    {
        return match ($this) {
            Bank::SPARDA => new SpardaCsvParser($bankAccountId),
            Bank::PAYPAL => new PaypalCsvParser($bankAccountId),
            Bank::DKB => new DkbCsvParser($bankAccountId),
            Bank::TRADE_REPUBLIC => new TradeRepublicParser($bankAccountId),
        };
    }
}
