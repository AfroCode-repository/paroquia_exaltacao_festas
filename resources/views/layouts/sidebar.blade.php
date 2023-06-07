<nav id="sidebar" class="sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route("dashboard") }}">
            <img src="{{ asset('img/allima-logo.svg') }}" alt="SysClean" width="30" height="30" viewBox="0 0 20 20" enable-background="new 0 0 20 20" xml:space="preserve" />
            <span class="align-middle me-3">Sys Clean</span>
        </a>

        <ul class="sidebar-nav">
            {{--<li class="sidebar-header">
                Pages
            </li>--}}
            @if ('home' == $active)
                <li class="sidebar-item active">
            @else
                <li class="sidebar-item">
            @endif
                <a class="sidebar-link next" href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-house"></i> Home
                </a>
            </li>

            <li class="sidebar-item">
                <a data-bs-target="#sideBarInvoice" data-bs-toggle="collapse" class="sidebar-link collapsed">
                    <i class="fas fa-hand-holding-usd"></i> <span class="align-middle"> Invoices</span>
                </a>
                <ul id="sideBarInvoice" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item @if ('listInvoices' == $active) active @endif">
                        <a class="sidebar-link next" href="">
                            <i class="fas fa-money-check"></i> Listar Invoice
                        </a>
                    </li>
                </ul>
            </li>

        </ul>

    </div>
</nav>
