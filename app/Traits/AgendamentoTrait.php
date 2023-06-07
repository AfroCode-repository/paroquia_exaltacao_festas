<?php

namespace App\Traits;

use App\Models\client_scheduling_obs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait AgendamentoTrait {

    public function tableAgendamentosVigentes($id_client = null)
    {
        $data['agendamentos'] = DB::table('clients AS c')
        ->join('client_schedulings AS cs', function($join)
        {
            $join->on('cs.id_client', '=', 'c.id');
        })
        ->join('sys_status AS ss', function($join)
        {
            $join->on('ss.tabela', '=', DB::raw("'client_schedulings'"));
            $join->on('ss.field', '=', DB::raw("'status_scheduling'"));
            $join->on('ss.code_status', '=', 'cs.status_scheduling');
        })
        ->leftjoin('client_service_schedulings AS css', function($join)
        {
            $join->on('css.id_client_scheduling', '=', 'cs.id');
            $join->on('css.status', '=', DB::raw("'1'"));
        })
        ->join('client_services AS cserv', function($join)
        {
            $join->on('cserv.id', '=', 'css.id_client_service');
        })
        ->join('services AS s', function($join)
        {
            $join->on('s.id', '=', 'cserv.id_service');
        })
        ->selectRaw("cs.id, CONCAT(c.name,' ',c.register) as client, cs.name_manager, cs.schedule_date, cs.schedule_date_end, cs.work_days, ss.description, GROUP_CONCAT(s.name) as services, sum(css.price) as price, cs.status")
        ->groupBy('cs.id')
        ->where('cs.status',1)
        ->whereNotIn('cs.status_scheduling', [6])
        ->whereNull('completed_date')
        ->where(function($query) use ($id_client)   {
            if (null != $id_client) {
                $query->where('c.id','=',$id_client);
            }
        })
        ->orderByRaw('cs.schedule_date ASC')
        ->get();

        return (string) view('client.dados.components.tables.tableAgendamentosVigentes', $data);
    }//fim função

    public function tableAgendamentosHistorico($id_client){

        $data['agendamentos'] = DB::table('clients AS c')
        ->join('client_schedulings AS cs', function($join)
        {
            $join->on('cs.id_client', '=', 'c.id');
            //$join->on('cs.status', '=', DB::raw("'1'"));
        })
        ->join('sys_status AS ss', function($join)
        {
            $join->on('ss.tabela', '=', DB::raw("'client_schedulings'"));
            $join->on('ss.field', '=', DB::raw("'status_scheduling'"));
            $join->on('ss.code_status', '=', 'cs.status_scheduling');
        })
        ->leftjoin('client_service_schedulings AS css', function($join)
        {
            $join->on('css.id_client_scheduling', '=', 'cs.id');
            $join->on('css.status', '=', DB::raw("'1'"));
        })
        ->join('client_services AS cserv', function($join)
        {
            $join->on('cserv.id', '=', 'css.id_client_service');
        })
        ->join('services AS s', function($join)
        {
            $join->on('s.id', '=', 'cserv.id_service');
        })
        ->selectRaw("cs.id, CONCAT(c.name,' ',c.register) as client, cs.name_manager, cs.schedule_date, cs.schedule_date_end, cs.work_days, ss.description, GROUP_CONCAT(s.name) as services, sum(css.price) as price, cs.status")
        ->groupBy('cs.id')
        ->where('cs.status_scheduling',6)
        ->where('cs.status',1)
        ->whereNotNull('cs.completed_date')
        ->where(function($query) use ($id_client)   {
            if (null != $id_client) {
                $query->where('c.id','=',$id_client);
            }
        })
        ->orWhere('cs.status',0)
        ->orderByRaw('cs.status DESC, cs.completed_date DESC, cs.status_scheduling DESC')
        ->get();

        return (string) view('client.dados.components.tables.tableAgendamentosHistoricos', $data);
    }//fim função

    public static function calculaStatusTextAgendamento($scheduleDateEnd)
    {
        $today = date("Y-m-d");

        if($today <= $scheduleDateEnd){
            $data['tipo_status'] = "success";
            $data['text_status'] = "Dentro do Prazo";
        }else{
            $data['tipo_status'] = "danger";
            $data['text_status'] = "Atrasado";
        }

        return $data;
    }//fim função

    public function tableClientServiceScheduling($id_scheduling)
    {
        $data['list_services'] = DB::table('clients AS c')
                            ->join('client_schedulings AS cl', function($join)
                            {
                                $join->on('cl.id_client', '=', 'c.id');
                            })
                            ->join('client_service_schedulings AS css', function($join)
                            {
                                $join->on('css.id_client_scheduling', '=', 'cl.id');
                            })
                            ->join('client_services AS cs', function($join)
                            {
                                $join->on('css.id_client_service', '=', 'cs.id');
                                $join->on('css.status', '=', DB::raw("'1'"));
                            })
                            ->join('services AS s', function($join)
                            {
                                $join->on('cs.id_service', '=', 's.id');
                            })
                            ->join('employers AS e', function($join)
                            {
                                $join->on('css.id_employer', '=', 'e.id');
                            })
                            ->selectRaw("cl.id_client, cl.id as id_scheduling, css.id as id_client_service_schedulings, s.name as service, css.price, e.name as employer")
                            ->where('css.id_client_scheduling',$id_scheduling)
                            ->get();

        $totalPrice = 0;

        foreach ($data['list_services'] as $d) {
            $totalPrice += $d->price;
        }

        $data['totalPrice'] = $totalPrice;

        return (string) view('helpers.agendamentoControl.tableClientServicesScheduling', $data);
    }//fim função

    public function htmlTableAgendamentoObs($id)
    {
        $ListaAgendamentoObs = client_scheduling_obs::where('id_client_scheduling', $id)->get();

        $data = (string) view('client.dados.components.tables.tableAgendamentoObs')->with('ListaAgendamentoObs',$ListaAgendamentoObs);

        return $data;
    }//fim função

    public function pageInvoiceAgendamentoConcluido($html = null){

        $data = DB::table('client_scheduling_invoices AS csi')
        ->join('client_schedulings as cs', function($join)
            {
                $join->on('cs.id', '=', 'csi.id_client_scheduling');
            })
        ->join('clients as c', function($join)
            {
                $join->on('c.id', '=', 'cs.id_client');
            })
        ->leftJoin('client_service_schedulings as css', function($join)
            {
                $join->on('css.id_client_scheduling', '=', 'cs.id');
                $join->on('css.status', '=', DB::raw("'1'"));
            })
        ->leftJoin('client_services as cse', function($join)
            {
                $join->on('cse.id', '=', 'css.id_client_service');
            })
        ->leftJoin('services as se', function($join)
            {
                $join->on('se.id', '=', 'cse.id_service');
            })
        ->select('csi.id',
                'csi.id_client_scheduling',
                'c.name as name_client',
                'c.register',
                'csi.price_total as preco',
                'cs.schedule_date',
                'csi.completed_date',
                'csi.due_date')
        ->selectRaw("GROUP_CONCAT(DISTINCT se.name) as services")
        ->where('cs.status_scheduling',6)
        ->where('cs.status',1)
        ->where('csi.status',1)
        ->whereNotNull('cs.completed_date')
        ->groupBy('cs.id')
        ->orderByRaw('cs.status DESC, cs.completed_date DESC')
        ->get();

        if(null == $html){
            return $data;
        }else{
            $view = (string) view('invoice.components.cardAgendamentosConcluido')->with('agendamentos', $data);
            return $view;
        }
    }//fim função

    public function pageInvoiceInvoicesPagos($html = null){
        $data = DB::table('client_scheduling_invoices AS csi')
        ->join('client_schedulings as cs', function($join)
            {
                $join->on('cs.id', '=', 'csi.id_client_scheduling');
            })
        ->join('clients as c', function($join)
            {
                $join->on('c.id', '=', 'cs.id_client');
            })
        ->leftJoin('client_service_schedulings as css', function($join)
            {
                $join->on('css.id_client_scheduling', '=', 'cs.id');
                $join->on('css.status', '=', DB::raw("'1'"));
            })
        ->leftJoin('client_services as cse', function($join)
            {
                $join->on('cse.id', '=', 'css.id_client_service');
            })
        ->leftJoin('services as se', function($join)
            {
                $join->on('se.id', '=', 'cse.id_service');
            })
        ->select('csi.id',
                'csi.id_client_scheduling',
                'csi.number as number_invoice',
                'c.name as name_client',
                'c.register',
                'csi.price_total as preco',
                'cs.schedule_date',
                'csi.completed_date',
                'csi.due_date')
        ->selectRaw("GROUP_CONCAT(DISTINCT se.name) as services")
        ->where('cs.status_scheduling',6)
        ->where('cs.status',1)
        ->where('csi.status',2)
        ->whereNotNull('cs.completed_date')
        ->groupBy('cs.id')
        ->orderByRaw('cs.status DESC, csi.number DESC')
        ->get();

        if(null == $html){
            return $data;
        }else{
            $view = (string) view('invoice.components.cardInvoicePago')->with('invoices', $data);
            return $view;
        }
    }//fim função

}//fim classe
