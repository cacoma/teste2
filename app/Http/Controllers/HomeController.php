<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\monthlyQuote;
use App\invest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
//         $names = new \stdClass;
//         $datas = new \stdClass;
         $pie = new \stdClass;
        $counter = 0;
        // ainda nao  if ($user->role_id == '1') {
        //pega todos os investimentos do usuario para chart de pie (pizza)
        $invests = Invest::with('stock')->where('user_id', $user->id)->get();
        //pega todos os investimentos AGRUPADOS do usuario para chart de pie (pizza)
        $results = Invest::groupBy('stock_id')->with('stock')
          ->selectRaw('sum(total) as total,sum(quant) as quant, avg(price) as avgprice, stock_id')
          ->where('user_id', $user->id)->get();
        //retorna a cotação de certa acao
      
        $monthlyQuotes = monthlyQuote::with('stock')
          //->selectRaw('timestamp, close')
            ->where('stock_id', '<', '4')
                ->whereDate('timestamp', '>', Carbon::now()->subMonth(12))
                      ->orderBy('timestamp', 'asc')->get();
        
       $forCharts = Invest::groupBy('stock_id')->with('stock')
          ->selectRaw('stock_id')
          ->where('user_id', $user->id)->get();
      
      
      
      foreach ($forCharts as &$forChart) {
            //arruma o nome
            $thedatas = array();
            $tempTS = array();
            $tempCS = array();

            $forChart->name = $forChart->stock->symbol;
            //depois retira o objeto de dentro do objeto
            unset($forChart->stock);
            
            
//             //funcao para gerar uma cor diferente para cada id diferente
//             $hash = md5('cor' . $monthlyQuote->stock_id);
//             $r = hexdec(substr($hash, 0, 2));
//             $g = hexdec(substr($hash, 2, 2));
//             $b = hexdec(substr($hash, 4, 2));
//             $a = hexdec(substr($hash, 6, 2));
//             $monthlyQuote->color = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $a . ')';
          $forChart->temp = monthlyQuote::where('stock_id', '=', $forChart->stock_id)
              ->selectRaw('timestamp, close')
                ->whereDate('timestamp', '>', Carbon::now()->subMonth(12))
                      ->orderBy('timestamp', 'asc')->get();
         
//           $forChart->temp->toArray();
//           $forChart->data = $forChart->temp->mapWithKeys(function ($item) {
//                           return [$item['timestamp'] => $item['close']];
//                       });
      // for ($i = 0; $i < count($forCharts); $i++){
        //$i = 0;
        
        foreach($forChart->temp as &$value){
            array_push($tempTS,$value->timestamp);
            array_push($tempCS, $value->close);
            //$tempTS = $value->timestamp;
            //$tempCS = $value->close;
            //$insert = array((string)$tempTS => (string)$tempCS);
            //$insert[$tempTS] = $tempCS;
            //$routeObject = $forChart;
            //array_push($forChart->data, array((string)$tempTS => (string)$tempCS));
            //array_merge($thedatas, array((string)$tempTS => (string)$tempCS));
            //$forChart->data['$i'] = $value->close;
          //$i++;
          }
     //   }
        $forChart->data = array_combine($tempTS,$tempCS);
        //$obj_merged = (object) array_merge((array) $obj1, (array) $obj2);
//           ->mapToGroups(function ($item, $key) {
//                             return [$item['timestamp'] => $item['close']];
//                         });

           unset($forChart->stock_id);
        }

      
//       for ($i = 0; $i < count($forCharts); $i++){
//       for ($j = 0; $j < count($forCharts[$i]); $j++){
//           foreach ($forCharts[$i]->$data[$j] as $value){//for (var $prop in $forcharts[$i]->$data[$j]) {
//             $tmstmp = $value->$timestamp;
//             $value[$tmstmp] = $value->close;
//             unset ($value->timestamp);
//     } forcharts[array 12]
//     }            data:
                            
//     }

//    foreach ($forCharts as $value2){ //abre a primeira array
//          foreach ($value2->data as $value1){ //abre o objeto de cada array
//           //foreach ($value1 as $value){ //abre o array onde estao os dados
//             $tmstmp = $value1->timestamp;
//             $value1[$tmstmp] = $value1->close;
//             //unset ($value1->timestamp);
//    // }
//     }
//     }
      
        
        
        foreach ($monthlyQuotes as $monthlyQuote) {
            //arruma o nome

            $monthlyQuote->stockName = $monthlyQuote->stock->symbol;
            //depois retira o objeto de dentro do objeto
            unset($monthlyQuote->stock);
            //funcao para gerar uma cor diferente para cada id diferente
            $hash = md5('cor' . $monthlyQuote->stock_id);
            $r = hexdec(substr($hash, 0, 2));
            $g = hexdec(substr($hash, 2, 2));
            $b = hexdec(substr($hash, 4, 2));
            $a = hexdec(substr($hash, 6, 2));
            $monthlyQuote->color = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $a . ')';
            
          
           
        }
        
       //foreach ($names as $name){
       //}
        
         //array_push($forChart, $name);
      
      
