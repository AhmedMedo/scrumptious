@extends('layouts.public')

@section('title', 'About Us')
@section('meta_description', 'Learn about Scrumptious Recipe Planner — our mission, features, and how we help you organize cooking and meal planning.')

@section('hero')
<section class="hero">
    <div class="hero-badge">About Us</div>
    <h1>Welcome to Scrumptious<br><span>The Recipe Planner</span></h1>
    <p class="hero-sub">A mobile application designed to help you easily organize your cooking and meal planning — simply and beautifully.</p>
</section>
@endsection

@push('styles')
<style>
    .content {
        max-width: 1000px;
        margin: 0 auto;
        padding: 3rem 1.75rem 5rem;
        display: grid;
        gap: 1.75rem;
    }

    /* Card */
    .card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 2.25rem 2.5rem;
        box-shadow: 0 2px 16px rgba(0,0,0,.05);
    }
    .card-header {
        display: flex;
        align-items: center;
        gap: .9rem;
        margin-bottom: 1.25rem;
    }
    .card-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: var(--orange-md);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .card-icon svg { width: 21px; height: 21px; fill: var(--orange); }
    .card h2 { font-size: 1.2rem; font-weight: 700; letter-spacing: -.2px; }
    .card p, .card li { font-size: .965rem; color: var(--muted); line-height: 1.8; }
    .card p + p { margin-top: .65rem; }

    /* Features grid */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: .9rem;
        margin-top: .25rem;
    }
    .feature-item {
        background: var(--orange-lt);
        border: 1px solid #FED7AA;
        border-radius: 14px;
        padding: 1.25rem 1.35rem;
        display: flex;
        gap: .8rem;
        align-items: flex-start;
    }
    .feature-dot {
        width: 9px; height: 9px;
        border-radius: 50%;
        background: var(--orange);
        margin-top: .5rem;
        flex-shrink: 0;
    }
    .feature-item strong { display: block; font-size: .925rem; font-weight: 700; color: var(--text); margin-bottom: .2rem; }
    .feature-item span  { font-size: .855rem; color: var(--muted); line-height: 1.6; }

    /* Mission */
    .mission-card {
        background: linear-gradient(135deg, #FFF7ED, #FFEDD5);
        border: 1px solid #FED7AA;
        border-radius: 20px;
        padding: 2.75rem 2.5rem;
        text-align: center;
    }
    .mission-label {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #C2410C;
        margin-bottom: .9rem;
    }
    .mission-card blockquote {
        font-size: 1.125rem;
        font-weight: 500;
        color: var(--brown);
        line-height: 1.75;
        max-width: 640px;
        margin: 0 auto;
        font-style: italic;
    }
    .mission-card blockquote::before { content: '\201C'; }
    .mission-card blockquote::after  { content: '\201D'; }
    .mission-card p {
        margin-top: .9rem;
        font-size: .9rem;
        color: #9A3412;
        line-height: 1.7;
    }

    /* Contact */
    .contact-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 2.25rem 2.5rem;
        box-shadow: 0 2px 16px rgba(0,0,0,.05);
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        align-items: center;
        justify-content: space-between;
    }
    .contact-left h2 { font-size: 1.2rem; font-weight: 700; margin-bottom: .3rem; }
    .contact-left p  { font-size: .9rem; color: var(--muted); }
    .contact-links   { display: flex; flex-direction: column; gap: .55rem; }
    .contact-link {
        display: flex;
        align-items: center;
        gap: .55rem;
        font-size: .9rem;
        font-weight: 600;
        color: var(--orange);
        text-decoration: none;
        transition: opacity .15s;
    }
    .contact-link:hover { opacity: .75; }
    .contact-link svg { width: 15px; height: 15px; fill: currentColor; flex-shrink: 0; }
    .contact-link.muted { color: var(--muted); font-weight: 500; }

    @media (max-width: 600px) {
        .card, .contact-card { padding: 1.5rem; }
        .contact-card { flex-direction: column; }
    }
</style>
@endpush

@section('content')
<div class="content">

    {{-- What the app does --}}
    <div class="card">
        <div class="card-header">
            <div class="card-icon">
                <svg viewBox="0 0 24 24"><path d="M18.06 22.99h1.66c.84 0 1.53-.64 1.63-1.46L23 5.05h-5V1h-1.97v4.05h-4.97l.3 2.34c1.71.47 3.31 1.32 4.27 2.26 1.44 1.42 2.43 2.89 2.43 5.29v8.05zM1 21.99V21h15.03v.99c0 .55-.45 1-1.01 1H2.01c-.56 0-1.01-.45-1.01-1zm15.03-7c0-8-15.03-8-15.03 0h15.03zM1.02 17h15v2h-15z"/></svg>
            </div>
            <h2>What Our App Does</h2>
        </div>
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-dot"></div>
                <div>
                    <strong>Recipe Management</strong>
                    <span>Add, edit, and store your favorite recipes in one organized place.</span>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-dot"></div>
                <div>
                    <strong>Meal Planning</strong>
                    <span>Plan meals for the day or week to stay organized and save time.</span>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-dot"></div>
                <div>
                    <strong>Grocery Lists</strong>
                    <span>Automatically create shopping lists based on your planned meals.</span>
                </div>
            </div>
        </div>
        <p style="margin-top:1.1rem;">Designed to help individuals and families stay organized in the kitchen and make meal preparation easier.</p>
    </div>

    {{-- Mission --}}
    <div class="mission-card">
        <p class="mission-label">Our Mission</p>
        <blockquote>
            To provide a simple and practical tool that helps people manage recipes, plan meals, and organize grocery shopping without unnecessary complexity.
        </blockquote>
        <p>We focus on building easy-to-use features that improve the everyday cooking and meal planning experience.</p>
    </div>

    {{-- Privacy --}}
    <div class="card">
        <div class="card-header">
            <div class="card-icon">
                <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4l5 2.18V11c0 3.5-2.33 6.79-5 7.93-2.67-1.14-5-4.43-5-7.93V7.18L12 5z"/></svg>
            </div>
            <h2>Data &amp; Privacy</h2>
        </div>
        <p>We respect the privacy of our users. Any information stored in the application — such as recipes, meal plans, and grocery lists — is used solely to provide the core functionality of the app.</p>
        <p>For more details, please review our <a href="{{ route('privacy-policy') }}" style="color:var(--orange);font-weight:600;text-decoration:none;">Privacy Policy</a>.</p>
    </div>

    {{-- Contact --}}
    <div class="contact-card">
        <div class="contact-left">
            <h2>Contact Us</h2>
            <p>Have questions, feedback, or suggestions?</p>
        </div>
        <div class="contact-links">
            <a href="mailto:scrumptiousreceipeplanner@gmail.com" class="contact-link">
                <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg>
                scrumptiousreceipeplanner@gmail.com
            </a>
            <span class="contact-link muted">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
                Scrumptious Recipe Planner
            </span>
        </div>
    </div>

</div>
@endsection
