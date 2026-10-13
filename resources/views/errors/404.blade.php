<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Rute Penerbangan Tidak Ditemukan | ANGKASA PURA</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&family=JetBrains+Mono:wght@400;600;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --ap-primary: #0284c7;
            --ap-primary-light: #38bdf8;
            --ap-blue: #0284c7;
            --ap-cyan: #38bdf8;
            --bg-dark: #090d16;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        body {
            background: radial-gradient(ellipse at 50% 25%, #1e293b 0%, #0f172a 50%, #020617 100%);
            min-height: 100vh;
            color: var(--text-light);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
            padding: 2rem 1.5rem;
        }

        /* Ambient Airport Radar Grid */
        .sky-grid {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            opacity: 0.8;
        }

        /* Top Header Brand */
        .error-top-brand {
            position: absolute;
            top: 24px;
            left: 32px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 10;
        }

        .brand-badge {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            color: #ffffff;
            font-weight: 900;
            font-size: 0.95rem;
            letter-spacing: 0.12em;
            padding: 6px 16px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(211, 47, 47, 0.4);
            text-decoration: none;
        }

        .brand-sub {
            font-size: 0.8rem;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Main Container */
        .error-wrapper {
            max-width: 780px;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 5;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Flight Scene & 404 Display */
        .flight-scene-404 {
            position: relative;
            width: 100%;
            max-width: 580px;
            height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        /* Huge 404 Big Numbers */
        .numbers-404 {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 9.5rem;
            font-weight: 900;
            line-height: 1;
            user-select: none;
        }

        .num-4 {
            background: linear-gradient(180deg, #ffffff 0%, #cbd5e1 45%, #64748b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        /* Center 0 is an Animated Radar Sphere */
        .radar-center-0 {
            width: 155px;
            height: 155px;
            border-radius: 50%;
            background: radial-gradient(circle at center, rgba(14, 165, 233, 0.15) 0%, rgba(15, 23, 42, 0.95) 75%);
            border: 2px solid rgba(56, 189, 248, 0.4);
            box-shadow: 0 0 40px rgba(14, 165, 233, 0.25), inset 0 0 30px rgba(2, 136, 209, 0.2);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Concentric Radar Rings */
        .radar-ring-inner {
            position: absolute;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 1px dashed rgba(56, 189, 248, 0.35);
        }

        .radar-crosshair-h {
            position: absolute;
            width: 100%;
            height: 1px;
            background: rgba(56, 189, 248, 0.25);
        }

        .radar-crosshair-v {
            position: absolute;
            height: 100%;
            width: 1px;
            background: rgba(56, 189, 248, 0.25);
        }

        /* Rotating Radar Sweep Beam */
        .radar-sweep-beam {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: conic-gradient(from 0deg, rgba(211, 47, 47, 0.6) 0deg, rgba(211, 47, 47, 0) 65deg, transparent 65deg);
            animation: radarSweep 3s linear infinite;
        }

        @keyframes radarSweep {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Blinking Radar Target Blip */
        .radar-blip {
            position: absolute;
            top: 38px;
            left: 95px;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #ef4444;
            box-shadow: 0 0 10px #ef4444;
            animation: blipPulse 1.2s ease-in-out infinite alternate;
        }

        @keyframes blipPulse {
            0% { transform: scale(0.7); opacity: 0.3; }
            100% { transform: scale(1.4); opacity: 1; }
        }

        /* Orbiting Search Plane */
        .orbiting-plane-track {
            position: absolute;
            width: 100%;
            height: 100%;
            animation: planeOrbit 5.5s linear infinite;
        }

        .orbiting-plane-icon {
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%) rotate(90deg);
            color: #ffffff;
            font-size: 1.25rem;
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.8));
        }

        @keyframes planeOrbit {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Drifting Aviation Clouds */
        .scene-clouds {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .scene-cloud {
            position: absolute;
            color: rgba(255, 255, 255, 0.1);
            animation: driftClouds 12s linear infinite;
        }

        .cloud-a { top: 15px; font-size: 2rem; animation-duration: 10s; }
        .cloud-b { bottom: 25px; font-size: 3rem; color: rgba(255, 255, 255, 0.06); animation-duration: 16s; animation-delay: -5s; }
        .cloud-c { top: 75px; font-size: 1.5rem; animation-duration: 12s; animation-delay: -3s; }

        @keyframes driftClouds {
            from { transform: translateX(650px); }
            to { transform: translateX(-150px); }
        }

        /* Big Flying Airplane across the whole 404 */
        .transiting-airplane-wrapper {
            position: absolute;
            top: 25px;
            left: 0;
            width: 100%;
            height: 120px;
            pointer-events: none;
            overflow: visible;
        }

        .transiting-airplane {
            position: absolute;
            display: flex;
            align-items: center;
            animation: flyAcross 4.2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .transiting-plane-icon {
            font-size: 3.2rem;
            color: #ffffff;
            filter: drop-shadow(0 6px 15px rgba(211, 47, 47, 0.8)) drop-shadow(0 0 25px rgba(255, 255, 255, 0.5));
            transform: rotate(-12deg);
        }

        .transiting-contrail {
            width: 220px;
            height: 4px;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.2) 20%, rgba(211, 47, 47, 0.6) 70%, #ff5252 100%);
            border-radius: 999px;
            box-shadow: 0 0 12px rgba(211, 47, 47, 0.9);
            margin-right: -12px;
        }

        .transiting-flame {
            width: 16px;
            height: 8px;
            background: radial-gradient(ellipse at center, #ffffff 0%, #ffeb3b 40%, #ff5722 80%, transparent 100%);
            border-radius: 50%;
            margin-right: -8px;
            animation: flameFlicker 0.15s infinite alternate;
        }

        @keyframes flameFlicker {
            0% { transform: scale(0.85); opacity: 0.7; }
            100% { transform: scale(1.3); opacity: 1; }
        }

        @keyframes flyAcross {
            0% {
                transform: translateX(-120px) translateY(30px);
            }
            50% {
                transform: translateX(240px) translateY(-15px);
            }
            100% {
                transform: translateX(650px) translateY(10px);
            }
        }

        /* Flight Board Ticket / Information Pill */
        .flight-ticket-pill {
            background: rgba(30, 41, 59, 0.85);
            border: 1px solid rgba(226, 232, 240, 0.15);
            border-radius: 14px;
            padding: 10px 22px;
            display: inline-flex;
            align-items: center;
            gap: 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.82rem;
            color: #cbd5e1;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            margin-bottom: 1.5rem;
            backdrop-filter: blur(8px);
        }

        .ticket-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .ticket-item strong {
            color: #ffffff;
        }

        .ticket-badge-lost {
            background: #ef4444;
            color: #ffffff;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.72rem;
            letter-spacing: 0.05em;
        }

        /* Typography & Error Description */
        .error-title {
            font-size: 1.9rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #ffffff;
            margin-bottom: 0.65rem;
        }

        .error-desc {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--text-muted);
            max-width: 580px;
            margin-bottom: 2rem;
        }

        /* Navigation Action Buttons */
        .action-button-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-ap-primary {
            background: linear-gradient(135deg, #0284c7, #0ea5e9);
            color: #ffffff;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.925rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 18px rgba(2, 132, 199, 0.45);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
        }

        .btn-ap-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(2, 132, 199, 0.6);
            background: linear-gradient(135deg, #0369a1, #0284c7);
        }

        .btn-ap-outline {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(226, 232, 240, 0.25);
            color: #e2e8f0;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.925rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.25s ease;
            cursor: pointer;
            backdrop-filter: blur(8px);
        }

        .btn-ap-outline:hover {
            background: rgba(51, 65, 85, 0.85);
            border-color: rgba(226, 232, 240, 0.5);
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* Bottom Footer */
        .error-footer {
            margin-top: 3rem;
            font-size: 0.8rem;
            color: #64748b;
            text-align: center;
        }

        @media (max-width: 640px) {
            .numbers-404 {
                font-size: 6rem;
            }
            .radar-center-0 {
                width: 105px;
                height: 105px;
            }
            .radar-ring-inner {
                width: 60px;
                height: 60px;
            }
            .flight-ticket-pill {
                flex-direction: column;
                gap: 8px;
            }
            .error-title {
                font-size: 1.45rem;
            }
            .error-top-brand {
                position: static;
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="sky-grid"></div>

    <!-- Top Left Brand Badge -->
    <div class="error-top-brand">
        <a href="{{ route('home') }}" class="brand-badge">ANGKASA PURA</a>
        <div class="brand-sub">PABX BILLING SYSTEM</div>
    </div>

    <div class="error-wrapper">
        <!-- Flight 404 Scene -->
        <div class="flight-scene-404">
            <!-- Background floating clouds -->
            <div class="scene-clouds">
                <span class="scene-cloud cloud-a"><i class="fa-solid fa-cloud"></i></span>
                <span class="scene-cloud cloud-b"><i class="fa-solid fa-cloud"></i></span>
                <span class="scene-cloud cloud-c"><i class="fa-solid fa-cloud"></i></span>
            </div>

            <!-- Big Transiting Jet Plane across the sky -->
            <div class="transiting-airplane-wrapper">
                <div class="transiting-airplane">
                    <div class="transiting-contrail"></div>
                    <div class="transiting-flame"></div>
                    <i class="fa-solid fa-plane-departure transiting-plane-icon"></i>
                </div>
            </div>

            <!-- 404 Numbers with Interactive Radar Sphere as Center 0 -->
            <div class="numbers-404">
                <div class="num-4">4</div>

                <!-- Center 0 Radar Device -->
                <div class="radar-center-0">
                    <div class="radar-sweep-beam"></div>
                    <div class="radar-crosshair-h"></div>
                    <div class="radar-crosshair-v"></div>
                    <div class="radar-ring-inner"></div>
                    <div class="radar-blip"></div>

                    <!-- Orbiting Search Plane inside radar -->
                    <div class="orbiting-plane-track">
                        <i class="fa-solid fa-plane orbiting-plane-icon"></i>
                    </div>
                </div>

                <div class="num-4">4</div>
            </div>
        </div>

        <!-- Flight Information Ticket -->
        <div class="flight-ticket-pill">
            <div class="ticket-item">
                <i class="fa-solid fa-ticket" style="color: #38bdf8;"></i>
                <span>FLIGHT: <strong>AP-404</strong></span>
            </div>
            <div class="ticket-item">
                <i class="fa-solid fa-tower-broadcast" style="color: #ef4444;"></i>
                <span>RADAR: <strong>OUT OF RANGE</strong></span>
            </div>
            <div class="ticket-item">
                <span class="ticket-badge-lost">ROUTE NOT FOUND</span>
            </div>
        </div>

        <!-- Description -->
        <h1 class="error-title">Rute Penerbangan Tidak Ditemukan</h1>
        <p class="error-desc">
            Sistem radar PABX Angkasa Pura tidak dapat menemukan rute atau halaman yang Anda tuju. 
            Alamat URL mungkin telah diubah, dihapus, atau sedang berada di luar jangkauan radar bandara.
        </p>

        <!-- Navigation Action Buttons -->
        <div class="action-button-group">
            <a href="{{ route('home') }}" class="btn-ap-primary">
                <i class="fa-solid fa-plane-arrival"></i>
                <span>Kembali ke Dashboard Utama</span>
            </a>
            <button type="button" onclick="window.history.back();" class="btn-ap-outline">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Halaman Sebelumnya</span>
            </button>
        </div>

        <div class="error-footer">
            &copy; {{ date('Y') }} Angkasa Pura PABX Billing System &bull; All Flight Systems Operational
        </div>
    </div>
</body>
</html>
