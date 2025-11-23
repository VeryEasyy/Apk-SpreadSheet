<div class="mdc-drawer sidebar-custom">

    <h4 class="text-center mb-4 sidebar-title">Bando</h4>

    {{-- User Info --}}
    <div class="user-info text-center mb-4">
        <p class="fw-bold text-white mb-0">{{ auth()->user()->name ?? 'User Name' }}</p>
        <p class="small text-white-50">{{ auth()->user()->nik ?? 'NIK User' }}</p>
    </div>

    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}" class="sidebar-link">
        <span class="material-icons me-2">home</span> Dashboard
    </a>

    {{-- dokumen --}}
    <div class="sidebar-dropdown">
        <button type="button" class="sidebar-dropdown-toggle">
            <span class="d-flex align-items-center">
                 <span class="material-icons me-2">description</span> Document
            </span>
            <span class="material-icons dropdown-arrow">expand_more</span>
        </button>

        <div class="sidebar-dropdown-menu">
            <a href="{{ route('dokumen.laporan') }}" class="sidebar-link sub-link">
                <span class="material-icons me-2">assignment</span> Laporan
            </a>
        </div>
    </div>

    {{-- Akun --}}
    <div class="sidebar-dropdown">
        <button type="button" class="sidebar-dropdown-toggle">
            <span class="d-flex align-items-center">
                <span class="material-icons me-2">account_circle</span> Akun
            </span>
            <span class="material-icons dropdown-arrow">expand_more</span>
        </button>

        <div class="sidebar-dropdown-menu">
            <a href="{{ route('akun.profile') }}" class="sidebar-link sub-link">
                <span class="material-icons me-2">person</span> Profil
            </a>
        </div>
    </div>



    <hr class="sidebar-divider">
    
    <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
        @csrf
        <button type="submit" class="sidebar-link w-100 text-start bg-transparent border-0">
            <span class="material-icons me-2">logout</span> Logout
        </button>
    </form>


</div>
