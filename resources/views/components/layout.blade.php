<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Принтеры</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-black text-white font-hanken-grotesk pb-10">
    <div class="px-10">
        <nav class="flex justify-between items-center py-4 border-b border-white/10">
            <div class="flex gap-5 items-center">
                <a href="/">
                    <img class="w-[42px]" src="{{ Vite::asset('resources/images/logo.png') }}" alt="">
                </a>

                <x-forms.link href="/">На главную</x-forms.link>
                <x-forms.link href="/all">Весь список</x-forms.link>
            </div>

            @auth
                <div class="space-x-6 font-bold flex">
                    <a href="/printers/create">Добавить</a>

                    <form method="POST" action="/logout">
                        @csrf
                        @method('DELETE')

                        <button class="text-red-500">Выйти</button>
                    </form>
                </div>
            @endauth

            @guest
                <div class="space-x-6 font-bold">
                    <a href="/login">Войти</a>
                </div>
            @endguest
        </nav>

        <main class="">
            {{ $slot }}
        </main>
    </div>

</body>

</html>
