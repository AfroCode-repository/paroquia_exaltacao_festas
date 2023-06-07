<?php

namespace App\Traits;

use App\Models\client_scheduling;
use App\Models\client_scheduling_invoices;
use App\Models\employer;
use App\Models\region;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait PagesTrait {

    public function tableListaEmployer()
    {
        $data['ListaEmployer'] = employer::all();

        return (string) view('employer.components.tables.tableListaEmployer', $data);

    }//fim função

    public function tableListaUsers()
    {
        $data = DB::table('users AS u')
        ->join('sys_groups as sg', function($join)
            {
                $join->on('sg.id', '=', 'u.id_group');
            })
        ->join('sys_languages as sl', function($join)
            {
                $join->on('sl.id', '=', 'u.id_language');
            })
        ->leftjoin('companies as c', function($join)
            {
                $join->on('c.id', '=', 'u.id_company');
            })
        ->select('u.id as id_user',
                'u.name',
                'u.email',
                'sg.name as group',
                'sl.code as language',
                'c.name as company',
                'u.status')
        ->where('sg.id','>=',  Auth::user()->id_group)
        ->get();

        $view = (string) view('admin.users.components.tables.tableUsers')->with('users', $data);
        return $view;
    }//fim função

    public function tableListaRegions()
    {
        $data = region::orderby('status','desc')->orderby('name','asc')->get();

        $view = (string) view('admin.regions.tables.tableRegions')->with('regions', $data);
        return $view;
    }//fim função

    public function ganhos_total_ano($data)
    {
        $sql = client_scheduling_invoices::selectRaw("sum(price_total) as price")
        ->whereRaw("status = 2 and DATE_FORMAT(completed_date, '%Y') = {$data}")
        ->get();

        return ($sql) ? $sql[0]['price'] : '0.00' ;
    }//fim classe

    public function ganhos_total_mes($data)
    {
        $sql = client_scheduling_invoices::selectRaw("sum(price_total) as price")
        ->whereRaw("status = 2 and DATE_FORMAT(completed_date, '%Y-%m') = '{$data}'")
        ->get();

        return ($sql) ? $sql[0]['price'] : '70.00' ;
    }//fim classe

    public function ganhos_pendentes()
    {
        $sql = client_scheduling_invoices::selectRaw("sum(price_total) as price")
        ->where("status", 1)
        ->get();

        return ($sql) ? $sql[0]['price'] : '0.00' ;
    }//fim classe

    public function agendamento_pendente_pgto()
    {
        $sql = client_scheduling_invoices::selectRaw("count(*) as qtd")
        ->where("status", 1)
        ->get();

        return ($sql) ? $sql[0]['qtd'] : '0' ;
    }//fim classe

    public function agendamento_pendente()
    {
        $sql = client_scheduling::selectRaw("count(*) as qtd")
        ->where("status_scheduling", 1)
        ->where("status", '!=' , 0)
        ->whereNull('completed_date')
        ->get();

        $data['qtd']  = ($sql) ? $sql[0]['qtd'] : '0' ;

        $sql = DB::table('client_schedulings AS cs')
        ->join('client_service_schedulings as css', function($join)
            {
                $join->on('cs.id', '=', 'css.id_client_scheduling');
            })
        ->selectRaw("sum(css.price) as price")
        ->where("cs.status_scheduling", 1)
        ->where("cs.status", '!=' , 0)
        ->whereNull('cs.completed_date')
        ->get();

        foreach ($sql as $s) {
            $data['price'] = $s->price;
        }
        return $data;
    }//fim classe

    public function graficoHomeAgendamento()
    {
        $sql = client_scheduling::selectRaw("DATE_FORMAT( schedule_date, '%m' ) AS mes, COUNT(id) value")
        ->where("status", 1)
        ->whereRaw("DATE_FORMAT(schedule_date, '%m') BETWEEN DATE_FORMAT( SUBDATE(CURDATE(),INTERVAL 5 MONTH) , '%m' ) and DATE_FORMAT( CURDATE(), '%m')")
        ->groupByRaw("mes")
        ->orderByRaw("DATE_FORMAT(schedule_date, '%m') ASC")
        ->limit(6)
        ->get();

        return $sql;
    }//fim função

    public function graficoHomeConcluido()
    {
        $sql = client_scheduling::selectRaw("DATE_FORMAT( schedule_date, '%m' ) AS mes, COUNT(id) value")
        ->where("status_scheduling", 6)
        ->whereNotNull("completed_date")
        ->whereRaw("DATE_FORMAT(schedule_date, '%m') BETWEEN DATE_FORMAT( SUBDATE(CURDATE(),INTERVAL 5 MONTH) , '%m' ) and DATE_FORMAT( CURDATE(), '%m')")
        ->groupByRaw("mes")
        ->orderByRaw("DATE_FORMAT(schedule_date, '%m') ASC")
        ->limit(6)
        ->get();

        return $sql;

    }//fim função

    public function graficoHomeInvoicesPagos()
    {
        $sql = client_scheduling_invoices::selectRaw("date_format(completed_date, '%m') as mes, sum(price_total) as value")
        ->where("status", 2)
        ->whereRaw("DATE_FORMAT(completed_date, '%m') BETWEEN DATE_FORMAT( SUBDATE(CURDATE(),INTERVAL 5 MONTH) , '%m' ) and DATE_FORMAT( CURDATE(), '%m')")
        ->groupByRaw("mes")
        ->orderByRaw("DATE_FORMAT(completed_date, '%m') ASC")
        ->limit(6)
        ->get();

        return $sql;
    }//fim função

    public function auxGraficosAgendamentos($listCategories,$data)
    {
        /**********************************************************************************************
         ** essa função relaciona os agendamentos com os outros tipos de graficos, cruzando os meses **
         ** para deixar o grafico com as informações corretas                                        **
         *********************************************************************************************/
        $listData = [];
        $listValueData = [];
        $valueData = [];
        $auxData = array();
        $auxCategories = 0;

        foreach ($data as $ghc) {
            $listData[$auxCategories] = $ghc->mes;
            $listValueData[$auxCategories] = $ghc->value;
            $auxCategories++;
        }

        for ($i=0; $i < count($listCategories); $i++) {

            for ($j=0; $j < count($listData) ; $j++) {
                if ($listData[$j] == $listCategories[$i]) {
                    $valueData[$i] = $listValueData[$j];
                    $auxData[] = $i;
                }else{
                    if (!in_array($i, $auxData)){
                        $valueData[$i] = 0;
                    }
                }
            }
        }

        $txtData = "[";

        foreach ($valueData as $qc) {
            $txtData .= "{$qc},";
        }

        return  rtrim($txtData,',')."]";
    }//fim função

}//fim classe
