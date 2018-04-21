<?php
namespace App\Http\Controllers;

use App\broker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BrokerController extends Controller
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
        $brokers = Broker::all();
        return view('brokers.index')->with('brokers', $brokers);
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
            return view('brokers.create');
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
        //
        //$request = $this->formatcnpj($request);
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $broker = $this->validate(request(), [
                        'name' => 'required|string|max:255',
                        'cnpj' => 'required|string|max:18',
                    ]);
            Broker::create([
            'name' => $broker['name'],
                        'cnpj' => $broker['cnpj'],
                        'created_at' => Carbon::now(),
            ]);
            return redirect('brokers')->with('success', 'A corretora foi adicionada.');
        } else {
            return back()->with('error', 'Permissao invalida!');
        }
    }

    private function formatcnpj($request)
    {
        // nao uso mais

        $request['cnpj'] = (int)preg_replace("/[^0-9,.]/", "", $request['cnpj']);
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\broker  $broker
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $broker = Broker::findOrFail($id);
        return $broker;
    }

    public function detail($id)
    {
        //
        $broker = Broker::findOrFail($id);
        return view('brokers.detail', array('broker' => $broker));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\broker  $broker
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $broker = Broker::findOrFail($id);
        return view('brokers.edit', compact('broker', 'id'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\broker  $broker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $brokerUpdate = Broker::find($id);
            $this->validate(request(), [
                        'name' => 'required|string|max:255',
                        'cnpj' => 'required|string|max:18',
                        ]);
            $brokerUpdate->name = $request->get('name');
            $brokerUpdate->cnpj = $request->get('cnpj');
            $brokerUpdate->save();
            return response()->json([
            'success' => 'Corretora atualizada!'
        ], 200);
        // return redirect('users.index')->with('success','Usuario atualizado');
        } else {
            return response()->json([
            'error' => 'Permissao invalida'
        ], 200);
            // return redirect('users.index')->with('error','Nao foi possivel atualizar o usuario.');
        }
        //return redirect('brokers')->with('success','Corretora atualizada');
                //}else{
                //	return back()->with('error', 'O broker nao pode ser adicionado');
                //}
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\broker  $broker
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = Auth::user();
        //dono e admin somente podem alterar
        if ($user->role_id == '1') {
            $brokerDel = Broker::findOrFail($id);
            $brokerDel->delete();
            return response()->json([
            'success' => 'Corretora deletada!'
                ], 200);
        // return redirect('users.index')->with('success','Usuario atualizado');
        } else {
            return response()->json([
                                'error' => 'Permissao invalida'
                                ], 200);
        }
    }
}
