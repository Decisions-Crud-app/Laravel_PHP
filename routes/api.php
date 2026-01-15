<?php
use App\Http\Controllers\Api\ItemController;

Route::apiResource('items', ItemController::class)
          ->only(['index', 'store', 'update']);

/**Route::middleware('auth:sanctum')->group(function () {
   Route::apiresource('records', RecordController::class)
       ->only(['index', 'store', 'update']);
});*/
