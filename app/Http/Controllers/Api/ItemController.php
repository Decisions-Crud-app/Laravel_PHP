<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Item::when(
            $request->status,
            fn ($q) => $q->where('status', $request->status)
        )->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'code' => 'required|string|unique:items,code',
            'status' => 'required|in:active,inactive',
        ]);
 
        $items = Item::create($validated);
 
        return response()->json($items, 201);

}
    /**
     * Display the specified resource.
     */
    /**public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $item->update(
            $request->validate([
                'name' => 'required',
                'description' => 'nullable',
                'status' => 'required|in:active,inactive',
            ])
        );
       return $item;
    }


    /**
     * Remove the specified resource from storage.
     */
    /**public function destroy(string $id)
    {
        //
    }
        **/
}
