<aside class="sidebar-wrapper" id="sidebar">
    {{-- Logo --}}
    <div class="sidebar-logo">
        <img src="{{ asset('assets/images/logo/bando-logo.png') }}" alt="{{ config('app.name') }}"
            onerror="this.style.display='none'">
        <div class="logo-text">{{ config('app.name', 'BANDO') }}</div>
    </div>

    {{-- User Info --}}
    <div class="sidebar-user">
        <div class="user-avatar-circle">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="user-details">
            <p class="sidebar-user-name">{{ auth()->user()->name }}</p>
            <p class="sidebar-user-nik">{{ auth()->user()->nik ?? 'N/A' }}</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav" role="navigation">
        {{-- Dashboard --}}
        <x-sidebar-link href="{{ route('Dashboard') }}" icon="bi-house-door" label="Dashboard" route="Dashboard" />

        {{-- Document Dropdown --}}
        <x-sidebar-dropdown icon="bi-file-earmark-text" label="Document" id="document">
            <x-sidebar-link href="{{ route('dokumen.laporan') }}" icon="bi-file-earmark-check" label="Laporan"
                route="dokumen.laporan" :is-sub-link="true" />
        </x-sidebar-dropdown>

        {{-- Account Dropdown --}}
        <x-sidebar-dropdown icon="bi-person-circle" label="Akun" id="account">
            <x-sidebar-link href="{{ route('akun.profile') }}" icon="bi-person" label="Profil" route="akun.profile"
                :is-sub-link="true" />
        </x-sidebar-dropdown>

        <hr class="sidebar-divider">

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
            @csrf
            <button type="submit" class="sidebar-link logout-link">
                <i class="bi bi-box-arrow-right"></i>
                <span class="sidebar-link-text">Logout</span>
            </button>
        </form>
    </nav>
</aside>
<aside class="sidebar-wrapper" id="sidebar">
    {{-- Logo --}}
    <div class="sidebar-logo">
        <img src="{{ asset('assets/images/logo/bando-logo.png') }}" alt="{{ config('app.name') }}"
            onerror="this.style.display='none'">
        <div class="logo-text">{{ config('app.name', 'BANDO') }}</div>
    </div>


    {{-- User Info --}}
    <div class="sidebar-user">
        <p class="sidebar-user-name">{{ auth()->user()->name }}</p>
        <p class="sidebar-user-nik">{{ auth()->user()->nik ?? 'N/A' }}</p>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav" role="navigation">
        {{-- Dashboard --}}
        <x-sidebar-link href="{{ route('Dashboard') }}" icon="bi-house-door" label="Dashboard" route="Dashboard" />

        {{-- Document Dropdown --}}
        <x-sidebar-dropdown icon="bi-file-earmark-text" label="Document" id="document">
            <x-sidebar-link href="{{ route('dokumen.laporan') }}" icon="bi-file-earmark-check" label="Laporan"
                route="dokumen.laporan" :is-sub-link="true" />
        </x-sidebar-dropdown>

        {{-- Account Dropdown --}}
        <x-sidebar-dropdown icon="bi-person-circle" label="Akun" id="account">
            <x-sidebar-link href="{{ route('akun.profile') }}" icon="bi-person" label="Profil" route="akun.profile"
                :is-sub-link="true" />
        </x-sidebar-dropdown>

        <hr class="sidebar-divider">

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
            @csrf
            <button type="submit" class="sidebar-link logout-link">
                <i class="bi bi-box-arrow-right"></i>
                <span class="sidebar-link-text">Logout</span>
            </button>
        </form>
    </nav>
</aside>
