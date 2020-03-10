<?php

use App\Imports\ContactsImport;
use Maatwebsite\Excel\Facades\Excel;
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class ImportController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = User::latest()->paginate(10);
        return view('contacts', compact('data'));
    }
}
