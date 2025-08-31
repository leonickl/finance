<?php

declare(strict_types=1);

namespace App\Bank;

interface Parser
{
    public function parse(string $data): ParserResult;
}
