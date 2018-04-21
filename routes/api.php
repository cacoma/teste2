<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\monthlyQuote;
use App\invest;
use Carbon\Carbon;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/getintchart', function() {
  $query = Input::get('query');
        $monthlyQuotes = monthlyQuote::with('stock')->where('stock_id', '=', $query)
          ->whereDate('timestamp', '>', Carbon::now()->subMonth(12))->orderBy('timestamp', 'asc')->get();
        foreach ($monthlyQuotes as &$monthlyQuote) {
            //arruma o nome
            $monthlyQuote->stock_id = $monthlyQuote->stock->symbol;
            //depois retira o objeto de dentro do objeto
            unset($monthlyQuote->stock);
            //ajusta as datas pro formato certo
        }
return response()->json($monthlyQuotes);
});
 