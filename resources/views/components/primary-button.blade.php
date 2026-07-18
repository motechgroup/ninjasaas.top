<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-primary text-white border border-transparent rounded-lg font-bold text-xs uppercase tracking-widest hover:opacity-90 active:opacity-95 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-md']) }}>
    {{ $slot }}
</button>
