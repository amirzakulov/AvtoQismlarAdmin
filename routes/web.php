<?php

use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\IncomeProductsController;
use App\Http\Controllers\IncomeSetsController;
use App\Http\Controllers\OrderDetailsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\AdUsersController;
use App\Http\Controllers\ProductCategoriesController;
use App\Http\Controllers\ProductsImagesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\AdminCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;

use App\Http\Controllers\ContractsController;
use App\Http\Controllers\CurrenciesController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ClientsNotificationsController;
use App\Http\Controllers\AdminController;
use \App\Http\Controllers\ProductTypesController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\CardTypesController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\StoresController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('app/sms_send', [AdminController::class, 'testsmssend']);
// Route::get('app/get_partners_ads', [PartnersController::class, 'getPartnersWithAds']);



Route::prefix("app")->middleware([AdminCheck::class])->group(function(){

    //reports
    Route::post('/get_products_report', [ReportsController::class, 'getProductsReport']);
    Route::post('/get_clients_report', [ReportsController::class, 'getClientsReport']);

    //Products
    Route::get('/get_products', [ProductsController::class, 'getProducts']);
    Route::get('/get_product/{id}', [ProductsController::class, 'getProduct']);
    Route::post('/add_product', [ProductsController::class, 'addProduct']);
    Route::post('/edit_product', [ProductsController::class, 'editProduct']);
    Route::post('/edit_product_price', [ProductsController::class, 'updateProductPrice']);
    Route::post('/delete_product', [ProductsController::class, 'deleteProduct']);
    Route::get('/get_product_categories/{product_id}/{names?}', [ProductsController::class, 'getCategories']);
    Route::post('/product_image_upload', [ProductsController::class, 'imageUpload']);
    Route::post('/product_images_upload', [ProductsController::class, 'imagesUpload']);
    Route::get("/get_category_products/{category_id}", [ProductsController::class, 'getCategoryProducts']);

// Product Types
    Route::get("/get_product_types", [ProductTypesController::class, 'getProductTypes']);

// Product Categories
    Route::post("/edit_product_categories", [ProductCategoriesController::class, 'editProductCategories']);

// Product Images
    Route::get('/get_product_images/{product_id}', [ProductsImagesController::class, 'getProductImages']);
    Route::post('/add_product_images', [ProductsImagesController::class, 'addProductImages']);
    Route::post('/delete_image', [ProductsImagesController::class, 'deleteImage']);

//Inout Sets
    Route::get('/get_income_sets', [IncomeSetsController::class, 'getIncomeSets']);
    Route::get('/get_income_set/{id}', [IncomeSetsController::class, 'getIncomeSet']);
    Route::post('/update_income_set', [IncomeSetsController::class, 'updateIncomeSet']);

//Income Products
    Route::post('/add_income_products', [IncomeProductsController::class, 'addIncomeProducts']);
    Route::get('/get_income_products/{contract_id}', [IncomeProductsController::class, 'getIncomeProducts']);
    Route::post('/add_income_product', [IncomeProductsController::class, 'addIncomeProduct']);
    Route::post('/edit_income_product', [IncomeProductsController::class, 'editIncomeProduct']);

//Contracts
    Route::post('/delete_contract', [ContractsController::class, 'deleteContract']);

//Currencies
    Route::get('/get_currencies', [CurrenciesController::class, 'getCurrencies']);

//Categories
    Route::get("/get_categories", [CategoriesController::class, 'getCategories']);
    Route::post("/add_category", [CategoriesController::class, 'addCategory']);
    Route::post("/edit_category", [CategoriesController::class, 'editCategory']);
    Route::post("/delete_category", [CategoriesController::class, 'deleteCategory']);
    Route::post('/category_image_upload', [CategoriesController::class, 'imageUpload']);

//Countries
    Route::get('/get_countries', [CountryController::class, 'getCountries']);
    Route::post('/add_country', [CountryController::class, 'addCountry']);
    Route::post('/edit_country', [CountryController::class, 'editCountry']);
    Route::post('/delete_country', [CountryController::class, 'deleteCountry']);

//Brands
    Route::get('/get_brands', [BrandsController::class, 'getBrands']);
    Route::post('/add_brand', [BrandsController::class, 'addBrand']);
    Route::post('/edit_brand', [BrandsController::class, 'editBrand']);
    Route::post('/delete_brand', [BrandsController::class, 'deleteBrand']);

//Orders
    Route::get('/get_orders/{store_id}/{status?}', [OrdersController::class, 'getOrders']);
    Route::get('/get_order/{order_id}', [OrdersController::class, 'getOrder']);
    Route::post('/add_order', [OrdersController::class, 'addOrder']);
    Route::post('/update_order', [OrdersController::class, 'updateOrder']);
    Route::post('/delete_order', [OrdersController::class, 'deleteOrder']);
    Route::get('/get_client_orders/{client_id}', [OrdersController::class, 'getClientOrders']);
    Route::post('/assign_to_store', [OrdersController::class, 'assignToStore']);

//Order Details
    Route::get('/get_order_products/{order_id}/{status?}', [OrderDetailsController::class, 'getOrderProducts']);
    Route::get('/get_order_product/{order_id}/{product_id}', [OrderDetailsController::class, 'getOrderProduct']);
    Route::get('/get_total_price/{order_id}', [OrderDetailsController::class, 'getTotalPrice']);
    Route::post('/add_order_product', [OrderDetailsController::class, 'addOrderProduct']);
    Route::post('/edit_order_product', [OrderDetailsController::class, 'editOrderProduct']);
    Route::post('/delete_order_product', [OrderDetailsController::class, 'deleteOrderProduct']);

//Clients
    Route::get('/get_clients', [ClientsController::class, 'getClients']);
    Route::get('/get_client/{client_id}', [ClientsController::class, 'getClient']);
    Route::post('/edit_client', [ClientsController::class, 'editClient']);
    Route::post('/delete_client', [ClientsController::class, 'deleteClient']);

//Clients Notifications
    Route::get('/get_messages', [ClientsNotificationsController::class, 'getMessages']);
    Route::post('/delete_message', [ClientsNotificationsController::class, 'deleteMessage']);
    Route::post('/send_fcm_message', [ClientsNotificationsController::class, 'sendPushNotification']);


//Ads
    Route::get('/get_partners', [AdUsersController::class, 'getAdUsers']);
    Route::post('/add_partner', [AdUsersController::class, 'addPartner']);
    Route::post('/edit_partner', [AdUsersController::class, 'editPartner']);
    Route::post('/delete_partner', [AdUsersController::class, 'deletePartner']);
    Route::get('/get_partners_ads', [AdUsersController::class, 'getPartnersWithAds']);

    Route::post('/get_partner_ads', [AdsController::class, 'getAds']);
    Route::post('/get_partner_ad', [AdsController::class, 'getAd']);
    Route::post('/add_partner_ad', [AdsController::class, 'addAd']);
    Route::post('/edit_partner_ad', [AdsController::class, 'editAd']);
    Route::post('/delete_ad', [AdsController::class, 'deleteAd']);
    Route::post('/change_ad_status', [AdsController::class, 'changeStatus']);
    Route::post('/ad_picture_upload', [AdsController::class, 'adPictureUpload']);

//Users
    Route::post('/login', [UsersController::class, 'login']);
    Route::get('/get_users', [UsersController::class, 'getUsers']);
    Route::post('/add_user', [UsersController::class, 'addUser']);
    Route::post('/edit_user', [UsersController::class, 'editUser']);
    Route::post('/delete_user', [UsersController::class, 'deleteUser']);
    Route::get('/logged_user', [UsersController::class, 'loggedUser']);

    //Roles
    Route::get("/get_roles", [UsersController::class, 'getRoles']);
    Route::get("/get_role", [UsersController::class, 'getRole']);
    Route::post("/add_role", [UsersController::class, 'addRole']);
    Route::post("/edit_role", [UsersController::class, 'editRole']);
    Route::post("/delete_role", [UsersController::class, 'deleteRole']);
    Route::post("/assign_roles", [UsersController::class, 'assignRoles']);

//Cards
    Route::get('/get_cards', [CardsController::class, 'getCards']);
    Route::post('/add_card', [CardsController::class, 'addCard']);
    Route::post('/edit_card', [CardsController::class, 'editCard']);
    Route::post('/delete_card', [CardsController::class, 'deleteCard']);
    Route::get('/uuidd', [CardsController::class, 'uuidd']);

//Card types
    Route::get('/get_card_types', [CardTypesController::class, 'getCardTypes']);

//Settings
    Route::get('/get_settings', [SettingsController::class, 'getSettings']);
    Route::post('/update_settings_item', [SettingsController::class, 'updateSettingsItem']);

//Stores
    Route::get('/get_stores', [StoresController::class, 'getStores']);
    Route::get('/get_store/{id}', [StoresController::class, 'getStore']);
    Route::post('/add_store', [StoresController::class, 'addStore']);
    Route::post('/edit_store', [StoresController::class, 'editStore']);
    Route::post('/delete_store', [StoresController::class, 'deleteStore']);

});

//Route::middleware([AdminCheck::class])->group(function(){
//    Route::post('login', [UsersController::class, 'login']);
//});


Route::get('/logout', [UsersController::class, 'logout']);
Route::get('/', [UsersController::class, 'index']);
Route::any('/{any}', [UsersController::class, 'index'])->where('any', '(.*)');

//Route::get('/{any}', function() { return view('welcome'); })->where('any', '(.*)');
