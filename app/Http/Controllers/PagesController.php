<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    public function showHome()
    {
//        $data = $this->dataDefault();

        $data['active'] = 'home';
        $data['title_page'] = 'Home';

        return view('dashboard', $data);
        //return view('home.index', $data);
    }//fim showHome
}