//           DB::table('invests')
//                 ->groupBy('stock_id')
//                 ->where('user_id', $user->id)
//                 ->get();
        //ajusta os dados

//         foreach ($invests as $key => $value) {
//             // retira o objeto, pegando valor antes
//             $value->stockName = $value->stock->symbol;
//             //retira o objeto de dentro do objeto, para renderizar corretamente
//             unset($value->stock);
//             if ($value->type == 'stock') {
//                 $value->type = 'Ação';
//             }
//             //funcao para gerar uma cor diferente para cada id diferente
//             $hash = md5('cor' . $value->stock_id);
//             $r = hexdec(substr($hash, 0, 2));
//             $g = hexdec(substr($hash, 2, 2));
//             $b = hexdec(substr($hash, 4, 2));
//             $a = hexdec(substr($hash, 6, 2));
//             $value->color = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $a . ')';

//           //para pegar o retorno por investimento feito
//           //if (!in_array($value->symbol, $symbols)) {
//            //$symbols[] = $value->stockName;
//          //     }
//         }
        //foreach para tratar invests
        foreach ($invests as &$invest) {
            // retira o objeto, pegando valor antes
            $invest->stockName = $invest->stock->symbol;
            //retira o objeto de dentro do objeto, para renderizar corretamente
            unset($invest->stock);
            if ($invest->type == 'stock') {
                $invest->type = 'Ação';
            }
            //funcao para gerar uma cor diferente para cada id diferente
            $hash = md5('cor' . $invest->stock_id);
            $r = hexdec(substr($hash, 0, 2));
            $g = hexdec(substr($hash, 2, 2));
            $b = hexdec(substr($hash, 4, 2));
            $a = hexdec(substr($hash, 6, 2));
            $invest->color = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $a . ')';

            //para pegar o retorno por investimento feito
          //if (!in_array($value->symbol, $symbols)) {
           //$symbols = $invest->stockName;
         //     }
        }
         $tempTS = array();
         $tempCS = array();
        //foreach para tratar results
        foreach ($results as &$result) {

            //pega as ultimas cotacoes mensais da acao
            $result->lastQuote = monthlyQuote::where('stock_id', '=', $result->stock_id)
                ->whereDate('timestamp', '>', Carbon::now()->subMonth(2))
                      ->orderBy('timestamp', 'asc')->get();
            //pega a ultima ultima (caso nao tenha atualizado por ultimo)
            $result->quote = $result->lastQuote[0]->close;
            //retira objeto nao necessario
            unset($result->lastQuote);
            //faz o calculo do total atualizado
            $result->totalUpdated = floatval(preg_replace("/[^-0-9\.]/", "", $result->quote)) * floatval(preg_replace("/[^-0-9\.]/", "", $result->quant));
            //faz o calculo de % de ganho ou perda
            $result->percentage = floatval(preg_replace("/[^-0-9\.]/", "", $result->totalUpdated)) / floatval(preg_replace("/[^-0-9\.]/", "", $result->total)) - 1;
            //trata o nome
            $result->stockName = $result->stock->symbol;
            //retira o objeto nao mais necessário
            unset($result->stock);
            //funcao para gerar uma cor diferente para cada id diferente
            $hash = md5('cor' . $result->stock_id);
            $r = hexdec(substr($hash, 0, 2));
            $g = hexdec(substr($hash, 2, 2));
            $b = hexdec(substr($hash, 4, 2));
            $a = hexdec(substr($hash, 6, 2));
            $result->color = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $a . ')';
          
          //foreach($result as &$value){
            array_push($tempTS, $result->stockName);
            array_push($tempCS, $result->total);
          //}
        }
      $pie = array_combine($tempTS,$tempCS);
        return view('home')->with('monthlyQuotes', $monthlyQuotes)->with('invests', $invests)->with('results', $results)
                        ->with('forCharts', $forCharts)->with('pie',$pie);
        //return view('home')->with('symbols', $symbols)->with('invests', $invests);
    }
}
