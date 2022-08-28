<header class="app-header">
    <nav class="app-header__navbar">
        <a href="{{ route('index.index') }}" class="app-header__title-link"><img src="/images/patrimag-logo.png" alt="Logo Patri-Mag"></a>

        <div class="app-header__content">
            <div style="display: flex; gap: 20px;">
                <div class="app-header__links-container">
                    <a href="{{ route('article.show-all') }}" class="app-header__link">Nos articles</a>
                    <a href="{{ route('contact.view') }}" class="app-header__link">Nous contacter</a>
                </div>
            </div>

            <div class="app-header__security-links">
                @guest
                    <a href="{{ route('security.register-view') }}" class="app-header__link icon"><i class="ri-user-add-line"></i></a>
                    <a href="{{ route('security.login-view') }}" class="app-header__link icon"><i class="ri-login-box-line"></i></a>
                @endguest
                @auth
                    <a href="{{ route('security.profile') }}" class="app-header__link"><span class="hello"></span> <strong>{{ auth()->user()->name }}</strong></a>
                    <a href="{{ route('security.logout') }}" class="app-header__link icon"><i class="ri-logout-box-line"></i></a>
                @endauth
            </div>
        </div>
    </nav>
</header>
