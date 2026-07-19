@extends('layouts.public')

@section('title', 'Refund Policy - SaaSNinja')

@section('content')
    <!-- Header Hero banner -->
    <header class="bg-slate-50 dark:bg-slate-950 py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-4xl mx-auto px-6">
            <h1 class="font-outfit font-extrabold text-4xl text-slate-900 dark:text-white tracking-tight mb-3">Refund Policy</h1>
            <p class="text-sm text-slate-550 dark:text-slate-400">Last updated: July 19, 2026. Understand our policies regarding digital downloads and custom services.</p>
        </div>
    </header>

    <!-- Content Body -->
    <main class="max-w-4xl mx-auto px-6 py-12">
        <div class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-350 text-sm space-y-8 leading-relaxed">
            
            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">1. Digital Products Policy</h2>
                <p>Since SaaSNinja licenses downloadable digital files (PHP scripts, templates, and full Laravel applications), all sales are final once download links have been accessed. Unlike physical goods, digital assets cannot be physically returned or deleted once acquired. Therefore, we do not issue refunds for simple "change of mind" or purchasing errors.</p>
            </section>

            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">2. Eligible Refund Scenarios</h2>
                <p>We want to ensure your satisfaction. We will issue refunds for digital licenses in the following situations, as aligned with Envato Market guidelines:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Broken Software:</strong> If the product contains critical errors or security vulnerabilities that prevent core functionality, and our support team cannot provide a hotfix or version patch within 7 business days of a detailed helpdesk ticket submission.</li>
                    <li><strong>Misrepresentation:</strong> If the product's advertised features differ materially from the final downloaded files or online product documentation.</li>
                    <li><strong>Double Purchase:</strong> If you accidentally purchase the identical license twice in error.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">3. Ineligible Refund Scenarios</h2>
                <p>We cannot issue refunds for digital licenses in the following scenarios:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>You do not want the product after downloading it.</li>
                    <li>The product does not meet your expectations or you decide you do not have the technical skills to run it.</li>
                    <li>Your web hosting environment does not meet the specified software requirements (such as PHP versions, database support, or required PHP extensions) listed on the product specifications.</li>
                    <li>You refuse to provide basic error logs or access details to help our team diagnose a configuration issue.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">4. Custom Services Policy</h2>
                <p>All custom service fees (such as server installation services, SMTP configurations, custom API integrations, or hourly developer contracts) are completely non-refundable once our developer has initiated the task or gained server access.</p>
            </section>

            <section class="space-y-3">
                <h2 class="font-outfit font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider">5. Requesting a Refund</h2>
                <p>To request a refund, please open a support ticket under the <a href="{{ route('dashboard') }}" class="text-primary hover:underline">Helpdesk Portal</a> detailing your purchase key, transaction ID, and a clear explanation of the technical issue. Refund requests are processed within 3 to 5 business days.</p>
            </section>

        </div>
    </main>
@endsection
