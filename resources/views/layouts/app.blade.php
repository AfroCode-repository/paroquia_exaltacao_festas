<!DOCTYPE html>
<html lang="pt">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Sistema para controle Sys clean">

    <title> @if (isset($title_page)){{$title_page}} | @endif</title>

	<link rel="shortcut icon" href="{{ asset('img/allima-logo.svg') }}">

    {{--<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">

	<link class="js-stylesheet" href="{{ asset('css/light.css') }}" rel="stylesheet">
	{{--<link class="js-stylesheet" href="{{ asset('css/plugins/loading.min.css') }}" rel="stylesheet">--}}
	<link class="js-stylesheet" href="{{ asset('css/plugins/sweetalert2.min.css') }}" rel="stylesheet">
	{{--<link class="js-stylesheet" href="{{ asset('css/plugins/datepicker.min.css') }}" rel="stylesheet">--}}
	<link class="js-stylesheet" href="{{ asset('css/plugins/bootstrap-datepicker.min.css') }}" rel="stylesheet">
	<link class="js-stylesheet" href="{{ asset('storage/css/sysApp.css') }}?v={{ Storage::lastModified('public/css/sysApp.css') }}" rel="stylesheet">
	{{--<link class="js-stylesheet" href="{{ asset('fontawesome.all.min.css') }}" rel="stylesheet">--}}

    @yield('CssPersonalizado')

</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-behavior="sticky">
	<div class="wrapper">

		@include('layouts.sidebar')

		<div class="main">
            @include('layouts.navibar')

            <main class="content">
                <div class="container-fluid p-0">
                    @yield('main')
                </div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-end">
							<p class="mb-0">
								&copy; <?=date("Y")?> - AfroCode
							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>
    <script>
        const base_URL = `{{env('APP_URL')}}`
    </script>
	<script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/plugins/blockUI.js') }}"></script>
    <script src="{{ asset('js/plugins/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/plugins/mask.min.js') }}"></script>
    <script src="{{ asset('js/plugins/inputmask.min.js') }}"></script>
    <script src="{{ asset('js/plugins/clipboard.min.js') }}"></script>
    <script src="{{ asset('storage/js/sysApp.js') }}?v={{ Storage::lastModified('public/js/sysApp.js') }}"></script>

    <div class="modal fade" id="modalesquisaCliente" tabindex="-1" role="dialog" aria-hidden="true" data-target="#modalesquisaCliente">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div id="HtmlPesquisaCliente">
                </div>
            </div>
        </div>
    </div>
    @yield('JsPersonalizado')

</body>

</html>
