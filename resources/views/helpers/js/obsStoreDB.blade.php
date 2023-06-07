$(document).on('click','{{$selector}}', function(){
    bloquear()
    let id = $(this).val()
    let obs = document.querySelector("{{$selectorObs}}")
    let tipo = '{{$tipo}}'

    let valida = true

    removeClass(obs, 'is-invalid')

    if(!obs.value || '' == obs.value || false == obs.value){
        removeAndAddClass(obs, 'is-invalid')
        valida = false
    }

    if(true == valida){
        desbloquear()
        Swal.fire({
        title: 'Atenção!',
        html: `Deseja Salvar a Observação: ${obs.value}?`,
        icon: 'warning',
        showDenyButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: `Sim, salvar`,
        confirmButtonColor: '#3085d6',
        denyButtonText: `Cancelar`,
        }).then((result) => {
            bloquear()
            if (result.isConfirmed) {
                let formData = new FormData()

                formData.append("id",id)
                formData.append("obs",obs.value)
                formData.append("tipo",tipo)
                formData.append("_token","{{ csrf_token() }}")

                fetch("{{ route("$rota_name") }}", {
                    method: 'POST',
                    body: formData
                }).then(function(response) {
                    response.json().then(function(data) {
                        desbloquear()
                        if(true == data.success){
                            Swal.fire({
                                title: 'Êxito!',
                                text: data.message,
                                icon: 'success',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                                }).then(function () {
                                    if(true == data.success){
                                        let valHTML = document.querySelector("#{{$html}}")
                                        valHTML.innerHTML = ""
                                        valHTML.innerHTML = data.data.{{$html}}

                                        document.querySelector("{{$selectorObs}}").value = ''

                                        desbloquear()
                                    }else{
                                        desbloquear()
                                        Swal.fire(
                                            'Atenção!',
                                            data.message,
                                            'warning'
                                        )
                                    }
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
                Swal.fire('Operação Cancelada!', 'Observação não foi salva no sistema!', 'info')
            }
        })//sweet alert confirmação
    }else{
        desbloquear()
        alertPreencher()
    }//fim função
})//fim função
