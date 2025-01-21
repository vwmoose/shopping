<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShoppingListResource;
use App\Models\ShoppingList;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ShoppingListController extends Controller
{
    /**
     * @return ShoppingListResource
     */
    public function index(Request $request): Response
    {
        // retrieve the shopping list by authenticated user id
        $list = ShoppingList::with('items')->byUserId($request->user()->id)->first();

        return Inertia::render('Dashboard', [
            'list' => $list
        ]);
    }

    /**
     * update the shopping list
     */
    public function update(Request $request)
    {
        // retrieve the authenticated user
        $user = $request->user();

        // retrieve the payload
        $payload = $request->all();

        try {
            // update or create the shopping cart
            $list = $user->list()->updateOrCreate(
                [
                    'id'  => $payload['ulid'],
                ],
                [
                    'spending_limit'    => $payload['spendingLimit'],
                ]
            );
    
            // iterate over the list items added by the user
            collect($payload['listItems'])
                ->each(function($item) use ($list) {
                    // update or create the shopping cart item
                    $list->items()->updateOrCreate(
                        [
                            'id' => $item['id'] ?? null,
                        ],
                        [
                            'description' => $item['description'],
                            'quantity' => $item['quantity'],
                            'is_picked' => $item['is_picked'],
                            'price' => $item['price'],
                        ]
                    );
                });

            // return redirect
            return Redirect::route('dashboard');

        } catch (Exception $e) {

            dd($e->getMessage());
        }
    }
}
