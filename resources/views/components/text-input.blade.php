@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-xl px-4 py-3 w-full outline-none transition-all duration-300']) !!} 
    style="background-color: #1e293b !important; color: #ffffff !important; border: 1px solid rgba(255, 255, 255, 0.1) !important;"
    onfocus="this.style.borderColor='#6366f1'" 
    onblur="this.style.borderColor='rgba(255, 255, 255, 0.1)'">
