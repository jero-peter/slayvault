<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{config('app.name')}}</title>
        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body class="antialiased bg-dark">
        <div class="row-fluid text-center">
            <div class="col-lg-12 mx-auto">
                <img class="d-block mx-auto my-5" src="https://media.giphy.com/media/tBlTkwiRUSMeIxjOAv/giphy.gif" >
                <h1 class="text-white text-center mt-4">Welcome to SlayVault</h1>
                <h6 class="text-white text-center">Saaslay's Official IdP & Account Management Application.</h6>
                @auth
                    <a href="/home" class="btn btn-danger text-white my-2">Open SlayVault</a>
                @endauth
            </div>
        </div>
    </body>
</html>
