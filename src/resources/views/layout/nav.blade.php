<div id="openMenu" class="nav__toggle" @click.prevent="toggleMenu">
    {!! file_get_contents(public_path('vendor/larafolio/zondicons/menu.svg')) !!}
</div>
<transition name="slide" v-cloak>
    <nav class="nav" v-show="menuVisible">
        <div id="closeMenu" class="nav__toggle" @click.prevent="toggleMenu">
            {!! file_get_contents(public_path('vendor/larafolio/zondicons/menu.svg')) !!}
        </div>
        <div class="nav__section nav__left">
            <a href="/" class="nav__item">
                <span class="nav__icon">
                    {!! file_get_contents(public_path('vendor/larafolio/zondicons/home.svg')) !!}
                </span>
                <span class="">Home</span>
            </a>
            @if (Auth::check())
                <a href="{{ route('dashboard') }}" class="nav__item">
                    <span class="nav__icon">
                        {!! file_get_contents(public_path('vendor/larafolio/zondicons/dashboard.svg')) !!}
                    </span>
                    <span class="">Dashboard</span>
                </a>
                <a href="{{ route('add-project') }}" class="nav__item">
                    <span class="nav__icon">
                        {!! file_get_contents(public_path('vendor/larafolio/zondicons/compose.svg')) !!}
                    </span>
                    <span class="">Add</span>
                </a>
                @if (!$navProjects->isEmpty())
                    <div class="nav__dropdown">
                        <span class="nav__item">
                            <span class="nav__icon">
                                {!! file_get_contents(public_path('vendor/larafolio/zondicons/portfolio.svg')) !!}
                            </span>
                            Projects
                        </span>
                        <div class="nav__dropdown-content">
                            @foreach($navProjects as $project)
                                <a class="nav__link" href="{{ route(
                                    'show-project',
                                    ['project' => $project]
                                )}}">
                                    <div class="nav__dropdown-item">
                                        <span class="nav__dropdown-item-text">
                                            {{ $project->name() }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
        <div class="nav__section nav__center">
            {!! file_get_contents(public_path('vendor/larafolio/images/logo.svg')) !!}
            <div class="nav__brand">
                Larafoilo
            </div>
        </div>
        <div class="nav__section nav__right">
            @if (Auth::check())
                <a href="/logout" class="nav__item">
                    <span class="nav__icon">
                        {!! file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thick-left.svg')) !!}
                    </span>
                    <span class="">Logout</span>
                </a>
            @else
                <a href="/login" class="nav__item">
                    Login
                </a>
            @endif
        </div>
    </nav>
</transition>
