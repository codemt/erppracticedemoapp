<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#ffffff">
        {{-- <script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
        <script>tinymce.init({ selector:'textarea' });</script> --}}
        <title>Admin</title>
        <!--  Css files  -->
        @include('admin.layout.web_header')
        <?=Html::style('backend/css/custom.css',[], IS_SECURE)?>
        @yield('style')
    </head>
    <body class="sidebar-mini fixed">
        <div class="wrapper">
            @yield('start_form')
            <!-- Header file -->
            @include('admin.layout.header')
            <!-- Side-Bar-->
            @include('admin.layout.sidebar')
            <!-- Content Start here -->
            <div class="content-wrapper">
                @yield('content')
            </div>
            <!-- Content End here -->
            <!-- footer contant -->
            @yield('end_form')
        </div>
        <!-- Js file -->
        @include('admin.layout.footer')
        <script type="text/javascript">
            $('#loader').hide();
        </script>
    </body>
</html>