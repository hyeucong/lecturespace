<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LectureSpace</title>
        <!-- Styles / Scripts -->
        @include('partials.head')
    </head>

    <body class="bg-gradient-to-b from-[#FDFDFC] to-[#F8F8F6] text-[#1b1b18] min-h-screen flex flex-col">
        <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 mb-8 md:mb-12">
            <div class="flex justify-between items-center">
                <nav class="text-[#1b1b18] font-bold text-xl sm:text-2xl">
                    <a href="/" class="flex items-center">
                        <span class="text-green-800">lecture</span><span>space</span>
                    </a>
                </nav>

                <!-- Navigation (visible on all screen sizes) -->
                <nav class="font-medium flex items-center">
                    @if (Route::has('login'))
                        @auth
                            <flux:button>
                                <a href="{{ url('/courses') }}" class="py-2">
                                    Dashboard
                                </a>
                            </flux:button>
                        @else
                            <flux:button>
                                <a href="{{ route('login') }}" class="py-2">
                                    Log in
                                </a>
                            </flux:button>
                        @endauth
                    @endif
                </nav>
            </div>
        </header>

        <main class="flex-grow">
            <x-layouts.client.hero />
            <flux:separator />
            <x-layouts.client.problem />
            <x-layouts.client.review />
            <x-layouts.client.product />
        </main>

        <footer>
            <flux:separator />
            <x-layouts.client.footer />
        </footer>
    </body>

</html>
