<header class="app-header">
    <nav class="navbar app-navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="https://bulma.io">
                <h2 class="title is-3">Patri-Mag</h2>
            </a>

            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item">
                    {{ __('components/header.articles') }}
                </a>

                <a class="navbar-item">
                    {{ __('components/header.who-are-we') }}
                </a>

                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                        {{ __('components/header.contact') }}
                    </a>

                    <div class="navbar-dropdown">
                        <a class="navbar-item">
                            {{ __('components/header.contact.form') }}
                        </a>
                        <a class="navbar-item">
                            {{ __('components/header.contact.email') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="buttons">
                        <a class="button is-primary">
                            <strong>{{ __('components/header.create-account') }}</strong>
                        </a>
                        <a class="button is-light">
                            {{ __('components/header.login') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
