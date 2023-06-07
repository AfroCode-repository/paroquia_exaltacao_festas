<div class="modal-header">
    <h4 class="modal-title text-info" id="titulo_modal">{{$titulo}}</h4>
</div>
<div class="modal-body m-3">

    <div class="row">
        <div class="mb-3 col-md-12">
            <label class="form-label" for="campoPesquisaCliente">Cliente</label>
            <select class="form-control select2" required name="campoPesquisaCliente" id="campoPesquisaCliente" data-toggle="select2">
                <option value="">Selecione uma opção</option>
                @foreach ($clients as $c)
                    <option value="{{$c->id}}">{{$c->name}} {{$c->register}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3 col-md-12" style="display: flex;justify-content: flex-end;">
            <div id="htmlBtnVerDados"></div>
            <button style="margin-right: 4px;" type="button" id="fazerPesquisaCliente" class="btn btn-info">Pesquisar</button>
            <button style="margin-right: 4px;" type="button" id="fecharPesquisaCliente" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
        </div>
    </div>

</div>
<div class="modal-footer">
    <div id="resultadoPesquisaCliente"></div>
</div>
