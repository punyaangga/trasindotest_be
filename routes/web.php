<?php

use Illuminate\Support\Facades\Route;
use App\Events\SendGlobalNotification;

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

Route::get('/', function () {
    // return view('welcome');
    $data = ['message' => 'Server Prod Baru Berjalan Dengan Baik. Its Work. Test'];
    return response()->json($data);
});
Route::get('/testNotif', function () {
    return view('welcome');
});
// Route::get('send-notif/{name}', function ($name) {
//     event(new SendGlobalNotification($name));
//     return "Event has been sent!";
// });
Route::get('log-viewers', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
// Route::get('/test', function () {
//     $data = ['message' => 'Api Jalan Ya, Server juga nyala'];
//     return response()->json($data);
// });
