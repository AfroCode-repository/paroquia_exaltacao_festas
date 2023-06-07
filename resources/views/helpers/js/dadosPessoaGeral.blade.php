<script>
$(document).on('click', '.modalVerImagem', function(){
    bloquear()
    let nome = $(this).attr('nome')
    let imagem = $(this).attr('imagem')

    var img = document.querySelector("#fotoModalImg");
    img.setAttribute('src', imagem);

    $("#titulo_modal").html(nome)

    $("#modalSomenteFoto").modal('hide')
    $("#modalSomenteFoto").modal('show')
    desbloquear()
})//abre modal foto


$(document).on('click', '.visualizarPessoa', function(){
    //bloquear()
    let nome = $(this).attr('nome')
    let codigo = $(this).attr('codigo')
    Swal.fire({
        title: 'Atenção!',
        text: `Deseja Visualizar os dados de ${nome}?`,
        icon: 'warning',
        showDenyButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: 'Visualizar',
        confirmButtonColor: '#3085d6',
        denyButtonText: `Cancelar`,
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `{{ env("APP_URL") }}/pessoa/${codigo}`;
        } else if (result.isDenied) {
            //desbloquear()
            Swal.fire('Operação Cancelada!', '', 'info')
        }
    })
})//fim visualizarPessoa
</script>
