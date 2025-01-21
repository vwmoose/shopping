<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShoppingListResource;
use App\Models\ShoppingList;
use Illuminate\Http\Request;

class ShoppingListController extends Controller
{
    /**
     * @return ShoppingListResource
     */
    public function index(Request $request): ShoppingListResource
    {
        // retrieve the shopping list by authenticated user id
        $list = ShoppingList::byUserId($request->user()->id);

        return new ShoppingListResource($list);
    }
}
