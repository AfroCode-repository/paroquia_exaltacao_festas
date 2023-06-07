<?php

namespace App\Traits;

use App\Models\companies;
use App\Models\sys_language;
use App\Models\sys_log_update;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait SystemTrait {

    use ApiResponser;

    public function liberaGroupBySQL(){
        config()->set('database.connections.mysql.modes', [
            //'ONLY_FULL_GROUP_BY', // Disable this to allow grouping by one column
            'STRICT_TRANS_TABLES',
            'NO_ZERO_IN_DATE',
            'NO_ZERO_DATE',
            'ERROR_FOR_DIVISION_BY_ZERO',
            'NO_AUTO_CREATE_USER',
            'NO_ENGINE_SUBSTITUTION'
        ]);
    }

    public function validaSeCompanyAtribuida($id_company){
        if(0 == Auth::user()->id_company){
            return $this->error("Não Possui uma atribuição valida!");
        }else if($id_company != Auth::user()->id_company){
            return $this->error("Sua atribuição mudou, por favor atualize a pagina!");
        }else {
            return true;
        }
    }

    public function dataDefault(){

        $data['nome_sistema'] = '157º festa do divino';

        return $data;
    }//fim função

    public function addLogUpdates($id_tabela,$table_name,$field,$new_value,$old_value,$action,$obs){

        $log = new sys_log_update();
        $log->id_user = Auth::user()->id;
        $log->id_tabela = $id_tabela;
        $log->table_name = $table_name;
        $log->field = $field;
        $log->new_value = $new_value;
        $log->old_value = $old_value;
        $log->action = $action;
        $log->obs = $obs;
        $log->save();

        $validaLog = $this->verificaErroNaInsercao($log);

        return $validaLog;

    }//fim função

    public function verificaErroNaInsercao($tabela){
        if(false == $tabela){
            DB::rollBack();
            $this->error("Erro inesperado ao persistir dados! erro:0x748263");
        }else{
            return true;
        }
    }

    public function verificaCRUD($tabela,$tipo){
        if(true == $tabela){
            return true;
        }else{
            return false;
        }
    }//fim função

    public static function dateEmMysqlReverse($dateSql){
        $padrao = (1 == Auth::user()->id_language) ? null : Auth::user()->id_language ;

        $ano= trim(substr($dateSql, 0,4));
        $mes= substr($dateSql, 5,2);
        $dia= substr($dateSql, 8,2);

        if (null === $padrao) {
            return $mes."/".$dia."/".$ano;
        }else{
            return $dia."/".$mes."/".$ano;
        }
    }//fim função

    public function dateEmMysql($dateSql){
        $padrao = (1 == Auth::user()->id_language) ? null : Auth::user()->id_language ;

        if(null === $padrao){
            $ano= trim(substr($dateSql, 6,4));
            $dia= substr($dateSql, 3,2);
            $mes= substr($dateSql, 0,2);
            return $ano."-".$mes."-".$dia;
        }else{
            $ano= trim(substr($dateSql, 6,4));
            $mes= substr($dateSql, 3,2);
            $dia= substr($dateSql, 0,2);
            return $ano."-".$mes."-".$dia;
        }
    }//fim função

    public function dateEmMysqlSemHora($dateSql){
        $ano= trim(substr($dateSql, 6,4));
        $mes= substr($dateSql, 3,2);
        $dia= substr($dateSql, 0,2);
        return $ano."-".$mes."-".$dia;
    }//fim função

    public function dinheiroEmMysql($valor){
        $source = array('.', ',');
        $replace = array('', '.');
        return str_replace($source, $replace, $valor); //remove os pontos e substitui a virgula pelo ponto
    }//fim função

    public static function dinheiroEmMysqlReverse($valor){
        return number_format($valor,2,",",".");
    }//fim função

    public function listAtribuiStatusSettings()
    {
        $data =  DB::table('sys_status as s')
                ->selectRaw("s.code_status as status, s.description")
                ->where('s.tabela', DB::raw("'client_schedulings'"))
                ->where('s.field', DB::raw("'status_scheduling'"))
                ->whereIn('s.code_status', [1,2,3])
                ->orderByRaw('s.code_status ASC')
                ->get();

        return $data;
    }//fim função$data_modal['list_status_scheduling'] = DB::table('sys_status AS s')

    public function porcentagemEmMysqlReverse($valor){
        $valor = floatval ($valor);
        return number_format($valor,2,",",".");
    }//fim função

    public function calculaVariacaoPorcentural($valorAtual,$valorPassado){

        if(0 == $valorAtual){
            $resultado = 0;
        }else if(0 == $valorPassado){
            $resultado = 100;
        }else{
            $resultado = (($valorAtual - $valorPassado)/$valorPassado)*100;
        }

        return $this->porcentagemEmMysqlReverse($resultado);

    }//fim função

    public function textMes($mes, $fullName = null){
        $mes -= 1;

        if(1 == Auth::user()->id_language){
            if(null == $fullName){
                $text = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
            }else{
                $text = array("January","February","March","April","May","June","July","August","September","October","November","December");
            }
        }else{
            if(null == $fullName){
                $text = array("Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez");
            }else{
                $text = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
            }
        }
        return $text[$mes];
    }//fim função

    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }//fim função


}//fim classe

