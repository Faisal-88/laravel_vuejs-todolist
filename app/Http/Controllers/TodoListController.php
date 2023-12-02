<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodoListController extends Controller
{
    //menampilkan data (Read) pada todolist
    public function getIndex() {
        return view('todolist');
     }
}
