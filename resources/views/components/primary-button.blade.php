<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-emerald-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider hover:bg-emerald-500 active:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 shadow-sm transition-all duration-200 active:scale-95']) }}>
    {{ $slot }}
</button>
