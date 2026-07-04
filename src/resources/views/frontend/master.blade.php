<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

        @stack('before-style')
		<link href="{{ asset('assets/css/output.css') }}" rel="stylesheet" />
		<link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

        <link rel="icon" href="{{ asset('assets/images/logos/logo.png') }}">

        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            html {
                scroll-behavior: smooth;
            }
        </style>


        @stack('after-style')
	</head>

    @yield('content')


    @stack('before-script')
    <script src="{{ asset('assets/js/two-lines-text.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- JavaScript -->
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
    <script src="{{ asset('assets/js/carousel.js') }}"></script>

    @stack('after-script')

</html>
