<header class="app-header">
    <nav class="app-header__navbar">
        <a href="{{ route('index.index') }}" class="app-header__title-link"><img src="/images/patrimag-logo.png"
                                                                                 alt="Logo Patri-Mag"></a>

        <div class="app-header__content">
            <div style="display: flex; gap: 20px;">
                <div class="app-header__links-container">
                    <a href="{{ route('admin.index') }}" class="app-header__link">Panneau d'administration</a>
                    <a href="{{ route('index.index') }}" class="app-header__link">Quitter l'administration</a>
                </div>
            </div>

            <div class="app-header__security-links">
                <a href="{{ route('security.profile') }}" class="app-header__link"><span class="hello"></span>
                    <strong>{{ auth()->user()->name }}</strong></a>
                <a href="{{ route('security.logout') }}" class="app-header__link icon"><i
                        class="ri-logout-box-line"></i></a>
            </div>
        </div>
    </nav>
</header>
