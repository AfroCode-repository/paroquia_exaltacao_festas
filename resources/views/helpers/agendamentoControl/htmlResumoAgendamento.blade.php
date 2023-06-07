@if('new' == $tipo)
    <hr>
@endif
@if('settings' == $tipo)
    @if(6 == $status_scheduling)
        <h4 style="text-align:left"><strong>Status: </strong> <span class="badge bg-success"> Concluido</span> </h4>
    @elseif(0 == $status)
        <h4 style="text-align:left"><strong>Status: </strong> <span class="badge bg-danger"> Exlcuido</span> </h4>
    @else
        <h4 style="text-align:left"><strong>Status: </strong> <span class="badge bg-{{ $tipo_status }}">{{ $text_status }}</span> </h4>
    @endif
@endif
    @if(isset($client))
        <p style="text-align:left"><strong>Client: </strong> {{ $client }}</p>
    @endif
    <p style="text-align:left"><strong>Endereço: </strong> {{ $endereco }}</p>
    <p style="text-align:left"><strong>Nome manager: </strong> {{ $manager }}</p>
    <p style="text-align:left"><strong>Tipo agendamento: </strong> {{ $tipoAgendamento }}</p>
    <p style="text-align:left"><strong>Data agendamento: </strong> {{ $data }} </p>
@if('settings' == $tipo)
    <p style="text-align:left"><strong>Data agendamento Final: </strong> {{ $dataEnd }} </p>
@endif
    <p style="text-align:left"><strong>Tempo de serviço: </strong> {{ $tempoServico }} </p>
@if('new' == $tipo)
    <p style="text-align:left"><strong>Serviço: </strong> {{ $servico }} <strong>({{ $empregado }})</strong></p>
@elseif('settings' == $tipo || 'edit' == $tipo)

    <p style="text-align:left"><strong>Serviços:</strong>
@foreach ($services as $s)
        <br>US$ {{ App\Traits\SystemTrait::dinheiroEmMysqlReverse($s->price) }} | {{ $s->service }} | <strong>({{ $s->employer }})</strong>
@endforeach
    </p>

@endif
@if('new' == $tipo)
    <p style="text-align:left"><strong>Observação: </strong> {{ $obs }}</p>
    <h4 style="text-align:left">Preço: US$ {{ $price }}</h4>
@else
    <h4 style="text-align:left">Preço: US$ {{ App\Traits\SystemTrait::dinheiroEmMysqlReverse($price) }}</h4>
@endif
@if('new' == $tipo)
    <hr>
@endif

