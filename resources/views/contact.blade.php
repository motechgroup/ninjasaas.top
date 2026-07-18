@extends('layouts.public')

@section('title', 'Contact SaaSNinja Software')

@section('content')
    <div class="py-16 bg-slate-900 text-white text-center space-y-3 relative">
        <h1 class="font-outfit font-extrabold text-3xl sm:text-4xl tracking-tight">Contact Us</h1>
        <p class="text-slate-400 text-sm max-w-xl mx-auto font-medium">Have questions about our licenses, custom developments, or need pre-sale help? Get in touch.</p>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Info Panel (1 col) -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-6 shadow-sm space-y-6">
                    <h3 class="font-outfit font-bold text-lg text-slate-900 dark:text-white">Company Information</h3>
                    
                    <div class="space-y-4 text-sm text-slate-650 dark:text-slate-400">
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Distribution Channel</span>
                            <span class="font-semibold text-slate-850 dark:text-white mt-0.5">Envato Market (CodeCanyon)</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Email Address</span>
                            <span class="font-semibold text-slate-850 dark:text-white mt-0.5">support@saasninja.top</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Office Location</span>
                            <span class="font-semibold text-slate-850 dark:text-white mt-0.5">Silicon Valley, CA, USA</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Panel (2 cols) -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-8 shadow-sm">
                <h3 class="font-outfit font-bold text-lg text-slate-900 dark:text-white mb-6">Send Message</h3>

                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-800/50 text-green-800 dark:text-green-300 flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Your Name</label>
                            <input type="text" id="name" name="name" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-indigo-500">
                            @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Email Address</label>
                            <input type="email" id="email" name="email" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-indigo-500">
                            @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Subject</label>
                        <input type="text" id="subject" name="subject" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-indigo-500">
                        @error('subject') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Message</label>
                        <textarea id="message" name="message" rows="5" required class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-indigo-500" placeholder="Type your inquiry here..."></textarea>
                        @error('message') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors duration-150">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
