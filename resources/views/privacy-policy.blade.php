@extends('layouts.public')

@section('title', 'Privacy Policy')
@section('meta_description', 'Scrumptious Recipe Planner Privacy Policy — how we collect, use, and protect your information.')

@section('hero')
<section class="hero">
    <div class="hero-badge">Legal</div>
    <h1>Privacy Policy</h1>
    <p class="hero-meta">Last updated: 13 March 2026</p>
</section>
@endsection

@push('styles')
<style>
    .layout {
        max-width: 1000px;
        margin: 0 auto;
        padding: 3rem 1.75rem 5rem;
        display: grid;
        grid-template-columns: 210px 1fr;
        gap: 2.5rem;
        align-items: start;
    }

    /* TOC */
    .toc {
        position: sticky;
        top: calc(var(--nav-h) + 1rem);
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,.04);
    }
    .toc-title {
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: .8rem;
    }
    .toc ul { list-style: none; display: flex; flex-direction: column; gap: .15rem; }
    .toc a {
        display: block;
        font-size: .8rem;
        color: var(--muted);
        text-decoration: none;
        padding: .3rem .55rem;
        border-radius: 8px;
        line-height: 1.45;
        transition: background .15s, color .15s;
    }
    .toc a:hover { background: var(--orange-md); color: var(--brown); }

    /* Article */
    .article { display: flex; flex-direction: column; gap: 1.5rem; }
    .section-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 2rem 2.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,.04);
        scroll-margin-top: calc(var(--nav-h) + 1rem);
    }
    .section-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px; height: 26px;
        background: var(--orange);
        border-radius: 7px;
        color: white;
        font-size: .72rem;
        font-weight: 700;
        margin-bottom: .7rem;
    }
    .section-card h2 { font-size: 1.1rem; font-weight: 700; margin-bottom: .8rem; letter-spacing: -.2px; }
    .section-card p, .section-card li { font-size: .945rem; color: var(--muted); line-height: 1.8; }
    .section-card p + p { margin-top: .65rem; }
    .section-card ul { list-style: none; display: flex; flex-direction: column; gap: .35rem; margin-top: .55rem; }
    .section-card li { display: flex; gap: .55rem; align-items: flex-start; }
    .section-card li::before {
        content: '';
        width: 7px; height: 7px;
        border-radius: 50%;
        background: var(--orange);
        margin-top: .57rem;
        flex-shrink: 0;
    }
    .sub-title { font-size: .82rem; font-weight: 700; color: var(--text); margin-top: 1rem; margin-bottom: .35rem; }

    .contact-link {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        color: var(--orange);
        font-weight: 600;
        text-decoration: none;
        font-size: .945rem;
    }
    .contact-link:hover { opacity: .75; }
    .contact-link svg { width: 14px; height: 14px; fill: currentColor; }

    @media (max-width: 700px) {
        .layout { grid-template-columns: 1fr; }
        .toc { position: static; }
        .section-card { padding: 1.5rem; }
    }
</style>
@endpush

