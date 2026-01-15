<nav class="bg-white px-6 py-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <a href="/" class="font-bold text-2xl text-gray-800 hover:text-blue-600 transition-colors duration-200">
            Shop Fashion
        </a>

        <ul class="hidden md:flex gap-8 text-lg font-medium">
            <li><a href="/" class="text-gray-700 hover:text-blue-600 transition-colors">Home</a></li>
            <li><a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Product</a></li>
            <li><a href="{{route('about')}}" class="text-gray-700 hover:text-blue-600 transition-colors">About Us</a></li>
        </ul>

        <div class="flex items-center space-x-4">
            <form action="{{ route('products.index') }}" method="GET" class="hidden sm:block relative">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search..."
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-full w-64"
                >
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>


            <div class="flex items-center space-x-4 text-gray-700">
                <a href="{{ route('cart.index') }}" class="relative hover:text-blue-600 transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13v.01" />
                    </svg>

                    @auth
                        @if($cartCount > 0)
                            <span
                                class="absolute -top-2 -right-2 min-w-[20px] h-5 px-1
                                    flex items-center justify-center
                                    text-xs font-bold text-white
                                    bg-red-600 rounded-full">
                                {{ $cartCount }}
                            </span>
                        @endif
                    @endauth
                </a>


                 {{-- User --}}
                @guest
                    {{-- Display Login when don't login--}}
                    <a href="{{ route('login') }}" class="hover:text-blue-600 transition-colors">
                        Login
                    </a>
                @endguest

                @auth
                     <span class="text-gray-700">Hi, {{ auth()->user()->name }}</span>
                    {{-- Display Logout when logged --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="hover:text-red-600 transition-colors">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>