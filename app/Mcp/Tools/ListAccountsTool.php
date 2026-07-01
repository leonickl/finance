<?php

namespace App\Mcp\Tools;

use App\Models\Account;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get a list of all accounts including details.')]
class ListAccountsTool extends Tool
{
    public function handle(Request $request): ResponseFactory
    {
        $accounts = Account::allNotArchived()
            ->map(fn (Account $account) => [
                'id' => $account->id,
                'name' => $account->name,
                'type' => $account->type->name,
                'fullname' => $account->fullname,
            ])
            ->values()
            ->toArray();

        return Response::structured(['accounts' => $accounts]);
    }
}
