<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">

            <li class="nav-item dropdown">


                {{--<a class="nav-link d-none d-sm-inline-block">
                    <span class="text-dark"> <img src="{{ asset($languageImg) }}" alt="user-image" class="me-0 me-sm-1" height="12"></span>
                    <span class="align-middle d-none d-sm-inline-block">{{ $languageCountry }}</span> <i class="fas fa-globe-americas"></i>
                </a>--}}

            </li>

            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle" data-feather="settings"></i>
                </a>

                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                    <span class="text-dark">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    {{--<a class="dropdown-item" href="pages-profile.html"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="pages-settings.html">Settings & Privacy</a>
                    <a class="dropdown-item" href="#">Help</a>--}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link class="dropdown-item" :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Logout') }}
                        </x-dropdown-link>
                    </form>

                </div>
            </li>
        </ul>
    </div>
</nav>
