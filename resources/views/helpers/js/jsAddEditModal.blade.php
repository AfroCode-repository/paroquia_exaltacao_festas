$(document).on('click','{!! $selectorClick !!}', function(){
    bloquear()

    @if($id)
        let id = "{{$id}}"
    @else
        let id = $(this).val()
    @endif

    let valida = true

    @for($i = 0; $i < count($variables); $i++)
        {!! $variables[$i] !!}
        {!! $validador[$i] !!}
    @endfor

    if(false == valida){
        desbloquear()
        alertPreencher()
    }else{
        {!! $modalName !!}

        let formData = new FormData()

        formData.append("id",id)

        @for($i = 0; $i < count($formData); $i++)
            {!! $formData[$i] !!}
        @endfor

        formData.append("_token","{{ csrf_token() }}")

        fetch("{{ route("$rota_name") }}", {
            method: 'POST',
            body: formData
        }).then(function(response) {
            response.json().then(function(data) {
                if(true == data.success){
                    desbloquear()
                    Swal.fire({
                        title: 'Êxito!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    }).then(function () {
                            bloquear()

                            let valHTML = document.querySelector("#{!! $html !!}")
                            valHTML.innerHTML = ""
                            valHTML.innerHTML = data.data.{!! $tableHtml !!}
                            @if (isset($tableHtmlPersonalize))
                                @include('helpers.js.reloadDataTables',['selectorTable'=>"#$tableHtmlPersonalize"])
                            @else
                                @include('helpers.js.reloadDataTables',['selectorTable'=>"#$tableHtml"])
                            @endif
                            desbloquear()
                    })
                }else{
                    desbloquear()
                    Swal.fire(
                        'Atenção!',
                        data.message,
                        'warning'
                    )
                }
            });
        }).catch(function(err) {
            desbloquear()
            Swal.fire('Erro!',err,'error')
        });{{-- fetch --}}
    }
})//fim função
