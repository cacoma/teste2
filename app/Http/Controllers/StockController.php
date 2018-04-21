<?php
namespace App\Http\Controllers;

use App\stock;
use App\broker;
use App\User;
use App\invest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //se usuario estiver registrado, pode visualizar, caso nao, redirecionado para tela de login
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
        $stocks = Stock::all();
        return view('stocks.index')->with('stocks', $stocks);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        ////
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            return view('stocks.create');
        } else {
            return back()->with('error', 'Permissao invalida!');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //valida antes de dar o store
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $stock = $this->validate(request(), [
                        'symbol' => 'required|string|max:255|unique:stocks,symbol',
                        'type' => 'required|string|max:255',
                    ]);
            Stock::create([
            'symbol' => strtoupper($stock['symbol']),
                        'type' => strtoupper($stock['type']),
                        'created_at' => Carbon::now(),
            ]);
            //$req = Request::create('/api/storequotemonthly/'.strtoupper($stock['symbol']), 'GET');
            //$resp = Route::dispatch($req);
            //$res = app()->handle($req)->getData();
            //return redirect('stocks')->with('success', 'A ação foi adicionada.');
            return redirect()->action('MonthlyQuotesController@create', ['symbol' => strtoupper($stock['symbol'])]);
        } else {
            redirect('stocks')->with('error', 'Permissao invalida!');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $stock = Stock::findOrFail($id);
        return $stock;
    }

    public function detail($id)
    {
        //
        $stock = Stock::findOrFail($id);
        return view('stocks.detail', array('stock' => $stock));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $stock = Stock::findOrFail($id);
        return view('stocks.edit', compact('stock', 'id'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Auth::user();
        if ($user->role_id == '1') {
            $stockUpdate = Stock::find($id);
            $this->validate(request(), [
                        'symbol' => 'required|string|max:255',
                        'type' => 'required|string|max:255',
                        ]);
            $stockUpdate->symbol = strtoupper($request->get('symbol'));
            $stockUpdate->type = strtoupper($request->get('type'));
            $stockUpdate->save();
            return response()->json([
            'success' => 'Ação atualizada!'
        ], 200);
        // return redirect('users.index')->with('success','Usuario atualizado');
        } else {
            return response()->json([
            'error' => 'Permissao invalida'
        ], 200);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = Auth::user();
        if ($user->role_id == '1') {
            $stockDel = Stock::findOrFail($id);
            $stockDel->delete();
            return response()->json([
            'success' => 'Ação deletada!'
                ], 200);
        // return redirect('users.index')->with('success','Usuario atualizado');
        } else {
            return response()->json([
                                'error' => 'Permissao invalida'
                                ], 200);
        }
    }

    //deste ponto em diante sao metodos referentes ao armazenamento de investimentos em acoes, nao de acoes em si
    public function investstore(Request $request)
    {
        //insere o invest do tipo stock
        //transforma os valores para valores de banco de dados (virgulas e pontos)
        $request = $this->formatcurrencytodb($request);
        $userid = $request->user()->id;
        $invests = $this->validate(request(), [
                        'symbol' => 'required|string|max:255|exists:stocks,symbol',
                        'quant' => 'required|numeric|min:1',
                        'price' => 'required|numeric|min:0.0001',
                        'broker_fee' => 'required|numeric|min:0',
                        'date_invest' => 'required|before:tomorrow',
                        'broker_name' => 'required|exists:brokers,name',
                    ]);
        $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
        //$brokerid = $request->broker()->id;
        $stockid = DB::table('stocks')->where('symbol', $request->symbol)->value('id');
        Invest::create([
                        'type' => 'stock',
            'symbol' => strtoupper($invests['symbol']),
                        'quant' => $invests['quant'],
                        'price' => $invests['price'],
                        'broker_fee' => $invests['broker_fee'],
                        'date_invest' => new Carbon($invests['date_invest']),
                        'user_id' => $userid,
                        'stock_id' => $stockid,
                        'broker_id' => $brokerid,
            ]);
        return redirect('invests')->with('success', 'O investimento foi atualizado.');
    }

    //metodo para transformar os valores da tela em valores de bd (virgulas e pontos) da corretagem e preco da acao
    private function formatcurrencytodb($request)
    {
        //retira os pontos e substitui a virgula por pontos
        $request['price'] = strtr($request['price'], array('.' => '', ',' => '.'));
        $request['broker_fee'] = strtr($request['broker_fee'], array('.' => '', ',' => '.'));

        //transforma em float
        $request['price'] = floatval($request['price']);
        $request['broker_fee'] = floatval($request['broker_fee']);

        //retornar valor
        return $request;
    }

    public function investedit($id)
    {
        //abre a tela onde vai ser feita a edicao
        $invest = invest::findOrFail($id);
        return view('stocks.stockinvestedit', compact('invest', 'id'));
        //return redirect('invests')->with('success', 'Foi ao lugar certo.');
    }

    public function investupdate(Request $request)
    {
        //primeiro ajusta os valores para formato de bando de dados, depois acha o investimento entao valida os dados e por fim faz o store
        $request = $this->formatcurrencytodb($request);
        //acha o investimento
        $investUpdate = Invest::findOrFail($request->id);
        //pega o id do user
        $userid = $request->user()->id;
        //valida as informacoes
        $this->validate(request(), [
                        'symbol' => 'required|string|max:255|exists:stocks,symbol',
                        'quant' => 'required|numeric|min:1',
                        'price' => 'required|numeric|min:0.0001',
                        'broker_fee' => 'required|numeric|min:0',
                        'date_invest' => 'required|before:tomorrow',
                        'broker_name' => 'required|exists:brokers,name',
                    ]);
        //pega info de broker e stock id
        $brokerid = DB::table('brokers')->where('name', $request->broker_name)->value('id');
        //$brokerid = $request->broker()->id;
        $stockid = DB::table('stocks')->where('symbol', $request->symbol)->value('id');

        //atualiza BD
        $investUpdate->type = 'stock';
        $investUpdate->symbol = strtoupper($request->get('symbol'));
        $investUpdate->quant = $request->get('quant');
        $investUpdate->price = $request->get('price');
        $investUpdate->broker_fee = $request->get('broker_fee');
        $investUpdate->date_invest = new Carbon($request->get('date_invest'));
        $investUpdate->user_id = $userid;
        $investUpdate->stock_id = $stockid;
        $investUpdate->broker_id = $brokerid;
        $investUpdate->save();

        //retorna com sucesso
        return redirect('invests')->with('success', 'O investimento foi atualizado.');
    }
}
