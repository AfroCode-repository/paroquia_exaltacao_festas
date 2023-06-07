<div class="modal-header">
    <h4 class="modal-title text-success" id="titulo_modal">{{$titulo}}</h4>
</div>
<div class="modal-body m-3">

    <div class="row">
        <div class="mb-3 col-md-12">
            <label class="form-label" for="oldPassword">Senha Antiga</label>
            <input type="password" class="form-control" name="oldPassword" id="oldPassword" placeholder="Informe a Senha Antiga">
        </div>
        <div class="mb-3 col-md-12">
            <label class="form-label" for="password">Nova Senha</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Informe a nova senha">
        </div>
        <div class="mb-3 col-md-12">
            <label class="form-label" for="confirmPassword">Confirmar nova Senha</label>
            <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirme a nova senha">
        </div>
    </div>

</div>
<div class="modal-footer">
        <button type="button" id="salvarNovaSenhaPadrao" class="btn btn-success">Mudar Senha</button>
        <button type="button" id="fecharNovaSenhaPadrao" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
</div>
