@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/dashboard.css') }}">
@endpush

@section('content')
{{-- Welcome Header --}}
<div class="dashboard-header">
    <div class="welcome-section">
        <h1 class="dashboard-title">Dashboard Laporan</h1>
        <p class="dashboard-subtitle">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong></p>
    </div>
    <div class="date-time-section">
        <div class="current-date">
            <i class="bi bi-calendar3"></i>
            <span id="currentDate"></span>
        </div>
        <div class="current-time">
            <i class="bi bi-clock"></i>
            <span id="currentTime"></span>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="stats-grid">
    @include('dashboard.partials.stat-card', [
        'icon' => 'bi-file-earmark-text',
        'title' => 'Total Reports',
        'value' => '15',
        'badge' => '+2 minggu ini',
        'badgeType' => 'success',
        'trend' => 'up'
    ])
    
    @include('dashboard.partials.stat-card', [
        'icon' => 'bi-grid-3x3-gap',
        'title' => 'Total Sheets',
        'value' => '87',
        'badge' => 'Stabil',
        'badgeType' => 'info',
        'trend' => 'stable'
    ])
    
    @include('dashboard.partials.stat-card', [
        'icon' => 'bi-pencil-square',
        'title' => 'Edits Today',
        'value' => '124',
        'badge' => 'Aktif',
        'badgeType' => 'warning',
        'trend' => 'up'
    ])
    
    @include('dashboard.partials.stat-card', [
        'icon' => 'bi-lock-fill',
        'title' => 'Locked Cells',
        'value' => '8',
        'badge' => 'Editing',
        'badgeType' => 'danger',
        'trend' => 'stable'
    ])
</div>

{{-- Chart Section --}}
<div class="dashboard-row">
    <div class="chart-card">
        <div class="card-header-dash">
            <div>
                <h3 class="card-title-dash">Aktivitas Edit Mingguan</h3>
                <p class="card-subtitle-dash">Statistik edit sel untuk 7 hari terakhir</p>
            </div>
            <div class="chart-filters">
                <button class="filter-btn active">7 Hari</button>
                <button class="filter-btn">30 Hari</button>
                <button class="filter-btn">90 Hari</button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="activityChart"></canvas>
        </div>
    </div>
</div>

{{-- Recent Reports & Activity --}}
<div class="dashboard-row two-cols">
    <div class="recent-reports-card">
        @include('dashboard.partials.recent-report')
    </div>
    
    <div class="activity-card">
        @include('dashboard.partials.recent-activity')
    </div>
</div>

{{-- Export Files Table --}}
<div class="export-files-card">
    @include('dashboard.partials.export-files')
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/pages/dashboard.js') }}"></script>
@endpush