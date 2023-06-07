@php
    //$delayInMilliseconds = 1000; //1 second
    $delayInMilliseconds = 1500;
@endphp
@if ("inserir" == $tipo)
    $(document).on('click', '#{{$idModal}}', function(){
        bloquear()
        let tipo = $(this).attr('tipo')
    @if(isset($id))
        recuperaModalHtml(tipo,"{!!$id!!}","{{ route("$rota_name") }}","{!!$html!!}", null)
    @else
        recuperaModalHtml(tipo,null,"{{ route("$rota_name") }}","{!!$html!!}", null)
    @endif
        $('#{!!$modal!!}').modal('hide')
        $('#{!!$modal!!}').modal('show')

        setTimeout(function() {
            desbloquear()
        }, {{$delayInMilliseconds}});

    })//abre {{$idModal}}
@else
    $(document).on('click', '.{{$nome_editar}}', function(){
        bloquear()
        let id = $(this).val()
        let tipo = $(this).attr('tipo')

        @if (isset($hideList))

            @foreach ($hideList as $h_name)
                $('#{!!$h_name!!}').modal('hide')
            @endforeach

        @endif

        if(id){

            let data = new FormData()
            data.append('id', id)
            data.append('_token', '{{ csrf_token()}}')

            @if(isset($dataTables))
                recuperaModalHtml(tipo,id,"{{ route("$rota_name") }}","{{$html}}","{{$dataTables}}")
            @else
                recuperaModalHtml(tipo,id,"{{ route("$rota_name") }}","{{$html}}",null)
            @endif

            $('#{!!$modal!!}').modal('hide')

            $('#{!!$modal!!}').modal('show')

        } else {
            Swal.fire('Erro!','Algo caboloso aconteceu aqui!','error')
        }

        setTimeout(function() {
            desbloquear()
        }, {{$delayInMilliseconds}});

    })//editar {!!$modal!!}
@endif

