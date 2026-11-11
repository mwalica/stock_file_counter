<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @fluxAppearance
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800 antialiased">

<flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>
    <flux:navbar class="-mb-px max-lg:hidden">
        <flux:navbar.item icon="square-text" href="{{route('photo.index')}}" wire:navigate>Photos</flux:navbar.item>
        <flux:navbar.item icon="chart-spline" href="{{route('summary.index')}}" wire:navigate>Summary</flux:navbar.item>
        <flux:separator vertical class="my-2" />
        <form method="POST" action="{{route('logout')}}" class="inline">
            @csrf
            <flux:navbar.item icon="log-out" type="submit" as="button">Logout</flux:navbar.item>
        </form>
    </flux:navbar>
    <flux:spacer />
    <flux:switch x-data x-model="$flux.dark" label="Dark model"/>
</flux:header>

<flux:main container class="lg:mt-0">
    {{ $slot }}
</flux:main>
<flux:toast position="bottom center" class="pt-12"/>
@livewireScripts
@fluxScripts
</body>
</html>
