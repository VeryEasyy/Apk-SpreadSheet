<a 
    href="{{ $href }}" 
    class="sidebar-link {{ $active ? 'active' : '' }} {{ $isSubLink ? 'sub-link' : '' }}"
    {{ $attributes }}
>
    <i class="{{ $icon }}"></i>
    <span class="sidebar-link-text">{{ $label }}</span>
</a>