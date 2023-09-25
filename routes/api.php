<?php

use App\Http\Controllers\mobile\ApiAuthController;
use App\Http\Controllers\mobile\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/check_registration_code', [ApiAuthController::class, 'checkRegistrationCode']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/check_user_phone', [ApiAuthController::class, 'checkUserPhone']);

Route::get("/get_categories", [ApiController::class, 'getCategories']);

Route::get("/get_products/{category_id}", [ApiController::class, 'getProducts']);
Route::get("/get_product/{product_id}", [ApiController::class, 'getProduct']);
Route::get("/get_product_similar/{product_id}", [ApiController::class, 'getProductWithSimilarProducts']);
Route::get("/get_similar_products/{product_id}", [ApiController::class, 'similarProducts']);

Route::get("/client_orders/{client_id}", [ApiController::class, 'getClientOrders']);
Route::get("/get_clients_count", [ApiController::class, 'getClientsCount']);
Route::get("/get_ads", [ApiController::class, 'getAds']);
Route::get("/get_cards", [ApiController::class, 'getCards']);
Route::post("/create_order", [ApiController::class, 'createOrder']);
Route::post("/update_client", [ApiController::class, 'updateClientData']);
Route::post('/delete_client', [ApiController::class, 'deleteClient']);

//Settings Table
Route::get("/get_settings_item/{name}", [ApiController::class, 'getSettingsItem']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
