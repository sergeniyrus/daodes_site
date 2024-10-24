<nav x-data="{ open: false }" class="header-menu">
    <!-- Primary Navigation Menu -->
    <div class="header-menu-cont-bord">
        <div class="flex justify-between h-12">
            <div class="flex link-name">

                <!-- Navigation Links для мониторов -->
                <div class="hidden space-x-3 sm:ms-2 sm:flex flex justify-between">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('news')" :active="request()->routeIs('news')">
                        {{ __('News') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dao')" :active="request()->routeIs('dao')">
                        {{ __('DAO') }}
                    </x-nav-link>
                    @auth
                        <?php
                        $rol = DB::table('users')
                            ->where('name', Auth::user()->name)
                            ->select('rang_access')
                            ->first();
                        ?>
                        @if ($rol->rang_access >= 3)
                            <x-nav-link :href="route('add_news')" :active="request()->routeIs('add_news')">
                                {{ __('Create News') }}
                            </x-nav-link>
                        @endif
                        <x-nav-link :href="route('add_offers')" :active="request()->routeIs('add_offers')">
                            {{ __('Create Offer') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        @if (Route::has('login'))
                            @auth
                                <div class="link-name">
                                    <a href="#">{{ Auth::user()->name }} &#9776;</a>
                                </div>
                            @else
                                <div class="link-name">
                                    <x-nav-link :href="route('login')">
                                        {{ __('Login') }}
                                    </x-nav-link>
                                    @if (Route::has('register'))
                                        <x-nav-link :href="route('register')">
                                            {{ __('Register') }}
                                        </x-nav-link>
                                    @endif
                                </div>
                            @endauth
                        @endif
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('wallet.wallet')">
                            {{ __('Wallet') }}
                        </x-dropdown-link>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="header-2">
                <!-- Hamburger -->
                <div class="header-logo-2">
                    <a href="/"><img src="/img/main/daodes.jpg" alt=""
                            class="border border-solid border-t-red-50 rounded" /></a>
                </div>
                <!-- описание сайта рекламный слоган можно сделать динамичным -->
                <div class="header-title-2">
                    <span class="logo_name-2">DAO DES</span>
                </div>
            </div>

            <div class="sm:hidden flex justify-between">
                <div class="link-name link-hamgurg justify-between">
                    <a href="#" @click="open = ! open" class="justify-center align-items-center flex: 1;">
                        MENU &#9776;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Отзывчивое меню навигации -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-black">
        <div class="pt-1 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('news')" :active="request()->routeIs('news')">
                    {{ __('News') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dao')" :active="request()->routeIs('dao')">
                    {{ __('DAO') }}
                </x-responsive-nav-link>
                @auth
                    @if ($rol->rang_access >= 3)
                        <x-responsive-nav-link :href="route('add_news')" :active="request()->routeIs('add_news')">
                            {{ __('Create News') }}
                        </x-responsive-nav-link>
                    @endif
                    <x-responsive-nav-link :href="route('add_offers')" :active="request()->routeIs('add_offers')">
                        {{ __('Create Offer') }}
                    </x-responsive-nav-link>
                @endauth
                <hr>
                @if (Route::has('login'))
                    @auth
                        <div class="menu-hamburg">{{ Auth::user()->name }}</div>
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('wallet.wallet')">
                            {{ __('Wallet') }}
                        </x-responsive-nav-link>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    @else
                        <x-responsive-nav-link :href="route('login')">
                            {{ __('Login') }}
                        </x-responsive-nav-link>
                        @if (Route::has('register'))
                            <x-responsive-nav-link :href="route('register')">
                                {{ __('Register') }}
                            </x-responsive-nav-link>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>
