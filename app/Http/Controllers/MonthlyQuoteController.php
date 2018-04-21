<?php
namespace App\Http\Controllers;

use App\monthlyQuote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonthlyQuoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $monthlyQuotes = monthlyQuote::with('stock')->get();
//       $monthlyQuotes = monthlyQuote::all();
        //coloca o nome dos symbols em variavel propria
        foreach ($monthlyQuotes as &$monthlyQuote) {
            $monthlyQuote->stock_id = $monthlyQuote->stock->symbol;
            //depois retira o objeto de dentro do objeto
            unset($monthlyQuote->stock);
        }
        return view('monthlyQuotes.index')->with('monthlyQuotes', $monthlyQuotes);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            return view('monthlyQuotes.create');
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
    //public function store(Request $request)
    public function store(Request $request)
    {
        //
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $quote = $this->validate(request(), [
                        'symbol' => 'required|string|max:255|exists:stocks,symbol',
                    ]);
            //('/storequotemonthly/{symbol}', function ($symbol) {
            $symbol =  strtoupper($quote['symbol']);
            //testar se stock esta cadastrada no banco
            $stockid = DB::table('stocks')->where('symbol', $symbol)->value('id');
            //primeiro testa se tem algum registro, se nao houver insere todos os dados
            if (DB::table('monthly_quotes')->where('stock_id', $stockid)->doesntExist()) {
                $handle = fopen("https://www.alphavantage.co/query?function=TIME_SERIES_MONTHLY&symbol=" . $symbol . "&apikey=" . env('APLHAVANTAGE_APIKEY') . "&datatype=csv", 'r');
                //testa se o alpha vantage esta funcionando
                if ($handle !== false	&& is_resource($handle)) {
                    //esse comando exclui a primeira linha do csv
                    // 								if($handle) {
                    // 									//return redirect('home')->with('error', $handle);
                    // 									\Log::info('handle: '.$handle);
                    // 								}
                    // 								$validator = $this->validate(
                    // 																	[
                    // 																			'file'      => $handle,
                    // 																			'extension' => strtolower($handle->getExtension()),
                    // 																			//'extension' => strtolower($handle->getClientOriginalExtension()),
                    // 																	],
                    // 																	[
                    // 																			'file'          => 'required',
                    // 																			'extension'      => 'required|in:csv',
                    // 																	]
                    // 															);
                    fgetcsv($handle);
                    //esse comando exclui a segunda linha do csv, pois trata-se do dia de hoje, referente ao fechamento do mes atual. Os dados só consolidaram no final do mes.
                    fgetcsv($handle);
                    //while para cadastrar todos os dados
                    while (($data = fgetcsv($handle, 1000, ',')) !==false) {
                        $monthlyQuotes = new monthlyQuotes();
                        $monthlyQuotes->stock_id = $stockid;
                        $monthlyQuotes->timestamp = $data[0];
                        $monthlyQuotes->open = $data[1];
                        $monthlyQuotes->high = $data[2];
                        $monthlyQuotes->low = $data[3];
                        $monthlyQuotes->close = $data[4];
                        $monthlyQuotes->volume = $data[5];
                        $monthlyQuotes->save();
                    }
                    fclose($handle);
                } else {
                    //nao funcionou a importacao,problema no AV
                    return redirect('home')->with('error', 'Algo errado com o sitio AV');
                    // 							return response()->json([
        // 													'error' => 'Algo errado com o sitio AV'
        // 											], 200);
                }
                //retorna que deu tudo certo
                return redirect('home')->with('success', 'Dados inseridos no BD');
            // 								return response()->json([
        // 											'success' => 'Dados inseridos no BD'
        // 									], 200);
                        //caso exista mais de um registro, buscar se o possui registro do mes passado, para inserir somente este
            } elseif (DB::table('monthly_quotes')->where('stock_id', $stockid)->whereDate('timestamp', '>', Carbon::now()->subMonth(1))->doesntExist()) {


                            //esse comando exclui a primeira linha do csv
                fgetcsv($handle);
                //esse comando exclui a segunda linha do csv, pois trata-se do dia de hoje, referente ao fechamento do mes atual. Os dados só consolidaram no final do mes.
                fgetcsv($handle);
                //esse insere o fechamento do ultimo mes já concluido
                $data = fgetcsv($handle, 1000, ',');
                $monthlyQuotes = new monthlyQuotes();
                $monthlyQuotes->stock_id = $stockid;
                $monthlyQuotes->timestamp = $data[0];
                $monthlyQuotes->open = $data[1];
                $monthlyQuotes->high = $data[2];
                $monthlyQuotes->low = $data[3];
                $monthlyQuotes->close = $data[4];
                $monthlyQuotes->volume = $data[5];
                $monthlyQuotes->save();
                fclose($handle);
            } else {
                //neste caso ja existe registro do ultimo mes
                return redirect('home')->with('error', 'Dados já existentes na base');
                // 				return response()->json([
        //             'error' => 'Dados já existentes na base'
        //         ], 200);
            }
        } else {
            redirect('home')->with('error', 'Permissao invalida!');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\monthlyQuotes  $monthlyQuotes
     * @return \Illuminate\Http\Response
     */
    public function show(monthlyQuotes $monthlyQuotes)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\monthlyQuotes  $monthlyQuotes
     * @return \Illuminate\Http\Response
     */
    public function edit(monthlyQuotes $monthlyQuotes)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\monthlyQuotes  $monthlyQuotes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, monthlyQuotes $monthlyQuotes)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\monthlyQuotes  $monthlyQuotes
     * @return \Illuminate\Http\Response
     */
    public function destroy(monthlyQuotes $monthlyQuotes)
    {
        //
    }

    private function fileExists($file)
    {
        try {
            $client->head($file);
            return true;
        } catch (ConnectException $e) {
            // Connection exception; most likely host does not exist
            return false;
        }
    }
}
