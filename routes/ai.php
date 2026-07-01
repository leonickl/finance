<?php

use App\Http\Middleware\VerifyMcpApiKey;
use Illuminate\Support\Facades\Route;
use Laravel\Mcp\Facades\Mcp;
use App\Mcp\Servers\FinanceServer;

Route::middleware([VerifyMcpApiKey::class])->group(function (): void {
    Mcp::web('/mcp/finance', FinanceServer::class);
});
