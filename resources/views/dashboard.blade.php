<!-- @extends('layouts.app')

@section('content')
<style>
    @if (session('status'))
    <div style="
        background:#d4edda;
        color:#155724;
        padding:10px;
        border-radius:6px;
        margin-bottom:15px;">
        ✅ {{ session('status') }}
    </div>
@endif

    body {
        background: #f6f7fb;
    }
    .wrapper {
        display: flex;
    }
    .sidebar {
        width: 220px;
        background: #fff;
        min-height: 100vh;
        padding: 20px;
        box-shadow: 2px 0 5px rgba(0,0,0,.05);
    }
    .sidebar a {
        display: block;
        padding: 10px;
        text-decoration: none;
        color: #333;
        margin-bottom: 8px;
        border-radius: 6px;
    }
    .sidebar a.active {
        background: #e6f4ea;
    }
    .main {
        flex: 1;
        padding: 30px;
    }
    .cards {
        display: grid;
        grid-template-columns: repeat(3,1fr);
        gap: 20px;
        margin-bottom: 25px;
    }
    .card {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,.05);
    }
    .big {
        font-size: 32px;
        font-weight: bold;
    }
    .green { color: #27ae60; }
    .red { color: #c0392b; }
</style>

<div class="wrapper"> -->

    <!-- /* Sidebar
    <div class="sidebar">
        <h3>BMKG</h3>
        <a href="#" class="active">Dashboard</a>
        <a href="#">System</a>
        <a href="#">Network Traffic</a>
        <a href="#">Settings</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button style="margin-top:20px;">Logout</button>
        </form>
    </div>

    Main
    <div class="main">
        <p>Selamat Datang {{ Auth::user()->name }} ☀️</p>
        <h1>Dashboard</h1>

        <div class="stat-card">
            <h4>CPU Load</h4>
            <div class="circle cpu">
                <span>67%</span>
            </div>
        </div>
            <div class="card">
                <h4>Load RAM</h4>
                <div class="big green">67%</div>
            </div>
            <div class="card">
                <h4>Network Summary</h4>
                <p>🟢 Online: 18</p>
                <p>🔴 Offline: 2</p>
                <p>AVG Memory: 35%</p>
            </div>
        </div>

        <div class="cards">
            <div class="card">
                <h4>System Date</h4>
                <p>{{ now()->format('d.m.Y, H:i:s') }}</p>
            </div>
            <div class="card">
                <h4>Device Error</h4>
                <div class="big green">0</div>
            </div>
        </div>

        <div class="card">
            <h4>Network Traffic Basic</h4>
            <p>Grafik akan ditambahkan (Chart.js)</p>
        </div>
    </div>

</div>
@endsection  */ -->
