let recuperaOptions = (idElementAfetado,idBusca,rota) => {
    bloquear()
    let formData = new FormData()

    formData.append("id",idBusca)
    formData.append("_token","{{ csrf_token() }}")

    fetch(`${rota}`, {
        method: 'POST',
        body: formData
    }).then(function(response) {
        response.json().then(function(data) {
            if(true == data.success){

                if('cidade' == idElementAfetado){
                    let _select_funcao = document.getElementById('bairro')
                    _select_funcao.innerHTML = '';
                    _select_funcao.setAttribute("disabled",true)
                }

                let select_funcao = document.getElementById(idElementAfetado)
                select_funcao.innerHTML = '';
                select_funcao.innerHTML = data.data
                select_funcao.removeAttribute("disabled")
                loadPlugins()

            }else{
                Swal.fire(
                    'Atenção!',
                    data.message,
                    'warning'
                )
            }
        })
    }).catch(function(err) {
        Swal.fire('Erro!',err,'error')
    })

    setTimeout(function() {
        desbloquear()
    }, 1000);
}
