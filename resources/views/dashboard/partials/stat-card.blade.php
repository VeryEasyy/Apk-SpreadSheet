<div class="stat-card">
    <div class="stat-icon {{ $badgeType }}">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="stat-content">
        <p class="stat-label">{{ $title }}</p>
        <h2 class="stat-value">{{ $value }}</h2>
        <div class="stat-footer">
            <span class="stat-badge badge-{{ $badgeType }}">{{ $badge }}</span>
            @if($trend === 'up')
                <span class="stat-trend trend-up">
                    <i class="bi bi-arrow-up"></i> 12%
                </span>
            @elseif($trend === 'down')
                <span class="stat-trend trend-down">
                    <i class="bi bi-arrow-down"></i> 5%
                </span>
            @else
                <span class="stat-trend trend-stable">
                    <i class="bi bi-dash"></i> 0%
                </span>
            @endif
        </div>
    </div>
</div>