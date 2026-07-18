@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white rounded-lg focus:ring-primary focus:border-primary py-2 px-3 text-sm shadow-sm']) }}>
