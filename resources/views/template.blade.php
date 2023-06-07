@extends('layouts.app')
@section('JsPersonalizado')
<script>
    $(document).ready(function() {

        {!! $clickSideBar !!}

    })
</script>
@endsection
@section('main')

    <div class="row mb-2 mb-xl-3">
        <div style="display:flex;justify-content: center;">
            <h1>{{ $name_page }}</h1>
        </div>
        <div style="display:flex;justify-content: center;">
            <h5>{{ $nome_sistema }}</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Template</h5>
                </div>
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>

@endsection
