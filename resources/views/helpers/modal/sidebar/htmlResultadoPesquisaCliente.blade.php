<button style="margin-bottom: 10px" class="btn btn-primary" id="copyInfoClient" data-clipboard-action="copy" data-clipboard-target="#txtInfoClient"><i class="fa-regular fa-copy"></i></button>
<div id="txtInfoClient">
    <h3> {{ "ID: $id_client" }}</h2>
    <h4> {{ "Client: $name $register" }}</h3>

    @if (null == $address)
    <p><b>Address:</b> -- </p>
    @else
        <p><b>Address:</b> {{$address}} <br>
        </p>
    @endif

    @if (null == $phone)
    <p><b>Phone:</b> -- </p>
    @else
    <b>Phone:</b>
        @php
            $phone = explode(",",$phone);
        @endphp

        @for ($i = 0; $i < count($phone); $i++)
            <p>{{$phone[$i]}}</p>
        @endfor
    @endif

    @if (null == $email)
    <p><b>Email:</b> -- </p>
    @else
    <b>email:</b>
        @php
            $email = explode(",",$email);
        @endphp

        @for ($i = 0; $i < count($email); $i++)
            <p>{{$email[$i]}}</p>
        @endfor
    @endif

    <h3> Observations</h2>
    <hr>
    @if (false == $obs)
        <p><b>No observations</b> -- </p>
    @else
        @foreach ($obs as $o)
            <p><b>{{ App\Traits\SystemTrait::dateEmMysqlReverse($o->created_at) }}:</b> {{$o->obs}}</p>
        @endforeach
        <hr>
    @endif
</div>
<script>
    var clipboard = new ClipboardJS('#copyInfoClient');
    clipboard.on('success', function (e) {
        bloquear()
        e.clearSelection();
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        Toast.fire({
            icon: 'success',
            title: 'Dados copiados com sucesso!'
        })
        desbloquear()
    });
</script>
