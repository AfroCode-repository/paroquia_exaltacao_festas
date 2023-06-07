@foreach ($listaStatusSchedulingSettings as $l)
    {{$l->status}}: '{{$l->description}}',
@endforeach
