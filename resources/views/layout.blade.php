<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="google-site-verification" content="6Ghhbm9YgakaFOh-jTygdfzHd4SDvva6VlwgSSFf4AI" />

        {{-- Vuetify --}}
        <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.min.css" rel="stylesheet">

        <title>Laravel</title>
    </head>
    <body>
        <div id="app">
            <v-app>
                <sidebar
                    :user="{{ json_encode($user) }}"
                    :urls="{{ json_encode($urls) }}"
                    :options="{{ json_encode($options) }}"
                ></sidebar>
                <toolbar></toolbar>

                <v-content class="white">
                    <v-container fluid fill-height>
                        <v-layout
                                justify-center
                        >
                            <v-flex text-xs-center>
                                @yield('content')
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-content>
            </v-app>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>
        <script>
            // Register service worker
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('{{ asset('sw.js') }}')
                        .then(registration => navigator.serviceWorker.ready)
                        .then(registration => {
                            console.log('Service worker registered');
                        }, () => {
                            console.log('Service worker registered failed');
                        });
                });
            }
        </script>
    </body>
</html>