@section('content')
<div class="layout">

    <aside class="toc">
        <p class="toc-title">On this page</p>
        <ul>
            <li><a href="#s1">1. Introduction</a></li>
            <li><a href="#s2">2. Information We Collect</a></li>
            <li><a href="#s3">3. How We Use Information</a></li>
            <li><a href="#s4">4. Data Storage</a></li>
            <li><a href="#s5">5. Third-Party Services</a></li>
            <li><a href="#s6">6. Children's Privacy</a></li>
            <li><a href="#s7">7. Your Rights</a></li>
            <li><a href="#s8">8. Policy Changes</a></li>
            <li><a href="#s9">9. Contact Us</a></li>
        </ul>
    </aside>

    <article class="article">

        <div class="section-card" id="s1">
            <div class="section-num">1</div>
            <h2>Introduction</h2>
            <p>Welcome to <strong>Scrumptious Recipe Planner</strong>.</p>
            <p>This Privacy Policy explains how we collect, use, and protect your information when you use our mobile application available on the Google Play Store.</p>
            <p>By using the application, you agree to the collection and use of information in accordance with this policy.</p>
        </div>

        <div class="section-card" id="s2">
            <div class="section-num">2</div>
            <h2>Information We Collect</h2>
            <p class="sub-title">a) Personal Information</p>
            <p>Our application may collect the following information if provided by the user:</p>
            <ul>
                <li>Email address (if account creation is required)</li>
                <li>User-created content such as recipes, meal plans, and grocery lists</li>
            </ul>
            <p class="sub-title">b) Automatically Collected Information</p>
            <p>When you use the application, certain information may be collected automatically:</p>
            <ul>
                <li>Device type</li>
                <li>Operating system version</li>
                <li>App usage statistics</li>
                <li>Crash reports</li>
            </ul>
            <p style="margin-top:.7rem;">This information is used only to improve app performance and user experience.</p>
        </div>

        <div class="section-card" id="s3">
            <div class="section-num">3</div>
            <h2>How We Use the Information</h2>
            <p>We use the collected information for the following purposes:</p>
            <ul>
                <li>To allow users to create and manage recipes</li>
                <li>To generate and organize meal plans</li>
                <li>To create grocery shopping lists</li>
                <li>To improve application functionality and performance</li>
                <li>To fix bugs and technical issues</li>
            </ul>
            <p style="margin-top:.7rem;">We do not sell or rent personal information to third parties.</p>
        </div>

        <div class="section-card" id="s4">
            <div class="section-num">4</div>
            <h2>Data Storage</h2>
            <p>User-generated content such as recipes, meal plans, and grocery lists may be stored locally on the user's device or on secure servers if cloud synchronization is enabled.</p>
            <p>We take reasonable measures to protect user data from unauthorized access or disclosure.</p>
        </div>

        <div class="section-card" id="s5">
            <div class="section-num">5</div>
            <h2>Third-Party Services</h2>
            <p>The application may use third-party services that may collect information used to identify you. These services may include:</p>
            <ul>
                <li>Google Play Services</li>
                <li>Analytics tools (if implemented)</li>
            </ul>
            <p style="margin-top:.7rem;">These third-party services have their own privacy policies governing how they use such information.</p>
        </div>

        <div class="section-card" id="s6">
            <div class="section-num">6</div>
            <h2>Children's Privacy</h2>
            <p>Our application is not directed toward children under the age of 13.</p>
            <p>We do not knowingly collect personal information from children under 13. If we discover that a child under 13 has provided personal information, we will delete such information immediately.</p>
        </div>

        <div class="section-card" id="s7">
            <div class="section-num">7</div>
            <h2>Your Rights</h2>
            <p>Users have the right to:</p>
            <ul>
                <li>Access their data</li>
                <li>Modify or delete their data</li>
                <li>Stop using the application at any time</li>
            </ul>
            <p style="margin-top:.7rem;">If your data is stored on our servers, you may request deletion by contacting us.</p>
        </div>

        <div class="section-card" id="s8">
            <div class="section-num">8</div>
            <h2>Changes to This Privacy Policy</h2>
            <p>We may update our Privacy Policy from time to time. Any changes will be posted on this page with an updated revision date.</p>
        </div>

        <div class="section-card" id="s9">
            <div class="section-num">9</div>
            <h2>Contact Us</h2>
            <p>If you have any questions about this Privacy Policy, please contact us:</p>
            <ul style="margin-top:.7rem;">
                <li>
                    <a href="mailto:scrumptiousreceipeplanner@gmail.com" class="contact-link">
                        <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        scrumptiousreceipeplanner@gmail.com
                    </a>
                </li>
                <li>Developer: Scrumptious Recipe Planner</li>
            </ul>
        </div>

    </article>
</div>
@endsection
