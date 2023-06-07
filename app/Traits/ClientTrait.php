<?php

namespace App\Traits;

use App\Models\client;
use App\Models\client_observations;
use App\Models\client_scheduling_obs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

trait ClientTrait {

    public function generateCodeClient($str)
    {

        $code = substr(Crypt::encryptString($str.now()),0,30);

        $verificaSeExisteCodigo = client::where('code',$code)->get();

        while (!$verificaSeExisteCodigo->isEmpty()) {
            $code = substr(Crypt::encryptString($str.now()),0,30);
            $verificaSeExisteCodigo = client::where('code',$code)->get();
        }

        return $code;
    }//fim função

    public function idByCodeClient($code)
    {
        $sql = client::where('code',$code)->get();

        if ($sql->isEmpty()) {
            $this->error("Erro ao sincronizar os dados do client, atualize a pagina e tente novamente! erro:0xCode1");
        }

        $sql = json_decode(json_encode($sql), true);

        $id = ($sql) ? $sql[0]['id'] : '' ;

        return $id;
    }//fim função

    public function htmlTableClientObs($id_client)
    {
        //$ListClientObs = client_observations::where('id_client', $id_client)->get();

        $client_observations = DB::table('client_observations as co')
        ->selectRaw("co.id,'' as id_agendamento, 'Cliente' as tipo, co.obs, co.created_at, co.status ")
        ->where('co.id_client',$id_client);

        $listaObs = DB::table('client_scheduling_obs as cso')
        ->join('client_schedulings AS cs', function($join)
        {
            $join->on('cs.id', '=', 'cso.id_client_scheduling');
        })
        ->selectRaw("cso.id, cso.id_client_scheduling as id_agendamento, 'Agendamento' as tipo, cso.obs, cso.created_at, cso.status ")
        ->where('cs.id_client',$id_client)
        ->union($client_observations)
        ->get();

        //$data = (string) view('client.dados.components.tables.tableAgendamentoObs.blade')->with('listaObs',$listaObs);
        //return $data;

        $data = (string) view('client.dados.components.tables.tableClientObs')->with('ListClientObs',$listaObs);
        return $data;
    }

    public function storeObs($id,$obs,$tipo)
    {
        $data['valida'] = false;

        switch ($tipo) {
            case 'scheduling':
                DB::beginTransaction();

                $client_scheduling_obs = new client_scheduling_obs();
                $client_scheduling_obs->id_client_scheduling = $id;
                $client_scheduling_obs->obs = $obs;
                $client_scheduling_obs->save();

                $this->addLogUpdates($client_scheduling_obs->id,'client_scheduling_obs','id',$client_scheduling_obs->id,'','INSERT','Nova observação agendamento');

                $data['html'] = $this->htmlTableAgendamentoObs($id);
                $data["varHtml"] = 'htmlTableAgendamentoObs';
                $data['valida']  = true;

                break;

            case 'client':
                DB::beginTransaction();

                $client_observations = new client_observations();
                $client_observations->id_client = $id;
                $client_observations->obs = $obs;
                $client_observations->save();

                $this->addLogUpdates($client_observations->id,'client_observations','id',$client_observations->id,'','INSERT','Nova observação client');

                $data['html'] = $this->htmlTableClientObs($id);
                $data["varHtml"] = 'htmlTableClientObs';
                $data['valida']  = true;

                break;

            default:
                $data['valida']  = false;
                break;
        }//fim switch

        return $data;

    }//fim função

    public function tableDataTelefones($id_client)
    {
        $listaTelefones =  DB::table('client_numbers as cn')
                            ->where('cn.id_client',$id_client)
                            ->get();

        $data = (string) view('client.dados.components.tables.tableClientTelefones')->with('listaTelefones',$listaTelefones);

        return $data;
    }//fim função

    public function tableDataEnderecos($id_client)
    {
        $listaEnderecos =  DB::table('client_addresses as ca')
        ->join('regions AS s', function($join)
        {
            $join->on('s.id', '=', 'ca.id_region');
        })
        ->selectRaw("ca.id, concat(ca.address, ', ',ca.city, ', ',s.name, '-',s.code, ', ',ca.postal_code) as address, ca.created_at, ca.status")
        ->where('ca.id_client',$id_client)
        ->get();

        $data = (string) view('client.dados.components.tables.tableClientEnderecos')->with('listaEnderecos',$listaEnderecos);
        return $data;
    }//fim função

    public function tableDataEmails($id_client)
    {
        $listaEmails =  DB::table('client_emails as ce')
        ->where('ce.id_client',$id_client)
        ->get();

        $data = (string) view('client.dados.components.tables.tableClientEmails')->with('listaEmails',$listaEmails);
        return $data;
    }//fim função

    public function tableDataServices($id_client, $onlyService = null)
    {
        $listaServices =  DB::table('client_services as cs')
        ->join('services AS s', function($join)
        {
            $join->on('s.id', '=', 'cs.id_service');
        })
        ->selectRaw("cs.id, s.name, FORMAT(cs.price, 2, 'de_DE') as price, cs.created_at, cs.status")
        ->where('cs.id_client',$id_client)
        ->where('s.id_company', Auth::user()->id_company)
        ->get();

        if(null != $onlyService){
            $data = $listaServices;
        }else{
            $data = (string) view('client.dados.components.tables.tableClientServices')->with('listaServices',$listaServices);
        }

        return $data;
    }//fim função

    public function tableDataObs($id_client)
    {
        $data = '';
        return $data;
    }//fim função

    public function sqlInfoDataClient($id_client)
    {
        return DB::table('clients AS c')
                            ->leftjoin('client_addresses AS ca', function($join)
                            {
                                $join->on('ca.id_client', '=', 'c.id');
                                $join->on('ca.status', '=', DB::raw("'1'"));
                            })
                            ->leftjoin('regions AS r', function($join)
                            {
                                $join->on('r.id', '=', 'ca.id_region');
                            })
                            ->leftjoin('client_numbers AS cn', function($join)
                            {
                                $join->on('cn.id_client', '=', 'c.id');
                                $join->on('cn.status', '=', DB::raw("'1'"));
                            })
                            ->leftjoin('client_emails AS ce', function($join)
                            {
                                $join->on('ce.id_client', '=', 'c.id');
                                $join->on('ce.status', '=', DB::raw("'1'"));
                            })
                            ->selectRaw("c.id id_client,
                                        c.name,
                                        c.code,
                                        c.register,
                                        c.status,
                                        GROUP_CONCAT(DISTINCT concat(ca.address,', ', ca.city,', ',r.name,', ', ca.postal_code)) address,
                                        GROUP_CONCAT(DISTINCT cn.number) phone,
                                        GROUP_CONCAT(DISTINCT ce.email) email")
                            ->distinct()
                            ->where('c.id', '=', $id_client)
                            ->groupBy('c.id')
                            ->get();
    }//fim função

}//fim classe
