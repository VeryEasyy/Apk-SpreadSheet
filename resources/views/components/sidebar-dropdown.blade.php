<div class="sidebar-dropdown" data-dropdown="{{ $id }}">
    <button type="button" class="sidebar-dropdown-toggle" aria-expanded="false">
        <div class="dropdown-content">
            <i class="{{ $icon }}"></i>
            <span class="sidebar-link-text">{{ $label }}</span>
        </div>
        <i class="bi bi-chevron-down dropdown-arrow"></i>
    </button>
    <div class="sidebar-dropdown-menu">
        {{ $slot }}
    </div>
</div>
