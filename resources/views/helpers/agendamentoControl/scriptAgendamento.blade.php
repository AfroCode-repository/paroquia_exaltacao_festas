<script>

    @include("helpers.js.abreModal",[
        "tipo" =>"editar",
        "nome_editar"=> "settingsAgendamento",
        "rota_name"=> "recupera.modal.settingsAgendamento",
        "html"=> "HtmlModalSettingsAgendamento",
        "modal"=> "modalSettingsAgendamento"
    ]){{--abre modalSettingsAgendamento--}}

    @include("helpers.js.abreModal",[
        "tipo" =>"editar",
        "nome_editar"=> "editarAgendamentoSettings",
        "rota_name"=> "recupera.modal.editarAgendamento",
        "html"=> "HtmlModalEditarAgendamento",
        "modal"=> "modalEditarAgendamento",
        "hideList"=> array('modalSettingsAgendamento')
    ]){{--abre modalEditarAgendamento--}}

    {{--Observações do agendamento e cliente--}}
    @include('helpers.js.obsStoreDB',[
        'selector' => '.novaObsAgendamento',
        'selectorObs' => '#obsAgendamentoClienteSettings',
        'tipo'=> 'scheduling',
        'rota_name'=> "store.obs.scheduling",
        'html' => 'htmlTableAgendamentoObs'
    ])

    @include('helpers.js.obsStoreDB',[
        'selector' => '.novaObsClientSettings',
        'selectorObs' => '#obsClienteSettings',
        'tipo'=> 'client',
        'rota_name'=> "store.obs.client",
        'html' => 'htmlTableClientObs'
    ])

    let resumoAgendamentos = (tipo,id) => {
        bloquear()
        let formData = new FormData()
        let endereco
        let manager
        let data
        let tempoServico
        let tipoAgendamento

        if ('new' == tipo) {

            let client = $("#novoClientAgendamento option:selected").text()

            if (!client) {
                client = false
            }

            endereco = $("#novoEnderecoAgendamento option:selected").text()
            tipoAgendamento = $("#novoTipoAgendamento option:selected").text()
            empregado = $("#novoEmpregadoAgendamento option:selected").text()
            manager = $("#novoGerenteAgendamento").val()
            data = $("#novoDataAgendamento").val()
            let obs = $("#novoObsAgendamento").val()
            tempoServico = $("#novoDiasTrabalho option:selected").text()
            let servico = $("#novoServiceAgendamento option:selected").text()
            let price = $("#novoPrecoServiceAgendamento").val()

            formData.append("client",client)
            formData.append("empregado",empregado)
            formData.append("servico",servico)
            formData.append("price",price)
            formData.append("obs",obs)

        } else {

            endereco = $("#editarEnderecoAgendamento option:selected").text()
            manager = $("#editarGerenteAgendamento").val()
            data = $("#editarDataAgendamento").val()
            tempoServico = $("#editarDiasTrabalho option:selected").text()
            tipoAgendamento = $("#editarTipoAgendamento option:selected").text()

            formData.append("id",id)
        }

            formData.append("endereco",endereco)
            formData.append("manager",manager)
            formData.append("data",data)
            formData.append("tempoServico",tempoServico)
            formData.append("tipoAgendamento",tipoAgendamento)

        formData.append("_token","{{ csrf_token() }}")

        fetch("{{ route('recupera.resumo.agendamento') }}", {
            method: 'POST',
            body: formData
        }).then(function(response) {
            response.json().then(function(data) {
                if(true == data.success){

                    let htmlTable
                    let htmlApp

                    if ('new' == tipo) {
                        htmlApp = document.querySelector('#resumeHTMLAgendamento')
                    }else{
                        htmlApp = document.querySelector('#resumeHTMLAgendamentoSettings')
                    }

                    htmlApp.innerHTML = ''
                    htmlApp.innerHTML = data.data.htmlResume
                    desbloquear()

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
        });{{-- fim form submit novo agendamento --}}

    }//fim função

    $(document).on('change','#novoServiceAgendamento', function(){
        bloquear()
            let element = document.querySelector("#novoServiceAgendamento")
            let service = element.value
            let price = $("#novoServiceAgendamento").select2().find(":selected").data("price");
            document.querySelector("#novoPrecoServiceAgendamento").value = price
            loadPlugins()
        desbloquear()

    }){{-- addServiceNovoAgendamento --}}

    $(document).on('change','#editarServiceAgendamento', function(){
        bloquear()
            let element = document.querySelector("#editarServiceAgendamento")
            let service = element.value
            let price = $("#editarServiceAgendamento").select2().find(":selected").data("price");
            document.querySelector("#editarPrecoServiceAgendamento").value = price
            loadPlugins()
        desbloquear()

    }){{-- addServiceNovoAgendamento --}}

    $(document).on('click','#navResumoAgendamento', function(){
        resumoAgendamentos('new',null)
    })//fim função

    $(document).on('click','#navResumoEditarAgendamento', function(){
        let id = $(this).attr('value')

        resumoAgendamentos(null,id)
    })//fim função

    {{--
    $(document).on('click','#navObsClientAgendamentoSettings', function(){
        @include("helpers.js.reloadDataTables",[
            "selectorTable" => ".tableAgendamentosObs"
        ]);
    })//fim função

    $(document).on('click','#navObsClientSettings', function(){
        @include("helpers.js.reloadDataTables",[
            "selectorTable" => ".tableClientObsSettings"
        ]);
    })
    --}}

    $(document).on('click','#salvarNovoAgendamento', function(){
        bloquear()

        let novoEnderecoAgendamento = document.querySelector("#novoEnderecoAgendamento")
        {{--let _novoEnderecoAgendamento = document.querySelector("#novoEnderecoAgendamento + span")--}}

        let novoGerenteAgendamento = document.querySelector("#novoGerenteAgendamento")
        let novoDataAgendamento = document.querySelector("#novoDataAgendamento")
        let novoTipoAgendamento = document.querySelector("#novoTipoAgendamento")
        let _novoTipoAgendamento = document.querySelector("#novoTipoAgendamento + span")
        let novoObsAgendamento = document.querySelector("#novoObsAgendamento")
        let novoServiceAgendamento = document.querySelector("#novoServiceAgendamento")
        let _novoServiceAgendamento = document.querySelector("#novoServiceAgendamento + span")
        let novoPrecoServiceAgendamento = document.querySelector("#novoPrecoServiceAgendamento")
        let novoDiasTrabalho = document.querySelector("#novoDiasTrabalho")
        let novoEmpregadoAgendamento = document.querySelector("#novoEmpregadoAgendamento")
        let _novoEmpregadoAgendamento = document.querySelector("#novoEmpregadoAgendamento + span")

        let valida = true

        removeClass(novoEnderecoAgendamento, 'is-invalid')
        removeClass(novoGerenteAgendamento, 'is-invalid')
        removeClass(novoDataAgendamento, 'is-invalid')
        removeClass(_novoTipoAgendamento, 'is-invalid')
        removeClass(_novoServiceAgendamento, 'is-invalid')
        removeClass(novoPrecoServiceAgendamento, 'is-invalid')
        removeClass(_novoEmpregadoAgendamento, 'is-invalid')

        @if(!isset($id))
            let client = document.querySelector("#novoClientAgendamento")
            let _client = document.querySelector("#novoClientAgendamento + span")
            removeClass(_client, 'is-invalid')

            if(!client.value || '' == client.value || false == client.value){
                removeAndAddClass(_client, 'is-invalid')
                valida = false
            }
        @endif

        if(!novoEnderecoAgendamento.value || '' == novoEnderecoAgendamento.value || false == novoEnderecoAgendamento.value){
            removeAndAddClass(novoEnderecoAgendamento, 'is-invalid')
            valida = false
        }

        if(!novoGerenteAgendamento.value || '' == novoGerenteAgendamento.value || false == novoGerenteAgendamento.value){
            removeAndAddClass(novoGerenteAgendamento, 'is-invalid')
            valida = false
        }

        if(!novoDataAgendamento.value || '' == novoDataAgendamento.value || false == novoDataAgendamento.value){
            removeAndAddClass(novoDataAgendamento, 'is-invalid')
            valida = false
        }

        if(!novoTipoAgendamento.value || '' == novoTipoAgendamento.value || false == novoTipoAgendamento.value){
            removeAndAddClass(_novoTipoAgendamento, 'is-invalid')
            valida = false
        }

        if(!novoServiceAgendamento.value || '' == novoServiceAgendamento.value || false == novoServiceAgendamento.value){
            removeAndAddClass(_novoServiceAgendamento, 'is-invalid')
            valida = false
        }

        if(!novoPrecoServiceAgendamento.value || '' == novoPrecoServiceAgendamento.value || false == novoPrecoServiceAgendamento.value){
            removeAndAddClass(novoPrecoServiceAgendamento, 'is-invalid')
            valida = false
        }

        if(!novoEmpregadoAgendamento.value || '' == novoEmpregadoAgendamento.value || false == novoEmpregadoAgendamento.value){
            removeAndAddClass(_novoEmpregadoAgendamento, 'is-invalid')
            valida = false
        }

        if(!$("#checkResumo").is(':checked') && true == valida){
            $("#checkResumo").removeClass('is-invalid')
            $("#checkResumo").addClass('is-invalid')

            desbloquear()
            Swal.fire('Atenção!','Clique em "Confirmar informações" na aba resumo e revisão!','warning')
            document.getElementById("navResumoAgendamento").click();

        }else if(false == valida){
            desbloquear()
            alertPreencher()
            document.getElementById("navInfoAgendamento").click();
        }else{
            $('#modalAddAgendamento').modal('hide')

            let formData = new FormData()

            @if(isset($id))
                formData.append("id","{{$id}}")
            @else
                formData.append("id",client.value)
            @endif

            formData.append("endereco",novoEnderecoAgendamento.value)
            formData.append("gerente",novoGerenteAgendamento.value)
            formData.append("data",novoDataAgendamento.value)
            formData.append("tipo",novoTipoAgendamento.value)
            formData.append("obs",novoObsAgendamento.value)
            formData.append("service",novoServiceAgendamento.value)
            formData.append("price",novoPrecoServiceAgendamento.value)
            formData.append("workday",novoDiasTrabalho.value)
            formData.append("employer",novoEmpregadoAgendamento.value)
            formData.append("_token","{{ csrf_token() }}")

            fetch("{{ route('store.scheduling') }}", {
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

                                @if(isset($id))
                                    let valHTML = document.querySelector("#htmlTableAgendamentosVigentes")
                                    valHTML.innerHTML = ""
                                    valHTML.innerHTML = data.data.tableAgendamentosVigentes
                                    @include('helpers.js.reloadDataTables',['selectorTable'=>'#tableAgendamentosVigentes'])
                                    desbloquear()
                                @else
                                    window.location.reload();
                                @endif
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
            });{{-- fim form submit novo agendamento --}}

        }
    })//fim função

    $(document).on('click','.navResumoSettingsAgendamento', function(){

        let tipo = $(this).attr('tipo')
        let id = $(this).val()

        //resumoAgendamentos(tipo,id)
    })//fim função {{--rever essa função, se não funcionaria apagar--}}

    $(document).on('click','#addNovoServiceAgendamento', function(){
        bloquear()
        let id = $(this).val()

        let service = document.querySelector("#editarServiceAgendamento")
        let _service = document.querySelector("#editarServiceAgendamento + span")

        let price = document.querySelector("#editarPrecoServiceAgendamento")

        let employer = document.querySelector("#editarEmpregadoAgendamento")
        let _employer = document.querySelector("#editarEmpregadoAgendamento + span")

        let valida = true

        if(!service.value || '' == service.value || false == service.value){
            removeAndAddClass(_service, 'is-invalid')
            valida = false
        }

        if(!price.value || '' == price.value || false == price.value){
            removeAndAddClass(price, 'is-invalid')
            valida = false
        }

        if(!employer.value || '' == employer.value || false == employer.value){
            removeAndAddClass(_employer, 'is-invalid')
            valida = false
        }

        if(false == valida){
            desbloquear()
            alertPreencher()
        }else{

            let formData = new FormData()

            formData.append("id",id)
            @if(isset($id))
                formData.append("id_client","{{$id}}")
            @else
                formData.append("id_client","noId")
            @endif
            formData.append("service",service.value)
            formData.append("price",price.value)
            formData.append("employer",employer.value)
            formData.append("_token","{{ csrf_token() }}")

            fetch("{{ route('store.client.service.scheduling') }}", {
                method: 'POST',
                body: formData
            }).then(function(response) {
                response.json().then(function(data) {
                    if(true == data.success){
                        let valHTML = document.querySelector("#htmlTableServicosAgendamento")
                        valHTML.innerHTML = ""
                        valHTML.innerHTML = data.data.tableClientServicesScheduling

                        @if(!str_contains(Route::getCurrentRoute()->getName(), 'calendar'))
                            valHTML = document.querySelector("#htmlTableAgendamentosVigentes")
                            valHTML.innerHTML = ""
                            valHTML.innerHTML = data.data.tableAgendamentosVigentes
                            @include('helpers.js.reloadDataTables',['selectorTable'=>'#tableAgendamentosVigentes'])
                        @endif

                        desbloquear()
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
            });{{-- fim novo serviço agendamento --}}
        }

    })//fim função

    $(document).on('click','#salvarEditarAgendamento', function(){
        bloquear()
        let id = document.querySelector("#salvarEditarAgendamento").value

        let endereco = document.querySelector("#editarEnderecoAgendamento")
        {{--let _endereco = document.querySelector("#editarEnderecoAgendamento + span")--}}

        let gerente = document.querySelector("#editarGerenteAgendamento")
        let dateAgendamento = document.querySelector("#editarDataAgendamento")
        let DiasTrabalho = document.querySelector("#editarDiasTrabalho")

        let valida = true

        {{--removeClass(_endereco, 'is-invalid')--}}
        removeClass(gerente, 'is-invalid')
        removeClass(dateAgendamento, 'is-invalid')
        removeClass(DiasTrabalho, 'is-invalid')

        if(!endereco.value || '' == endereco.value || false == endereco.value){
            removeAndAddClass(endereco, 'is-invalid')
            valida = false
        }

        if(!gerente.value || '' == gerente.value || false == gerente.value){
            removeAndAddClass(gerente, 'is-invalid')
            valida = false
        }

        if(!dateAgendamento.value || '' == dateAgendamento.value || false == dateAgendamento.value){
            removeAndAddClass(dateAgendamento, 'is-invalid')
            valida = false
        }

        if(!DiasTrabalho.value || '' == DiasTrabalho.value || false == DiasTrabalho.value){
            removeAndAddClass(DiasTrabalho, 'is-invalid')
            valida = false
        }

        if(!$("#checkEditarResumo").is(':checked') && true == valida){
            $("#checkEditarResumo").removeClass('is-invalid')
            $("#checkEditarResumo").addClass('is-invalid')

            desbloquear()
            Swal.fire('Atenção!','Clique em "Confirmar informações" na aba resumo e revisão!','warning')
            document.getElementById("navResumoEditarAgendamento").click();

        }else if(false == valida){
            desbloquear()
            alertPreencher()
            document.getElementById("navInfoEditarAgendamento").click();
        }else{

            $('#modalEditarAgendamento').modal('hide')

            let formData = new FormData()

            formData.append("id",id)
            formData.append("endereco",endereco.value)
            formData.append("gerente",gerente.value)
            formData.append("dateAgendamento",dateAgendamento.value)
            formData.append("DiasTrabalho",DiasTrabalho.value)
            formData.append("_token","{{ csrf_token() }}")

            fetch("{{ route('update.scheduling') }}", {
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

                                @if(isset($id))
                                    let valHTML = document.querySelector("#htmlTableAgendamentosVigentes")
                                    valHTML.innerHTML = ""
                                    valHTML.innerHTML = data.data.tableAgendamentosVigentes

                                    @include('helpers.js.reloadDataTables',['selectorTable'=>'#tableAgendamentosVigentes'])
                                    desbloquear()
                                @else
                                    window.location.reload();
                                @endif
                        })
                    }else{
                        desbloquear()
                        Swal.fire(
                            'Atenção!',
                            data.message,
                            'warning'
                        )

                        $('#modalEditarAgendamento').modal('show')

                    }
                });
            }).catch(function(err) {
                desbloquear()
                Swal.fire('Erro!',err,'error')
            });
        }
    })//fim função


    $(document).on('click','.excluirEditarServicoAgendamento', function(){
        let id = $(this).val()
        let tipo = $(this).attr('tipo')
        let id_client = $(this).attr('id_client')
        let id_scheduling = $(this).attr('id_scheduling')

        let formData = new FormData()

        formData.append("id",id)
        formData.append("tipo",tipo)
        formData.append("id_client",id_client)
        formData.append("id_scheduling",id_scheduling)
        formData.append("_token","{{ csrf_token() }}")

        Swal.fire({
            title: 'Atenção!',
            html: `Deseja excluir o serviço?`,
            icon: 'warning',
            showDenyButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: `Sim, apagar`,
            confirmButtonColor: '#3085d6',
            denyButtonText: `Cancelar`,
        }).then((result) => {
                bloquear()
                if (result.isConfirmed) {
                    fetch("{{ route('mudarStatus.client.service.scheduling') }}", {
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
                                            let valHTML = document.querySelector("#htmlTableServicosAgendamento")
                                            valHTML.innerHTML = ""
                                            valHTML.innerHTML = data.data.tableClientServicesScheduling

                                            @if(!str_contains(Route::getCurrentRoute()->getName(), 'calendar'))
                                                valHTML = document.querySelector("#htmlTableAgendamentosVigentes")
                                                valHTML.innerHTML = ""
                                                valHTML.innerHTML = data.data.tableAgendamentosVigentes

                                                @include('helpers.js.reloadDataTables',['selectorTable'=>'#tableAgendamentosVigentes'])
                                            @endif

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
                        Swal.fire('Operação Cancelada!', 'Nenhuma mudança Realizada', 'info')
                        desbloquear()
                    }
        })//sweet alert confirmação
    })//fim função

    $(document).on('click','.mudaStatusAgendamentoSettings', function(){
        let id = $(this).val()
        let tipo = $(this).attr('tipo')
        let code = $(this).attr('code')
        let text = ''

        let formData = new FormData()

        formData.append("id",id)
        formData.append("tipo",tipo)
        formData.append("code",code)
        formData.append("_token","{{ csrf_token() }}")

        if ('E' == tipo) {
            text = 'excluir'
        } else {
            text = 'reativar'
        }

        Swal.fire({
            title: 'Atenção!',
            html: `Deseja ${text} o agendamento ID: ${id}?`,
            icon: 'warning',
            showDenyButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: `Sim, excluir`,
            confirmButtonColor: '#3085d6',
            denyButtonText: `Cancelar`,
        }).then((result) => {
                bloquear()
                if (result.isConfirmed) {
                    fetch("{{ route('mudarStatus.scheduling') }}", {
                        method: 'POST',
                        body: formData
                    }).then(function(response) {
                        response.json().then(function(data) {
                            desbloquear()
                            if(true == data.success){
                                $('#modalSettingsAgendamento').modal('hide')
                                Swal.fire({
                                    title: 'Êxito!',
                                    text: data.message,
                                    icon: 'success',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                    }).then(function () {
                                        bloquear()
                                        if(true == data.success){

                                            @if(isset($id))
                                                let valHTML = document.querySelector("#htmlTableAgendamentosVigentes")
                                                valHTML.innerHTML = ""
                                                valHTML.innerHTML = data.data.tableAgendamentosVigentes
                                                @include('helpers.js.reloadDataTables',['selectorTable'=>'#tableAgendamentosVigentes'])

                                                valHTML = document.querySelector("#htmlTableAgendamentosHistorico")
                                                valHTML.innerHTML = ""
                                                valHTML.innerHTML = data.data.tableAgendamentosHistorico
                                                @include('helpers.js.reloadDataTables',[
                                                    'selectorTable'=>'#tableAgendamentosHistorico',
                                                    'moreTable' => true
                                                ])
                                                desbloquear()
                                            @else
                                                window.location.reload();
                                            @endif

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
                        Swal.fire('Operação Cancelada!', 'Nenhuma mudança realizada', 'info')
                        desbloquear()
                    }
        })//sweet alert confirmação
    })//fim função




    $(document).on('click','.atribuiStatusAgendamentoSettings', function(){
        let id = $(this).val()
        let tipo = $(this).attr('tipo')
        let code = $(this).attr('code')

        Swal.fire({
        title: 'Atribuição de Status no Agendamento',
        text:'status',
        icon: 'warning',
        input: 'select',
        inputOptions: {
          'Status': {
            @include("helpers.js.listAtribuiStatusSettings")
          }
        },
        inputPlaceholder: 'Selecione um status',
        showCancelButton: true,
        inputValidator: (value) => {
          return new Promise((resolve) => {
            if (value !== '') {
                let texto = $('.swal2-select').find(":selected").text();

                let formData = new FormData()

                formData.append("id",id)
                formData.append("status_scheduling",value)
                formData.append("tipo",tipo)
                formData.append("code",code)
                formData.append("_token","{{ csrf_token() }}")

                Swal.fire({
                    title: 'Atenção!',
                    html: `Deseja Atribuir o status: <strong>${texto}</strong>, para o agendamento ID: ${id}?`,
                    icon: 'warning',
                    showDenyButton: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: `Sim, atribuir`,
                    confirmButtonColor: '#3085d6',
                    denyButtonText: `Cancelar`,
                }).then((result) => {
                        bloquear()
                        if (result.isConfirmed) {
                            fetch("{{ route('mudar.atribuicaoStatus.scheduling') }}", {
                                method: 'POST',
                                body: formData
                            }).then(function(response) {
                                response.json().then(function(data) {
                                    desbloquear()
                                    if(true == data.success){

                                        $('#modalSettingsAgendamento').modal('hide')

                                        Swal.fire({
                                            title: 'Êxito!',
                                            text: data.message,
                                            icon: 'success',
                                            allowOutsideClick: false,
                                            allowEscapeKey: false,
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok'
                                            }).then(function () {
                                                bloquear()
                                                if(true == data.success){
                                                    @if(isset($id))
                                                        let valHTML = document.querySelector("#htmlTableAgendamentosVigentes")
                                                        valHTML.innerHTML = ""
                                                        valHTML.innerHTML = data.data.tableAgendamentosVigentes
                                                        @include('helpers.js.reloadDataTables',['selectorTable'=>'#tableAgendamentosVigentes'])

                                                        valHTML = document.querySelector("#htmlTableAgendamentosHistorico")
                                                        valHTML.innerHTML = ""
                                                        valHTML.innerHTML = data.data.tableAgendamentosHistorico
                                                        @include('helpers.js.reloadDataTables',[
                                                            'selectorTable'=>'#tableAgendamentosHistorico',
                                                            'moreTable' => true
                                                        ])
                                                        desbloquear()
                                                    @else
                                                        window.location.reload();
                                                    @endif

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
                            Swal.fire('Operação Cancelada!', 'Nenhuma mudança realizada', 'info')
                            desbloquear()
                        }
                })//sweet alert confirmação
            } else {
              resolve('Por favor, selecione uma opção!')
            }
          })
        }
      })
    })//fim função

    $(document).on('click','.concluirAgendamentoSettings', function(){
        let id = $(this).val()
        let tipo = $(this).attr('tipo')
        let code = $(this).attr('code')

        let formData = new FormData()

        formData.append("id",id)
        formData.append("tipo",tipo)
        formData.append("code",code)
        formData.append("_token","{{ csrf_token() }}")

        Swal.fire({
            title: 'Atenção!',
            html: `Deseja concluir o agendamento ID: ${id}?`,
            icon: 'warning',
            showDenyButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: `Sim, concluir`,
            confirmButtonColor: '#3085d6',
            denyButtonText: `Cancelar`,
        }).then((result) => {
                bloquear()
                if (result.isConfirmed) {
                    fetch("{{ route('concluir.scheduling') }}", {
                        method: 'POST',
                        body: formData
                    }).then(function(response) {
                        response.json().then(function(data) {
                            desbloquear()
                            if(true == data.success){
                                $('#modalSettingsAgendamento').modal('hide')
                                Swal.fire({
                                    title: 'Êxito!',
                                    text: data.message,
                                    icon: 'success',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                    }).then(function () {
                                        bloquear()
                                        if(true == data.success){


                                            @if(isset($id))
                                                let valHTML = document.querySelector("#htmlTableAgendamentosVigentes")
                                                valHTML.innerHTML = ""
                                                valHTML.innerHTML = data.data.tableAgendamentosVigentes
                                                @include('helpers.js.reloadDataTables',['selectorTable'=>'#tableAgendamentosVigentes'])

                                                valHTML = document.querySelector("#htmlTableAgendamentosHistoricos")
                                                valHTML.innerHTML = ""
                                                valHTML.innerHTML = data.data.tableAgendamentosHistoricos
                                                @include('helpers.js.reloadDataTables',[
                                                    'selectorTable'=>'#tableAgendamentosHistoricos',
                                                    'moreTable' => true
                                                ])
                                                desbloquear()
                                            @else
                                                window.location.reload();
                                            @endif

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
                        Swal.fire('Operação Cancelada!', 'Nenhuma mudança realizada', 'info')
                        desbloquear()
                    }
        })//sweet alert confirmação
    })//fim função

    $(document).on('change','#novoClientAgendamento', function(){
        bloquear()
        let selectAddress = document.querySelector('#novoEnderecoAgendamento');
        selectAddress.innerHTML = '<option value="">Selecione uma opção</option>'

        let selectService = document.querySelector('#novoServiceAgendamento');
        //let _selectService = document.querySelector('#novoServiceAgendamento + span');
        selectService.innerHTML = '<option value="">Selecione uma opção</option>'

        let client = document.querySelector('#novoClientAgendamento');

        let formData = new FormData()

        formData.append("client",client.value)
        formData.append("_token","{{ csrf_token() }}")

        fetch("{{ route('recupera.endereco.service.client') }}", {
           method: 'POST',
           body: formData
        }).then(function(response) {
           response.json().then(function(data) {
               if(true == data.success){
                    selectAddress.options[selectAddress.options.length] = new Option(`${data.data.textAddress}`, data.data.valueAddress)
                    selectAddress.removeAttribute('disabled')

                    let dataService = JSON.parse(data.data.dataService)

                    for (let i = 0;i < dataService.length; i++) {
                        let option = new Option(`${dataService[i].name}`, dataService[i].id)
                        selectService.options[selectService.options.length] = option;
                        option.setAttribute('data-price', dataService[i].price)
                    }

                    selectService.removeAttribute('disabled')
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
        }).catch(function(err) {
           desbloquear()
           Swal.fire('Erro!',err,'error')
        })
    })//fim função

</script>
