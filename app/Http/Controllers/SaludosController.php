<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;

class SaludosController extends Controller
{
    public function index($name)
    {
        $cliente = new Cliente;
        $cliente->name = $name;
        $cliente->save();
        return view('welcome', compact('name'));
    }

    public function verTodos()
    {
        $clientes = Cliente::all();
        return view('verTodos', ['clientes' => $clientes]);
    }
}
