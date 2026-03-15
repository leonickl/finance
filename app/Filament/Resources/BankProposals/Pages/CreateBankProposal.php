<?php

declare(strict_types=1);

namespace App\Filament\Resources\BankProposals\Pages;

use App\Filament\Resources\BankProposals\BankProposalResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateBankProposal extends CreateRecord
{
    protected static string $resource = BankProposalResource::class;
}
