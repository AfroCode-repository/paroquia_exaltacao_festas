let loadHtmlModal = (url,_data,idModal,dataTable) => {
        fetch(url, {
                method: 'POST',
                body: _data
            }).then(function(response) {
                response.json().then(function(data) {
                    if(true == data.success){
                        document.getElementById(idModal).innerHTML = '';
                        document.getElementById(idModal).innerHTML = data.html;
                        loadPlugins()
                        if(dataTable){
                            $(`#${dataTable}`).DataTable({
                                dom: 'Bfrtip',
                                buttons: [
                                    'copy', 'csv', 'excel', 'pdf', 'print'
                                ],
                                "order": [],
                                    "language":{
                                        "url": `${base_URL}/js/plugins/datatables/dataTables.Portuguese-Brasil.json`
                                    },
                                responsive: true,
                            })
                        }
                    }else{
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
            });
}

let recuperaModalHtml = (tipo, id, url,html,dataTable) => {

    let formData = new FormData()
    formData.append('tipo', tipo)
    if("A" == tipo || "C" == tipo || "S" == tipo || "CI" == tipo){
        formData.append('id', id)
    }
    formData.append('_token', '{{ csrf_token()}}')

    loadHtmlModal(url,formData,html,dataTable)


}//função recupera modal
