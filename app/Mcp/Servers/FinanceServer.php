<?php

namespace App\Mcp\Servers;

use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Finance Server')]
#[Version('0.0.1')]
#[Instructions('This server allows to manage the personal finances.')]
class FinanceServer extends Server
{
    protected array $tools = [
        \App\Mcp\Tools\ListAccountsTool::class,
        \App\Mcp\Tools\ListBankAccountsTool::class,
        \App\Mcp\Tools\UnresolvedBankTransactionsTool::class,
        \App\Mcp\Tools\ShowProposalsTool::class,
        \App\Mcp\Tools\ApplyAllProposalsTool::class,
        \App\Mcp\Tools\CreateTransactionTool::class,
        \App\Mcp\Tools\CreateTransactionForBankTransactionTool::class,
        \App\Mcp\Tools\CreateAccountTool::class,
        \App\Mcp\Tools\CreateBankProposalTool::class,
        \App\Mcp\Tools\FindMatchingTransactionsTool::class,
        \App\Mcp\Tools\LinkBankTransactionToTransactionTool::class,
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}
