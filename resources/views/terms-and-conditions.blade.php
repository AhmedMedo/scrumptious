@extends('layouts.public')

@section('title', 'Terms & Conditions')
@section('meta_description', 'Scrumptious Recipe Planner Terms and Conditions — rules governing your use of the app.')

@section('hero')
<section class="hero">
    <div class="hero-badge">Legal</div>
    <h1>Terms &amp; Conditions</h1>
    <p class="hero-meta">Last Updated: March 13, 2026</p>
    <p class="hero-sub">Please read these Terms carefully before using the app. By accessing or using Scrumptious Recipe Planner, you agree to be bound by these Terms.</p>
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
        font-size: .78rem;
        color: var(--muted);
        text-decoration: none;
        padding: .28rem .55rem;
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

    .highlight {
        background: var(--orange-md);
        border-left: 3px solid var(--orange);
        border-radius: 0 10px 10px 0;
        padding: .8rem 1.1rem;
        margin-top: .85rem;
        font-size: .9rem;
        color: var(--brown);
        line-height: 1.65;
    }

    .plans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: .8rem;
        margin-top: .85rem;
    }
    .plan-tile {
        background: var(--orange-lt);
        border: 1px solid #FED7AA;
        border-radius: 12px;
        padding: 1rem 1.1rem;
    }
    .plan-tile strong { display: block; font-size: .865rem; font-weight: 700; color: var(--brown); margin-bottom: .3rem; }
    .plan-tile span   { font-size: .815rem; color: var(--muted); line-height: 1.55; }

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
            <li><a href="#s1">1. Overview of Services</a></li>
            <li><a href="#s2">2. User Accounts</a></li>
            <li><a href="#s3">3. Subscription Plans</a></li>
            <li><a href="#s4">4. Payments</a></li>
            <li><a href="#s5">5. Cancellations &amp; Refunds</a></li>
            <li><a href="#s6">6. User-Generated Content</a></li>
            <li><a href="#s7">7. Specialist Meal Plans</a></li>
            <li><a href="#s8">8. Acceptable Use</a></li>
            <li><a href="#s9">9. Limitation of Liability</a></li>
            <li><a href="#s10">10. Governing Law</a></li>
            <li><a href="#s11">11. Changes to Terms</a></li>
            <li><a href="#s12">12. Shipping Policy</a></li>
            <li><a href="#s13">13. Contact Us</a></li>
        </ul>
    </aside>

    <article class="article">

        <div class="section-card" id="s1">
            <div class="section-num">1</div>
            <h2>Overview of Services</h2>
            <p>Scrumptious Recipe Planner provides users with the ability to:</p>
            <ul>
                <li>Upload and browse recipes</li>
                <li>Create personalized meal plans</li>
                <li>Access premium features through subscription plans</li>
                <li>Receive customized meal plans created by dietary specialists</li>
            </ul>
        </div>

        <div class="section-card" id="s2">
            <div class="section-num">2</div>
            <h2>User Accounts</h2>
            <p>To access certain features, you may need to create an account. By creating an account, you agree to:</p>
            <ul>
                <li>Provide accurate and complete information</li>
                <li>Maintain the confidentiality of your login credentials</li>
                <li>Accept responsibility for all activities that occur under your account</li>
            </ul>
            <div class="highlight">You must notify us immediately if you suspect unauthorized use of your account.</div>
        </div>

        <div class="section-card" id="s3">
            <div class="section-num">3</div>
            <h2>Subscription Plans</h2>
            <p>Scrumptious Recipe Planner offers the following subscription options:</p>
            <div class="plans-grid">
                <div class="plan-tile">
                    <strong>Free</strong>
                    <span>Access to a limited number of recipes and basic features.</span>
                </div>
                <div class="plan-tile">
                    <strong>Weekly</strong>
                    <span>Enhanced features billed on a weekly basis.</span>
                </div>
                <div class="plan-tile">
                    <strong>Monthly</strong>
                    <span>Premium features including personalized specialist meal plans.</span>
                </div>
                <div class="plan-tile">
                    <strong>Yearly</strong>
                    <span>Full premium access with up to 25% savings.</span>
                </div>
            </div>
            <p style="margin-top:.85rem;">Pricing and availability are displayed within the App and may change from time to time.</p>
        </div>

        <div class="section-card" id="s4">
            <div class="section-num">4</div>
            <h2>Payments</h2>
            <p>Payments are processed through Apple App Store, Google Play Store, or other secure payment providers depending on your platform.</p>
            <p>By purchasing a subscription, you agree to:</p>
            <ul>
                <li>Provide valid and accurate payment information</li>
                <li>Authorize the applicable subscription charges</li>
                <li>Accept that payment processing is handled by third-party providers subject to their own terms</li>
            </ul>
            <div class="highlight">Subscriptions automatically renew unless cancelled before the renewal date through your platform account settings.</div>
        </div>

        <div class="section-card" id="s5">
            <div class="section-num">5</div>
            <h2>Cancellations &amp; Refunds</h2>
            <p>Refund and cancellation requests are governed by our Refund and Cancellation Policy. By subscribing, you acknowledge and agree to the terms outlined in that policy.</p>
            <p>Refund requests may also be subject to the policies of the Apple App Store or Google Play Store, depending on where the purchase was made.</p>
        </div>

        <div class="section-card" id="s6">
            <div class="section-num">6</div>
            <h2>User-Generated Content</h2>
            <p>Users may upload recipes and other content to the App. By submitting content, you grant Scrumptious Recipe Planner a non-exclusive, worldwide, royalty-free license to use, reproduce, display, and distribute your content within the App.</p>
            <p>You confirm that:</p>
            <ul>
                <li>You own or have permission to share the content you upload</li>
                <li>Your content does not violate any laws or third-party rights</li>
                <li>Your content does not contain harmful or misleading information</li>
            </ul>
            <p style="margin-top:.7rem;">We reserve the right to remove any content that violates these Terms.</p>
        </div>

        <div class="section-card" id="s7">
            <div class="section-num">7</div>
            <h2>Specialist Meal Plans</h2>
            <p>Monthly and yearly subscribers may receive personalized meal plans created by qualified dietary specialists. These meal plans:</p>
            <ul>
                <li>Are based on information provided by the user</li>
                <li>Are intended for general wellness and informational purposes</li>
                <li>Do not replace professional medical advice</li>
            </ul>
            <div class="highlight">Users should consult a qualified healthcare professional before making major dietary changes.</div>
        </div>

        <div class="section-card" id="s8">
            <div class="section-num">8</div>
            <h2>Acceptable Use Policy</h2>
            <p>You agree not to:</p>
            <ul>
                <li>Use the App for illegal or unauthorized purposes</li>
                <li>Upload malicious software or harmful content</li>
                <li>Violate intellectual property rights</li>
                <li>Attempt to reverse engineer or interfere with the App's functionality</li>
            </ul>
            <p style="margin-top:.7rem;">We reserve the right to suspend or terminate accounts that violate these Terms.</p>
        </div>

        <div class="section-card" id="s9">
            <div class="section-num">9</div>
            <h2>Limitation of Liability</h2>
            <p>To the maximum extent permitted by law, Scrumptious Recipe Planner shall not be liable for:</p>
            <ul>
                <li>Indirect, incidental, or consequential damages</li>
                <li>Loss of data or business interruptions</li>
                <li>Unauthorized access to your account</li>
            </ul>
            <div class="highlight">Use of the App is at your own risk.</div>
        </div>

        <div class="section-card" id="s10">
            <div class="section-num">10</div>
            <h2>Governing Law</h2>
            <p>These Terms shall be governed by and interpreted in accordance with the laws of <strong>Egypt</strong>, without regard to conflict of law principles.</p>
        </div>

        <div class="section-card" id="s11">
            <div class="section-num">11</div>
            <h2>Changes to These Terms</h2>
            <p>We may update or modify these Terms at any time. Updates become effective once posted within the App or on our website.</p>
            <p>Your continued use of the App after updates means you accept the revised Terms.</p>
        </div>

        <div class="section-card" id="s12">
            <div class="section-num">12</div>
            <h2>Shipping Policy</h2>
            <p>Scrumptious Recipe Planner provides <strong>digital services only</strong>. We do not ship physical products.</p>
            <p>All services — including recipes, meal plans, and related content — are delivered electronically through the App or via email.</p>
        </div>

        <div class="section-card" id="s13">
            <div class="section-num">13</div>
            <h2>Contact Us</h2>
            <p>If you have questions regarding these Terms, please contact us:</p>
            <ul style="margin-top:.7rem;">
                <li>
                    <a href="mailto:Scrumptiousrecipeplanner@gmail.com" class="contact-link">
                        <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        Scrumptiousrecipeplanner@gmail.com
                    </a>
                </li>
                <li>Developer: Scrumptious Recipe Planner</li>
            </ul>
        </div>

    </article>
</div>
@endsection
