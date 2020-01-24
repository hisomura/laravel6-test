<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::prefix('outside')->group(function () {
    Route::view('login', 'outside.login');
    Route::post('oauthLogin', function (Request $request) {
        $state = \Illuminate\Support\Str::random(40);
        $request->session()->put('state', $state);
        $query = http_build_query([

            'client_id' => config('oauth.client_id'),
            'redirect_uri' => config('oauth.redirect_url'),
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
        ]);

        return redirect('http://' . config('oauth.server') . '/oauth/authorize?' . $query);
    })->name('outside.oauthLogin');

    Route::get('/callback', function (Request $request) {
        $state = $request->session()->get('state');
        throw_unless(
            strlen($state) > 0 && $state === $request->get('state'),
            InvalidArgumentException::class
        );

        $http = new GuzzleHttp\Client;
        $params = [
            'grant_type' => 'authorization_code',
            'client_id' => config('oauth.client_id'),
            'client_secret' => config('oauth.client_secret'),
            'redirect_uri' => config('oauth.redirect_url'),
            'code' => $request->get('code'),
        ];
        $response = $http->post('http://localhost/oauth/token', [
            'form_params' => $params,
        ]);

        $secrets = json_decode((string)$response->getBody(), true);
        $request->session()->put('secrets', $secrets);

        return redirect(route('outside.user'));
    });

    Route::get('user', function (Request $request) {
        $secrets = $request->session()->get('secrets');

        $accessToken = $secrets['access_token'];
        $client = new \GuzzleHttp\Client();
        $uri = 'http://' . config('oauth.server') . '/api/user';
        $response = $client->request('GET', $uri, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);
        $data = json_decode((string)$response->getBody(), true);

        return "name: {$data['name']}, email: {$data['email']}";
    })->name('outside.user');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
