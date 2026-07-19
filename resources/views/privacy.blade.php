@extends('layouts.public')

@section('title', 'Privacy Policy - SaaSNinja')

@section('content')
    <!-- Header Hero banner -->
    <header class="bg-slate-50 dark:bg-slate-950 py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-4xl mx-auto px-6">
            <h1 class="font-outfit font-extrabold text-4xl text-slate-900 dark:text-white tracking-tight mb-3">Privacy Policy</h1>
            <p class="text-sm text-slate-550 dark:text-slate-400">Last updated: July 19, 2026. Please read this policy carefully to understand our data practices.</p>
        </div>
    </header>

    <!-- Content Body -->
    <main class="max-w-4xl mx-auto px-6 py-12">
        <div class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-350 text-sm space-y-8 leading-relaxed">
            
            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">1. Information We Collect</h2>
                <p>We collect information to provide better services to our users. The types of personal information we collect include:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Account Details:</strong> When you register on our portal, we collect your name, email address, password, and profile preferences.</li>
                    <li><strong>Purchase History:</strong> We record purchase transactions, license keys, and billing references linked to Envato purchases or custom service orders.</li>
                    <li><strong>Support Tickets:</strong> Any communications, log files, or server configurations you provide to resolve technical helpdesk tickets.</li>
                    <li><strong>Technical Data:</strong> IP addresses, browser types, cookie identifiers, and system diagnostic logs collected for security and system diagnostics.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">2. How We Use Your Information</h2>
                <p>We process your data to fulfill our contract with you and run our SaaS marketplace operations. Specifically:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>To verify purchase licenses and Envato API tokens.</li>
                    <li>To notify you about critical software updates, changelogs, and security patches.</li>
                    <li>To diagnose, assign, and respond to your helpdesk support tickets.</li>
                    <li>To process custom service quotes, custom script adjustments, or server setup services.</li>
                    <li>To comply with regulatory tax auditing requirements and legal obligations.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">3. Data Retention and Security</h2>
                <p>Your security is our priority. We implement robust database encryption and SSL/TLS secure channels to protect your credentials. We retain your support and account data as long as your registered account remains active or as needed to maintain legal tax records.</p>
            </section>

            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">4. Third-Party Sharing</h2>
                <p>SaaSNinja does not sell your information. We only share data with essential third parties necessary to process site services:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Envato Market API:</strong> To validate purchase code credentials.</li>
                    <li><strong>Stripe:</strong> To process secure payment transactions.</li>
                    <li><strong>SMTP Services:</strong> To deliver password resets and transaction confirmations.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">5. Your Rights and Contact</h2>
                <p>You have the right to request access to, correction of, or deletion of your personal data. You can delete your account under your Profile settings. For privacy inquiries, please contact our support team at <a href="mailto:support@saasninja.top" class="text-primary hover:underline">support@saasninja.top</a>.</p>
            </section>

        </div>
    </main>
@endsection
