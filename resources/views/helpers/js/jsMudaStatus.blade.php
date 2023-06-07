$(document).on('click','{!! $SelectorClick !!}', function(){
    let txtData = $(this).attr('att')
    let tipo = $(this).attr('tipo')
    let id = $(this).val()
    let text = ""

    if("E" == tipo){
        text = "Excluir"
    }else{
        text = "Reativar"
    }

    Swal.fire({
        title: 'Atenção!',
        text: `Realmente deseja ${text} {!! $txtSwal !!}: ${txtData}?`,
        icon: 'warning',
        showDenyButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: `${text}`,
        confirmButtonColor: '#3085d6',
        denyButtonText: `Cancelar`,
    }).then((result) => {
        if (result.isConfirmed) {
            bloquear()
            let formData = new FormData()
            formData.append("id",id)
            formData.append("tipo",tipo)
            formData.append("_token","{{ csrf_token() }}")

            fetch("{{ route("$rota_name") }}", {
                method: 'POST',
                body: formData
            }).then(function(response) {
                response.json().then(function(data) {
                    desbloquear()
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
                                let valHTML = document.querySelector("#{!! $htmlDiv !!}")
                                valHTML.innerHTML = ""
                                valHTML.innerHTML = data.data.{!! $tableHtmlDiv !!}
                                @include('helpers.js.reloadDataTables',['selectorTable'=>"#$tableNameSelector"])
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
                })
            }).catch(function(err) {
                desbloquear()
                Swal.fire('Erro!',err,'error')
            })
        } else if (result.isDenied) {
            desbloquear()
            Swal.fire('Operação Cancelada!', 'Nenhuma mudança Realizada', 'info')
        }
    })
})//fim função
