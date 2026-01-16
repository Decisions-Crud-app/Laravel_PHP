<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $items = Item::when(
            $request->status,
            fn ($q) => $q->where('status', $request->status)
        )->get();
        
        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'code' => 'required|string|unique:items,code|max:50',
            'status' => 'required|in:active,inactive',
        ]);
 
        $item = Item::create($validated);
 
        return response()->json($item, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);
        
        $item->update($validated);
        
        return response()->json($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item): JsonResponse
    {
        $item->delete();
        
        return response()->json(null, 204);
    }
}
