<?php

declare(strict_types=1);

use App\Http\Middleware\VerifyMcpApiKey;
use App\Mcp\Servers\FinanceServer;
use Illuminate\Support\Facades\Route;
use Laravel\Mcp\Facades\Mcp;

Route::middleware([VerifyMcpApiKey::class])->group(function (): void {
    Mcp::web('/mcp/finance', FinanceServer::class);
});
