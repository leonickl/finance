<?php

declare(strict_types=1);

namespace App\Bank;

use App\Bank\ParserResult;

interface Parser
{
    public function parse(string $data): ParserResult;
}
