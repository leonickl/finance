<?php

namespace App\Mcp\Tools;

use App\Models\Account;
use App\Types\AccountType;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a new account with a name and type.')]
class CreateAccountTool extends Tool
{
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'type' => ['required', 'integer'],
            'archived' => ['nullable', 'boolean'],
            'recurring' => ['nullable', 'boolean'],
            'interest_rate' => ['nullable', 'numeric'],
        ]);

        $account = new Account;
        $account->name = $validated['name'];
        $account->type = AccountType::make($validated['type']);
        $account->archived = $validated['archived'] ?? false;
        $account->recurring = $validated['recurring'] ?? false;
        $account->interest_rate = $validated['interest_rate'] ?? null;
        $account->save();

        return Response::text("VERBATIM: Created account {$account->id}: {$account->fullname}");
    }

    public function schema(JsonSchema $schema): array
    {
        $accountTypes = AccountType::all()->map(fn ($name, $id) => "$id=$name")->join(', ');

        return [
            'name' => $schema->string()
                ->description('The name of the account.')
                ->required(),

            'type' => $schema->integer()
                ->description('The account type ID: '.$accountTypes)
                ->required(),

            'archived' => $schema->boolean()
                ->description('Whether the account is archived. Defaults to false.'),

            'recurring' => $schema->boolean()
                ->description('Whether the account is recurring. Defaults to false.'),

            'interest_rate' => $schema->number()
                ->description('Interest rate for interest-bearing accounts.'),
        ];
    }
}
