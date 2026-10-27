@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'px-4 py-2.5 border-slate-300 focus:border-sky-500 focus:ring-sky-500 rounded-xl text-sm shadow-sm']) }}>
