<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2D7D4F">
    
    <title>NearU – Find Housing Near Campus</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════ */
        /* CSS VARIABLES - MATCHING NEARU THEME */
        /* ═══════════════════════════════════════ */
        :root {
            /* Backgrounds */
            --bg:         #F0F7F2;
            --surface:    #fff;
            --card:       #fff;

            /* Text */
            --t1:         #141F14;
            --t2:         #5E6E5E;

            /* Border */
            --border:     #D6E8DC;

            /* Green palette */
            --green:      #2D7D4F;
            --green-dk:   #1f5c38;
            --green-lt:   #E8F7EE;

            /* Gold palette */
            --gold:       #F2B705;
            --gold-dk:    #c99200;
            --gold-lt:    #FFFBEB;

            /* Blue palette */
            --blue:       #3B82F6;
            --blue-lt:    #EFF6FF;

            /* Red palette */
            --red:        #C8102E;
            --red-lt:     #FFF0F2;

            /* Shadows */
            --sh:         0 2px 14px rgba(45, 125, 79, .08);
            --sh2:        0 6px 28px rgba(45, 125, 79, .16);

            /* Shared tokens */
            --rad:        18px;
            --transition: .2s ease;
        }

        /* ── DARK MODE ── */
        body.dark {
            --bg:       #0a1410;
            --surface:  #111d14;
            --card:     #162019;
            --t1:       #dfeee4;
            --t2:       #6a8a72;
            --border:   #1e3024;
            --green-lt: #0d2219;
            --gold-lt:  #1a1500;
            --blue-lt:  #0d1826;
            --red-lt:   #1a050a;
        }

        /* ── RESET & BASE ── */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(145deg, #0a1f0e, #1a4d2e, #0f2d4a);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            transition: background .3s, color .3s;
        }

        /* ── LANDING CONTAINER ── */
        .landing-container {
            text-align: center;
            background: linear-gradient(145deg, #0a1f0e, #1a4d2e, #0f2d4a);
            padding: 60px 40px;
            border-radius: var(--rad);
            box-shadow: var(--sh2);
            max-width: 480px;
            width: 90%;
            position: relative;
        }

        /* ── LOGO ── */
        .logo {
            font-family: 'Syne', sans-serif;
            font-size: 4rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 20px;
            letter-spacing: -2px;
            line-height: 1;
        }

        .logo em {
            color: var(--gold);
            font-style: normal;
        }

        /* ── TAGLINE ── */
        .tagline {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 40px;
            font-weight: 400;
            line-height: 1.4;
        }

        /* ── BUTTONS ── */
        .buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 16px 32px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all var(--transition);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .btn:hover::before {
            transform: translateX(0);
        }

        .btn-login {
            background: var(--green);
            color: white;
            box-shadow: 0 4px 16px rgba(45, 125, 79, 0.35);
        }

        .btn-login:hover {
            background: var(--green-dk);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(45, 125, 79, 0.45);
        }

        .btn-signup {
            background: var(--gold);
            color: var(--t1);
            box-shadow: 0 4px 16px rgba(242, 183, 5, 0.35);
        }

        .btn-signup:hover {
            background: var(--gold-dk);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(242, 183, 5, 0.45);
        }

        /* ── DECORATIVE ELEMENTS ── */
        .decoration {
            position: absolute;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--green-lt), var(--green));
            opacity: 0.1;
            pointer-events: none;
        }

        .decoration-1 {
            top: -60px;
            right: -60px;
        }

        .decoration-2 {
            bottom: -60px;
            left: -60px;
            background: linear-gradient(135deg, var(--gold-lt), var(--gold));
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 600px) {
            .landing-container {
                padding: 40px 30px;
                margin: 20px;
            }
            
            .logo {
                font-size: 3rem;
            }
            
            .tagline {
                font-size: 1.1rem;
            }
            
            .buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 250px;
            }
        }

        /* ── AUTH STATE STYLES ── */
        .auth-state {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            font-size: 0.9rem;
            color: var(--t2);
        }

        .auth-state a {
            color: var(--green);
            text-decoration: none;
            font-weight: 600;
            margin: 0 5px;
        }

        .auth-state a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="landing-container">
        <!-- Decorative elements -->
        <div class="decoration decoration-1"></div>
        <div class="decoration decoration-2"></div>
        
        <!-- Logo -->
        <div class="logo">Near<em>U</em></div>
        
        <!-- Tagline -->
        <div class="tagline">Find Housing Near Campus</div>
        
        <!-- Main buttons -->
        <div class="buttons">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-login">Dashboard</a>
                    <a href="{{ route('logout') }}" class="btn btn-signup" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-signup">Sign Up</a>
                    @endif
                @endauth
            @endif
        </div>
        
        <!-- Auth state indicator -->
        @auth
            <div class="auth-state">
                Logged in as <strong>{{ auth()->user()->name }}</strong>
            </div>
        @endauth
    </div>
</body>
</html>