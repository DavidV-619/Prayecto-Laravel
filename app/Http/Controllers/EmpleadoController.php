<?php

namespace App\Http\Controllers;

use App\Empleado;
use Illuminate\Http\Request;
use GuzzleHttp\Client as HttpClient;
use App\Http\Controllers\DB;


class EmpleadoController extends Controller
{
    public function __construct()
    {
        //Linea para agregar un Middleware a las funciones del controlador
        $this->middleware('auth');
        $this->middleware('authByName')->only('create','edit','destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empleados = Empleado::orderBy('id','DESC')->paginate(3);
        return view('Empleado.index',compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       /*  $tipomonedas = /DB::table('empleado')->select('tipo_moneda')->get();
        return view ('Empleado.create'); */

        $lstEstados = $this -> obtenerEstadosWS();
        return view('Empleado.create', compact('lstEstados'));
/* 
        $listMonedas = $this -> obtenerMonedas();
        return view('Empleado.create', compact('listMonedas'));
 */
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* dd($request->all()); */

        $this->validate($request,[
            'nombre' => 'required',
            'nombre' => 'required',
            'edad' => 'required',
            'puesto' => 'required',
            'tipomoneda'=> 'required',
            'sueldo'=> 'required',
            /* 'salario'=> 'required', */
            'estado'=> 'required']);

        $arrayUpdate =[
            'nombre' => $request->get("nombre"),
            'edad' => $request->get('edad'),
            'puesto' => $request->get('puesto'),
            'activo' => $request->has('activo') ? $request->get('activo') : 0,
            /* 'tipomoneda' => $request->get('tipomoneda'), */
            'sueldo' => $request->get('sueldo'),
            'salario' => $request->get('salario'),
            'estado' => $request->get('estado')
        ];

        Empleado::create($arrayUpdate);

        return redirect()->route('empleado.index')->with('success','Registro creado satisfactoriamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empleado = Empleado::find($id);
        return view('empleado.show',compact('empleado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       /*  $lstEstados = $this -> obtenerEstadosWS();
        return view('empleado.edit',compact('lstEstados')); */

        $empleado = Empleado::find($id);
        return view('empleado.edit',compact('empleado'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,['nombre' => 'required', 'nombre' => 'required','edad' => 'required','puesto' => 'required','activo' => 'required','salario'=> 'required']);
        Empleado::find($id)->update($request->all());

        return redirect()->route('empleado.index')->with('success','Registro actualizado satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        Empleado::find($id)->delete();
        return redirect()->route('empleado.index')->with('success','Registro eliminado satisfactoriamente'); 

    }

    private function obtenerEstadosWS(){

        $client = new HttpClient(['base_uri' => 'https://beta-bitoo-back.azurewebsites.net/api/']);
        $response = $client->request('POST',"proveedor/obtener/lista_estados");
        
        return json_decode($response->getBody())->data->lst_estado_proveedor;

    }

    /* private function obtenerMonedas(){
        $client = new HttpClient(['base_uri' => 'https://fx.currencysystem.com/webservices/CurrencyServer5.asmx']);
        $response = $client->request('POST',"/webservices/CurrencyServer5.asmx/AllCurrencies");
        dd((array)json_decode($response->getBody())->data->list_estado_provedor));
        dd((json_decode($response->getBody())->data->lst_estado_proveedor));
        return json_decode($response->getBody())->data->list_monedas;
        dd($response);
    } */


}
