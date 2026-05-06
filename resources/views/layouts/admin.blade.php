<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NearU - Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        :root {
            --green: #2D7D4F;
            --gold: #FFD700;
            --gold-lt: #FFF8DC;
            --surface: #F8F9FA;
            --t2: #5E6E5E;
            --border: #E9ECEF;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #F8F9FA;
            margin: 0;
            padding: 0;
        }
        .admin-nav {
            background: linear-gradient(135deg, #2D7D4F, #1e5a3a);
            padding: 1rem 2rem;
            box-shadow: 0 4px 20px rgba(45,125,79,0.15);
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-brand {
            color: white;
            font-size: 1.5rem;
            font-weight: 800;
            text-decoration: none;
            font-family: 'Syne', sans-serif;
        }
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
        }
        .nav-link.active {
            background: rgba(255,255,255,0.2);
        }
        .main-content {
            min-height: calc(100vh - 80px);
            background: #F8F9FA;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Admin Navigation -->
    <nav class="admin-nav">
        <div class="nav-container">
            <a href="{{ route('admin.owner-verifications.index') }}" class="nav-brand">
                🔐 NearU Admin
            </a>
            <div class="nav-links">
                <a href="{{ route('admin.owner-verifications.index') }}" class="nav-link">
                    Owner Verifications
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="nav-link" style="background: rgba(255,255,255,0.1);">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
