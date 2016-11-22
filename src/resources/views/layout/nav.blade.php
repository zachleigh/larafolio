<nav class="nav">
    <div class="nav__section nav__left">
        <a href="/" class="nav__item">
            <span class="nav__icon">
                {!! file_get_contents(public_path('vendor/larafolio/zondicons/home.svg')) !!}
            </span>
            Home
        </a>
        @if (Auth::check())
            <a href="{{ route('dashboard') }}" class="nav__item">
                <span class="nav__icon">
                    {!! file_get_contents(public_path('vendor/larafolio/zondicons/dashboard.svg')) !!}
                </span>
                Dashboard
            </a>
            <a href="{{ route('add-project') }}" class="nav__item">
                <span class="nav__icon">
                    {!! file_get_contents(public_path('vendor/larafolio/zondicons/compose.svg')) !!}
                </span>
                Add
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
    <div class="nav__section nav__right">
        @if (Auth::check())
            <a href="/logout" class="nav__item">
                <span class="nav__icon">
                    {!! file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thick-left.svg')) !!}
                </span>
                Logout
            </a>
        @else
            <a
                href="/login"
                class="nav__item"
            >
                Login
            </a>
        @endif
    </div>
</nav>