<aside id="sidebar" class="sidebar">
    
    {{-- Logo --}}
    <div class="logo">
        <img src="{{ asset('assets/image/logo.jpeg') }}" alt="Logo BMKG">
        <h6>BMKG</h6>
    </div>

    {{-- Navigation Menu --}}
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
               href="{{ route('dashboard') }}">
                <i class="bi bi-grid-fill"></i>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('system') ? 'active' : '' }}" 
               href="{{ route('system') }}">
                <i class="bi bi-pc-display"></i>
                <span class="nav-text">System</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('network') ? 'active' : '' }}" 
               href="{{ route('network') }}">
                <i class="bi bi-graph-up"></i>
                <span class="nav-text">Network Traffic</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" 
               href="{{ route('settings.index') }}">
                <i class="bi bi-gear-fill"></i>
                <span class="nav-text">Settings</span>
            </a>
        </li>
    </ul>

    {{-- Logout --}}
    <div class="logout-section">
        <button type="button" class="btn btn-outline-danger w-100" onclick="showLogoutModal(event)">
            <i class="bi bi-box-arrow-left"></i>
            <span class="nav-text">Sign Out</span>
        </button>
    </div>
</aside>
