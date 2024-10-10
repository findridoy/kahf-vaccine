<x-guest-layout>
    <main class="">
        <div class="flex flex-col items-center">
            <h1 class="text-3xl mb-8">Find My Vaccine</h1>
            <h6 class="text-center my-2">Enter your NID number below to check your vaccine status</h6>
            <form action="{{route('search')}}" method="get" class="flex justify-center gap-4 mb-8">
                <x-text-input type="number" name="nid" placeholder="Insert NID Number Here" required class="w-64" />
                <x-primary-button type="submit">Search</x-primary-button>
            </form>
            <div class="text-center">
                @if (Session::has('status'))
                    <div class="mb-2">
                        <span class="rounded-full px-3 py-1 text-white text-lg font-semibold bg-green-500">
                            {{ Session::get('status') }}
                        </span>
                    </div>
                    @if (Session::get('status') == 'Not registered')
                        <a href="{{ route('register') }}"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                            Register Now
                        </a>
                    @endif
                @else
                    <div>&nbsp;</div>
                    <div>&nbsp;</div>
                @endif
            </div>
        </div>

    </main>
</x-guest-layout>
