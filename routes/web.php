<?php

use App\Models\User;
use App\Notifications\PushMessageBrowser;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
// Route::view('/', 'welcome');
Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('/push/subscribe', function (Request $request) {
    $user = auth()->user(); // atau ambil User::find(1) untuk contoh statis
// echo json_encode($request->all());exit;
    $user->updatePushSubscription(
        $request->input('endpoint'),
        $request->input('keys.p256dh'),
        $request->input('keys.auth'),
        $request->input('contentEncoding', null)
    );

    return response()->json(['success' => true]);
})
->middleware(['auth']);

Route::get('/push', function (Request $request) {
    $receiver = User::find(1);
    $receiver->notify(new PushMessageBrowser($receiver->id, 'https://avatar.iran.liara.run/public/24', 'Test Notification', 'This is a test notification from the browser!'));
    // Notification::send(User::all(),new PushMessageBrowser());
    return redirect()->back();
})
->middleware(['auth'])
->name('push');
// Broadcast::routes();


Route::get('/chat/{uid}', function (Request $request, $uid) {
    session()->flash('openChatUid', $uid);
    return redirect()->route('dashboard');

})
->middleware(['auth'])
->name('push');

// Broadcast::routes(['middleware' => ['auth']]); 
// Broadcast::routes(['middleware' => ['auth:api']]);

require __DIR__.'/auth.php';
