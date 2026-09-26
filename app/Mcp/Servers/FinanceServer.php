<?php

declare(strict_types=1);

namespace App\Mcp\Servers;

use App\Mcp\Tools\ApplyAllProposalsTool;
use App\Mcp\Tools\CreateAccountTool;
use App\Mcp\Tools\CreateBankProposalTool;
use App\Mcp\Tools\CreateTransactionForBankTransactionTool;
use App\Mcp\Tools\CreateTransactionTool;
use App\Mcp\Tools\FindMatchingTransactionsTool;
use App\Mcp\Tools\LinkBankTransactionToTransactionTool;
use App\Mcp\Tools\ListAccountsTool;
use App\Mcp\Tools\ListBankAccountsTool;
use App\Mcp\Tools\ShowProposalsTool;
use App\Mcp\Tools\UnresolvedBankTransactionsTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Finance Server')]
#[Version('0.0.1')]
#[Instructions('This server allows to manage the personal finances.')]
final class FinanceServer extends Server
{
    protected array $tools = [
        ListAccountsTool::class,
        ListBankAccountsTool::class,
        UnresolvedBankTransactionsTool::class,
        ShowProposalsTool::class,
        ApplyAllProposalsTool::class,
        CreateTransactionTool::class,
        CreateTransactionForBankTransactionTool::class,
        CreateAccountTool::class,
        CreateBankProposalTool::class,
        FindMatchingTransactionsTool::class,
        LinkBankTransactionToTransactionTool::class,
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}
