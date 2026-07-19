@extends('layouts.public')

@section('title', 'Terms of Service - SaaSNinja')

@section('content')
    <!-- Header Hero banner -->
    <header class="bg-slate-50 dark:bg-slate-950 py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-4xl mx-auto px-6">
            <h1 class="font-outfit font-extrabold text-4xl text-slate-900 dark:text-white tracking-tight mb-3">Terms of Service</h1>
            <p class="text-sm text-slate-550 dark:text-slate-400">Last updated: July 19, 2026. Please read these terms carefully before accessing our marketplace.</p>
        </div>
    </header>

    <!-- Content Body -->
    <main class="max-w-4xl mx-auto px-6 py-12">
        <div class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-350 text-sm space-y-8 leading-relaxed">
            
            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">1. Agreement to Terms</h2>
                <p>By registering an account, purchasing items, or requesting custom installation support on SaaSNinja (saasninja.top), you agree to be bound by these Terms of Service. If you do not agree, please do not use our services.</p>
            </section>

            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">2. Licensing & Digital Deliverables</h2>
                <p>All software products, landing pages, and administration scripts sold through our portal are subject to our Envato licensing standards. A single purchase code licenses the software for a single production domain only. Redistribution, sub-licensing, or sharing of source files without explicit authorization is strictly prohibited.</p>
            </section>

            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">3. Account Obligations</h2>
                <p>To access downloads, changelogs, and submit helpdesk tickets, you must register a valid account. You are solely responsible for protecting your account credentials and ensuring the email address associated with your profile remains up-to-date.</p>
            </section>

            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">4. Support Helpdesk Policies</h2>
                <p>We offer product support for licensed users matching Envato's guidelines. Support includes debugging product failures and answering basic setup questions. Support does **not** include custom features, theme redesigns, or third-party server integrations unless a separate Custom Installation Service is purchased.</p>
            </section>

            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">5. Limitation of Liability</h2>
                <p>SaaSNinja products are provided "as is" without warranty of any kind. We shall not be liable for any direct, indirect, incidental, or consequential damages (including data loss or business interruption) arising from the installation, use, or inability to use our software scripts.</p>
            </section>

        </div>
    </main>
@endsection
