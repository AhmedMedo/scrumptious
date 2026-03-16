<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Scrumptious') – Scrumptious Recipe Planner</title>
    <meta name="description" content="@yield('meta_description', 'Scrumptious Recipe Planner – organize your cooking, meal planning and grocery lists.')">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet"/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --orange:     #F97316;
            --orange-dk:  #EA6C0A;
            --orange-lt:  #FFF7ED;
            --orange-md:  #FFEDD5;
            --brown:      #7C2D12;
            --text:       #1C1917;
            --muted:      #78716C;
            --border:     #E7E5E4;
            --white:      #FFFFFF;
            --nav-h:      64px;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
            background: var(--orange-lt);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        /* ═══════════════════════════════
           NAVBAR
        ═══════════════════════════════ */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            height: var(--nav-h);
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
        }
        .nav-inner {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1.75rem;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        /* Brand */
        .nav-brand {
            display: flex;
            align-items: center;
            gap: .55rem;
            text-decoration: none;
            flex-shrink: 0;
        }
        .brand-logo {
            width: 38px;
            height: 38px;
            object-fit: contain;
            border-radius: 10px;
        }
        .brand-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -.3px;
        }

        /* Nav links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: .25rem;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .845rem;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            padding: .45rem .8rem;
            border-radius: 9px;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }
        .nav-link svg { width: 14px; height: 14px; fill: currentColor; flex-shrink: 0; }
        .nav-link:hover { background: var(--orange-md); color: var(--brown); }
        .nav-link.active {
            background: var(--orange-md);
            color: var(--orange-dk);
            font-weight: 600;
        }

        /* Hamburger (mobile) */
        .nav-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: .4rem;
            border: none;
            background: none;
        }
        .nav-toggle span {
            display: block;
            width: 22px;
            height: 2px;
            background: var(--muted);
            border-radius: 2px;
            transition: transform .25s, opacity .25s;
        }

        /* ═══════════════════════════════
           HERO (yielded per-page)
        ═══════════════════════════════ */
        .hero {
            background: linear-gradient(135deg, #7C2D12 0%, #C2410C 55%, #F97316 100%);
            color: white;
            text-align: center;
            padding: 4.5rem 1.75rem 4rem;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.045'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }
        .hero > * { position: relative; }
        .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,.14);
            border: 1px solid rgba(255,255,255,.28);
            border-radius: 999px;
            padding: .32rem .95rem;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            margin-bottom: 1.1rem;
        }
        .hero h1 {
            font-size: clamp(1.85rem, 4.5vw, 3rem);
            font-weight: 800;
            letter-spacing: -.5px;
            line-height: 1.15;
            margin-bottom: .75rem;
        }
        .hero h1 span { font-weight: 300; opacity: .8; }
        .hero-sub {
            font-size: .95rem;
            opacity: .82;
            max-width: 540px;
            margin: 0 auto;
            line-height: 1.7;
        }
        .hero-meta {
            font-size: .825rem;
            opacity: .65;
            margin-top: .5rem;
        }

        /* ═══════════════════════════════
           MAIN CONTENT SLOT
        ═══════════════════════════════ */
        main { flex: 1; }

        /* ═══════════════════════════════
           FOOTER
        ═══════════════════════════════ */
        .site-footer {
            background: var(--white);
            border-top: 1px solid var(--border);
            padding: 2.5rem 1.75rem;
        }
        .footer-inner {
            max-width: 1000px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
        }
        .footer-brand {
            display: flex;
            align-items: center;
            gap: .6rem;
            text-decoration: none;
        }
        .footer-brand-logo {
            width: 32px;
            height: 32px;
            object-fit: contain;
            border-radius: 8px;
        }
        .footer-brand-name {
            font-size: .9rem;
            font-weight: 700;
            color: var(--text);
        }
        .footer-copy {
            font-size: .8rem;
            color: var(--muted);
            margin-top: .2rem;
        }
        .footer-nav {
            display: flex;
            flex-wrap: wrap;
            gap: .25rem;
            align-items: center;
        }
        .footer-link {
            display: flex;
            align-items: center;
            gap: .35rem;
            font-size: .825rem;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            padding: .35rem .7rem;
            border-radius: 8px;
            transition: background .15s, color .15s;
        }
        .footer-link svg { width: 13px; height: 13px; fill: currentColor; }
        .footer-link:hover { background: var(--orange-md); color: var(--brown); }
        .footer-link.active { color: var(--orange-dk); font-weight: 600; }
        .footer-sep {
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: var(--border);
        }

        /* ═══════════════════════════════
           MOBILE
        ═══════════════════════════════ */
        @media (max-width: 640px) {
            .nav-toggle { display: flex; }
            .nav-links {
                display: none;
                position: absolute;
                top: var(--nav-h);
                left: 0; right: 0;
                background: white;
                border-bottom: 1px solid var(--border);
                flex-direction: column;
                align-items: stretch;
                padding: .75rem 1rem;
                gap: .2rem;
                box-shadow: 0 8px 24px rgba(0,0,0,.08);
            }
            .nav-links.open { display: flex; }
            .nav-link { font-size: .9rem; padding: .6rem .85rem; }
            .footer-inner { flex-direction: column; align-items: flex-start; }
            .footer-nav { gap: .15rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- ══ NAVBAR ══ --}}
    <header class="navbar">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="nav-brand">
                <img src="/images/logo.png" alt="Scrumptious Logo" class="brand-logo">
                <span class="brand-name">Scrumptious</span>
            </a>

            <button class="nav-toggle" onclick="this.nextElementSibling.classList.toggle('open')" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>

            <nav class="nav-links">
                <a href="{{ route('about-us') }}"
                   class="nav-link {{ request()->routeIs('about-us') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    About Us
                </a>
                <a href="{{ route('privacy-policy') }}"
                   class="nav-link {{ request()->routeIs('privacy-policy') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4l5 2.18V11c0 3.5-2.33 6.79-5 7.93-2.67-1.14-5-4.43-5-7.93V7.18L12 5z"/></svg>
                    Privacy Policy
                </a>
                <a href="{{ route('terms-and-conditions') }}"
                   class="nav-link {{ request()->routeIs('terms-and-conditions') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm-1 7V3.5L18.5 9H13zM8 16h8v2H8zm0-4h8v2H8zm0-4h5v2H8z"/></svg>
                    Terms &amp; Conditions
                </a>
            </nav>
        </div>
    </header>

    {{-- ══ HERO ══ --}}
    @yield('hero')

    {{-- ══ CONTENT ══ --}}
    <main>
        @yield('content')
    </main>

    {{-- ══ FOOTER ══ --}}
    <footer class="site-footer">
        <div class="footer-inner">
            <div>
                <a href="{{ route('home') }}" class="footer-brand">
                    <img src="/images/logo.png" alt="Scrumptious Logo" class="footer-brand-logo">
                    <span class="footer-brand-name">Scrumptious</span>
                </a>
                <p class="footer-copy">&copy; {{ date('Y') }} Scrumptious Recipe Planner. All rights reserved.</p>
            </div>

            <nav class="footer-nav">
                <a href="{{ route('about-us') }}"
                   class="footer-link {{ request()->routeIs('about-us') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    About Us
                </a>
                <div class="footer-sep"></div>
                <a href="{{ route('privacy-policy') }}"
                   class="footer-link {{ request()->routeIs('privacy-policy') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4l5 2.18V11c0 3.5-2.33 6.79-5 7.93-2.67-1.14-5-4.43-5-7.93V7.18L12 5z"/></svg>
                    Privacy Policy
                </a>
                <div class="footer-sep"></div>
                <a href="{{ route('terms-and-conditions') }}"
                   class="footer-link {{ request()->routeIs('terms-and-conditions') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm-1 7V3.5L18.5 9H13zM8 16h8v2H8zm0-4h8v2H8zm0-4h5v2H8z"/></svg>
                    Terms &amp; Conditions
                </a>
            </nav>
        </div>
    </footer>

</body>
</html>
