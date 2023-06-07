<h3>Lista de Serviços Cadastrados</h3>

<table class="table table-striped dt-responsive nowrap tableClientServicesScheduling" id="tableClientServicesScheduling" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>ID</th>
        <th>Service</th>
        <th>Valor</th>
        <th>Employer</th>
        <th>Ação</th>
    </tr>
    </thead>

    <tbody>

        @foreach ($list_services as $ls)
        <tr>
            <td scope="row">{{$ls->id_client_service_schedulings}}</td>
            <td>{{$ls->service}}</td>
            <td>US$ {{App\Traits\SystemTrait::dinheiroEmMysqlReverse($ls->price)}}</td>
            <td>{{$ls->employer}}</td>
            <td>
                <button
                    class="btn btn-danger excluirEditarServicoAgendamento"
                    value="{{$ls->id_client_service_schedulings}}"
                    id_client ="{{$ls->id_client}}"
                    id_scheduling="{{$ls->id_scheduling}}"
                    tipo="E"><i class="fa-solid fa-trash"></i>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<p><h3>Total: US$ {{App\Traits\SystemTrait::dinheiroEmMysqlReverse($totalPrice)}}</h3></p>
<hr>
