@extends('layouts.public')

@section('title', 'About SaaSNinja - Ambitious, Forward-Looking Software Development')
@section('meta_description', 'Learn about SaaSNinja, a modern software company founded in 2028 dedicated to crafting reliable, scalable, and innovative technology solutions.')

<!-- Devicon Font CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-slate-50 dark:bg-slate-950 py-20 border-b border-slate-200 dark:border-slate-800 overflow-hidden">
        <!-- Ambient decorative glows -->
        <div class="absolute -right-24 -top-24 w-96 h-96 rounded-full bg-indigo-500/5 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-24 -bottom-24 w-96 h-96 rounded-full bg-emerald-500/5 blur-3xl pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="max-w-3xl">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400 text-xs font-semibold mb-4">
                    Who We Are
                </span>
                <h1 class="font-outfit font-extrabold text-4xl sm:text-5xl lg:text-6xl text-slate-900 dark:text-white mb-6 tracking-tight leading-none">
                    Engineering the Future of Business Software.
                </h1>
                <p class="text-base sm:text-lg lg:text-xl text-slate-500 leading-relaxed">
                    SaaSNinja is an ambitious, forward-looking software development company. We construct high-quality business management systems, custom web applications, and mobile ecosystems designed to help organizations scale and succeed.
                </p>
            </div>
        </div>
    </section>

    <!-- Company Story & Mission -->
    <section class="py-16 bg-white dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Story -->
                <div class="lg:col-span-7 space-y-6">
                    <h2 class="font-outfit font-extrabold text-2xl sm:text-3xl text-slate-900 dark:text-white tracking-tight">Our Story</h2>
                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-sm sm:text-base">
                        Founded in 2028, SaaSNinja emerged from a simple observation: modern businesses are often forced to choose between complex, bloated enterprise suites and rigid, cookie-cutter solutions. We set out to build a new kind of software company—one focused on clarity, security, performance, and long-term maintainability.
                    </p>
                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-sm sm:text-base">
                        Rather than chasing buzzwords, our experienced team of engineers focuses on crafting premium software architectures that solve real-world operational problems. From simplifying daily workflows to executing complex data orchestrations, we build tools that scale alongside your growth.
                    </p>
                </div>

                <!-- Mission & Vision Cards -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Mission Card -->
                    <div class="bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200/50 dark:border-slate-800/80 p-6 rounded-2xl">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="material-symbols-outlined text-primary text-[24px]">explore</span>
                            <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base">Our Mission</h3>
                        </div>
                        <p class="text-slate-550 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">
                            To empower businesses with reliable, scalable, and innovative software solutions that simplify operations, improve productivity, and accelerate digital transformation.
                        </p>
                    </div>

                    <!-- Vision Card -->
                    <div class="bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200/50 dark:border-slate-800/80 p-6 rounded-2xl">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="material-symbols-outlined text-primary text-[24px]">visibility</span>
                            <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base">Our Vision</h3>
                        </div>
                        <p class="text-slate-550 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">
                            To become one of the world's most trusted software companies by creating high-quality business applications and technology solutions that help organizations grow.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- What We Do & Industries We Serve -->
    <section class="py-16 bg-slate-50/50 dark:bg-slate-950/10 border-y border-slate-200/60 dark:border-slate-800/60">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <h2 class="font-outfit font-extrabold text-2xl sm:text-3xl text-slate-950 dark:text-white tracking-tight">Capabilities & Domain Expertise</h2>
                <p class="text-slate-500 text-sm sm:text-base">We specialize in developing digital infrastructures engineered for real-world business domains.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- What We Do List -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 rounded-3xl space-y-6">
                    <h3 class="font-outfit font-extrabold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">terminal</span>
                        What We Do
                    </h3>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-slate-600 dark:text-slate-400 text-xs sm:text-sm">
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0"></span>Enterprise Software Development</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0"></span>Business Management Systems</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0"></span>Web Application Development</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0"></span>SaaS Platforms</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0"></span>Mobile App Development</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0"></span>API & Integration Architecture</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0"></span>Cloud Solutions & Scaling</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0"></span>UI/UX Product Design</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0"></span>Business Automation Systems</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0"></span>AI-powered Business Integrations</li>
                    </ul>
                </div>

                <!-- Industries We Serve -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 rounded-3xl space-y-6">
                    <h3 class="font-outfit font-extrabold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">domain</span>
                        Industries We Serve
                    </h3>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach(['Law Firms', 'Healthcare', 'Schools & Academies', 'Internet Service Providers (ISPs)', 'Retail & E-commerce', 'Finance & FinTech', 'Public Sector', 'NGOs', 'SMEs', 'Large Enterprises'] as $ind)
                            <span class="inline-flex items-center px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-950 text-slate-700 dark:text-slate-300 text-xs font-semibold border border-slate-200/55 dark:border-slate-850">
                                {{ $ind }}
                            </span>
                        @endforeach
                    </div>
                    <div class="border-t border-slate-100 dark:border-slate-800/80 pt-4">
                        <p class="text-xs text-slate-450 dark:text-slate-500 leading-relaxed font-semibold">
                            Including specialty software like Law Firm Management Systems, ISP Billing Systems, Hospital Portals, POS Management, and CRM/ERP custom modules.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Technology Stack -->
    <section class="py-16 bg-white dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-5 space-y-4">
                    <h2 class="font-outfit font-extrabold text-2xl sm:text-3xl text-slate-950 dark:text-white tracking-tight">Our Technology Stack</h2>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                        We leverage modern, verified, and active open-source ecosystems to build products targeting speed, security, clean code, and long-term developer maintainability.
                    </p>
                </div>
                <div class="lg:col-span-7 flex flex-wrap gap-3">
                    @php
                        $techs = [
                            ['name' => 'Laravel', 'icon' => 'devicon-laravel-original colored'],
                            ['name' => 'PHP', 'icon' => 'devicon-php-plain colored'],
                            ['name' => 'JavaScript', 'icon' => 'devicon-javascript-plain colored'],
                            ['name' => 'TypeScript', 'icon' => 'devicon-typescript-plain colored'],
                            ['name' => 'Livewire', 'icon' => 'material-symbols-outlined text-[16px] text-blue-500', 'is_material' => true, 'symbol' => 'bolt'],
                            ['name' => 'Tailwind CSS', 'icon' => 'devicon-tailwindcss-original colored'],
                            ['name' => 'React', 'icon' => 'devicon-react-original colored'],
                            ['name' => 'Vue', 'icon' => 'devicon-vuejs-plain colored'],
                            ['name' => 'Flutter', 'icon' => 'devicon-flutter-plain colored'],
                            ['name' => 'MySQL', 'icon' => 'devicon-mysql-plain colored'],
                            ['name' => 'PostgreSQL', 'icon' => 'devicon-postgresql-plain colored'],
                            ['name' => 'REST APIs', 'icon' => 'material-symbols-outlined text-[16px] text-indigo-500', 'is_material' => true, 'symbol' => 'api'],
                            ['name' => 'Cloud Infrastructure', 'icon' => 'material-symbols-outlined text-[16px] text-sky-500', 'is_material' => true, 'symbol' => 'cloud'],
                            ['name' => 'Artificial Intelligence', 'icon' => 'material-symbols-outlined text-[16px] text-purple-500', 'is_material' => true, 'symbol' => 'psychology'],
                        ];
                    @endphp
                    @foreach($techs as $tech)
                        <span class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-200 text-xs font-bold border border-slate-200/60 dark:border-slate-850 group hover:scale-[1.03] hover:border-indigo-200/50 hover:bg-slate-50 dark:hover:bg-slate-900 transition-all duration-300 shadow-sm">
                            @if(isset($tech['is_material']) && $tech['is_material'])
                                <span class="{{ $tech['icon'] }}">{{ $tech['symbol'] }}</span>
                            @else
                                <i class="{{ $tech['icon'] }} text-[16px] filter dark:brightness-110"></i>
                            @endif
                            {{ $tech['name'] }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="py-16 bg-slate-50/50 dark:bg-slate-950/10 border-t border-slate-200/60 dark:border-slate-800/60">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <h2 class="font-outfit font-extrabold text-2xl sm:text-3xl text-slate-950 dark:text-white tracking-tight">Our Core Values</h2>
                <p class="text-slate-500 text-sm sm:text-base">The guiding principles behind every specification we write and line of code we push.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Value 1 -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800/80 p-6 rounded-2xl space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">lightbulb</span>
                    </div>
                    <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-base">Innovation</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        We actively research and incorporate modern technologies, ensuring our partners are equipped with state-of-the-art tools to maintain competitive operational efficiency.
                    </p>
                </div>

                <!-- Value 2 -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800/80 p-6 rounded-2xl space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">verified</span>
                    </div>
                    <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-base">Quality</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        We refuse to compromise on software quality. Clean code, comprehensive documentation, and structured testing methodologies define our execution standard.
                    </p>
                </div>

                <!-- Value 3 -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800/80 p-6 rounded-2xl space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">shield</span>
                    </div>
                    <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-base">Security</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        By applying strict, security-first architectural standards, we protect enterprise data and customer integrity against modern digital threat models.
                    </p>
                </div>

                <!-- Value 4 -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800/80 p-6 rounded-2xl space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">handshake</span>
                    </div>
                    <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-base">Integrity</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        Transparency and reliability guide our operations. We provide accurate assessments, avoid exaggerated claims, and build trust through verifiable performance.
                    </p>
                </div>

                <!-- Value 5 -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800/80 p-6 rounded-2xl space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">psychology</span>
                    </div>
                    <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-base">Continuous Learning</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        The tech ecosystem moves rapidly. Our engineering and design teams dedicate research time to master evolving frameworks, paradigms, and design guidelines.
                    </p>
                </div>

                <!-- Value 6 -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800/80 p-6 rounded-2xl space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-950/30 text-orange-600 dark:text-orange-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">volunteer_activism</span>
                    </div>
                    <h3 class="font-outfit font-bold text-slate-900 dark:text-white text-base">Customer Success</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        Your operational uptime is our primary metric. We back our software products with committed customer support channels and continuous telemetry improvements.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-16 bg-white dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-5 space-y-6">
                    <h2 class="font-outfit font-extrabold text-2xl sm:text-3xl text-slate-950 dark:text-white tracking-tight">Why Partner with SaaSNinja?</h2>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        We build digital products that businesses can depend on for years. We focus on modern practices, verified architecture, and human relationships.
                    </p>
                    <ul class="space-y-4">
                        @foreach([
                            'Experienced Software Engineers' => 'A team that values craftsmanship and clear architectures.',
                            'Modern Development Practices' => 'Continuous integration, strict testing, and regular stability updates.',
                            'User-Focused Design' => 'Clean user interfaces designed to remove operational friction.',
                            'Reliable Support & Partnerships' => 'Committed long-term support to assist you as your scale requirements evolve.'
                        ] as $title => $desc)
                            <li class="flex gap-3">
                                <span class="material-symbols-outlined text-primary text-[20px] flex-shrink-0">check_circle</span>
                                <div>
                                    <span class="block font-bold text-slate-900 dark:text-white text-sm">{{ $title }}</span>
                                    <span class="block text-slate-500 text-xs mt-0.5">{{ $desc }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="lg:col-span-7 bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200/60 dark:border-slate-800/80 rounded-3xl p-8 space-y-6">
                    <h3 class="font-outfit font-extrabold text-xl text-slate-900 dark:text-white">Our Product Commitment</h3>
                    <p class="text-slate-655 dark:text-slate-350 text-sm leading-relaxed">
                        SaaSNinja is dedicated to delivering technology that your organization can rely on. We don't just ship code and walk away—we provide professional support, continuous security patching, and ongoing feature updates. We treat our clients as long-term partners, aligning our product roadmaps with your long-term success.
                    </p>
                    <div class="border-t border-slate-200 dark:border-slate-850 pt-4 flex items-center gap-4">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 border border-white dark:border-slate-900 flex items-center justify-center text-[10px] font-bold text-slate-500">M</div>
                            <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 border border-white dark:border-slate-900 flex items-center justify-center text-[10px] font-bold text-slate-500">A</div>
                            <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 border border-white dark:border-slate-900 flex items-center justify-center text-[10px] font-bold text-slate-500">J</div>
                        </div>
                        <span class="text-xs text-slate-450 dark:text-slate-500 font-semibold">Backed by a dedicated core engineering team</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-gradient-to-r from-primary to-indigo-650 text-white relative overflow-hidden">
        <!-- Overlay background graphics -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.05),transparent)]"></div>
        
        <div class="max-w-4xl mx-auto text-center px-6 relative z-10 space-y-6">
            <h2 class="font-outfit font-extrabold text-3xl sm:text-4xl lg:text-5xl tracking-tight leading-tight">
                Let's Build the Next Generation of Digital Solutions.
            </h2>
            <p class="text-indigo-100 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto leading-relaxed">
                Whether you are a growing business, an ambitious startup, or a digital-first enterprise, we have the software solutions and technical expertise to help you succeed.
            </p>
            <div class="flex flex-wrap justify-center gap-4 pt-4">
                <a href="{{ route('contact') }}" class="px-6 py-3 bg-white text-primary text-sm font-bold rounded-xl shadow-lg hover:bg-slate-50 transition-all hover:scale-[1.02] active:scale-[0.98]">
                    Partner with Us
                </a>
                <a href="{{ route('products.index') }}" class="px-6 py-3 bg-indigo-700/40 hover:bg-indigo-700/60 text-white text-sm font-bold rounded-xl border border-indigo-400/40 transition-all hover:scale-[1.02] active:scale-[0.98]">
                    View Software Catalog
                </a>
            </div>
        </div>
    </section>
@endsection
