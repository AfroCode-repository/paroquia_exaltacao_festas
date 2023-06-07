<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait ControlGroup {

    public static function grupoAdminDev($sePagina = null){
        if(1 != Auth::user()->id_grupo){
            return ($sePagina) ? false : abort(404);
        }else{
            return true;
        }
    }

    public static function grupoAdminMaster($sePagina = null){
        if(!(2 >= Auth::user()->id_grupo)){
            return ($sePagina) ? false : abort(404);
        }else{
            return true;
        }
    }

    public static function grupoAdminLocal($sePagina = null){
        if(!(3 >= Auth::user()->id_grupo)){
            return ($sePagina) ? false : abort(404);
        }else{
            return true;
        }
    }

    public static function grupoDirigenteFichas($sePagina = null){
        if(!(4 >= Auth::user()->id_grupo)){
            return ($sePagina) ? false : abort(404);
        }else{
            return true;
        }
    }

    public static function grupoDirigenteFinancas($sePagina = null){

        if(!(Auth::user()->id_grupo <= 5 && 4 != Auth::user()->id_grupo)){
            return ($sePagina) ? false : abort(404);
        }else{
            return true;
        }
    }

    public static function grupoAdministrativo($sePagina = null){
        if(!(5 >= Auth::user()->id_grupo)){
            return ($sePagina) ? false : abort(404);
        }else{
            return true;
        }
    }

    public static function _adminUserButaoAcao(){

    }
}//fim classe

