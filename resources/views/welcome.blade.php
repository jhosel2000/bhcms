<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Barangay Health Center Monitoring and Scheduling System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #059669;
            --primary-hover: #047857;
            --secondary-color: #10b981;
            --text-dark: #1f2937;
            --text-light: #f9fafb;
            --background-light: #f3f4f6;
            --background-white: #ffffff;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Figtree, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: var(--background-light);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        .header {
            background: var(--background-white);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        .nav-logo img {
            width: 40px;
            height: 40px;
        }
        .nav-links a {
            color: var(--text-dark);
            text-decoration: none;
            margin-left: 1.5rem;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .nav-links a:hover {
            color: var(--primary-color);
        }
        .hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--text-light);
            padding: 6rem 2rem;
            text-align: center;
        }
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            text-decoration: none;
            border: 2px solid transparent;
        }
        .btn-primary {
            background: var(--background-white);
            color: var(--primary-color);
        }
        .btn-primary:hover {
            background: transparent;
            color: var(--background-white);
            border-color: var(--background-white);
        }
        .btn-secondary {
            background: transparent;
            color: var(--text-light);
            border-color: var(--text-light);
        }
        .btn-secondary:hover {
            background: var(--text-light);
            color: var(--primary-color);
        }
        .section {
            padding: 5rem 2rem;
        }
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 3rem;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }
        .feature-card {
            background: var(--background-white);
            padding: 2.5rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .feature-icon {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
        }
        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }
        .feature-card p {
            color: #6b7280;
        }
        .register-section {
            background: var(--background-white);
        }
        .register-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        .register-links a {
            color: var(--primary-color);
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            border: 2px solid var(--primary-color);
            font-weight: 600;
        }
        .register-links a:hover {
            background: var(--primary-color);
            color: white;
        }
        .footer {
            background: var(--text-dark);
            color: var(--text-light);
            padding: 3rem 2rem;
            text-align: center;
        }
        .footer p {
            margin-bottom: 1rem;
        }
        .social-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
        }
        .social-links a {
            color: var(--text-light);
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }
        .social-links a:hover {
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar container">
            <div class="nav-logo">
                <img src="{{ asset('images/logo.png') }}" alt="BHCMS Logo">
                <span>BHCMS</span>
            </div>
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#register">Register</a>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Health and Wellness for Everyone</h1>
                <p>Your trusted partner in community health. Easily schedule appointments, monitor your health, and stay connected with your local health center.</p>
                <div class="cta-buttons">
                    <a href="#register" class="btn btn-primary">Get Started</a>
                    <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
                </div>
            </div>
        </section>

        <section id="features" class="section">
            <div class="container">
                <h2 class="section-title">Why Choose BHCMS?</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <i class="fas fa-calendar-check feature-icon"></i>
                        <h3>Effortless Scheduling</h3>
                        <p>Book appointments with doctors, midwives, or BHWs in just a few clicks.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-heartbeat feature-icon"></i>
                        <h3>Personalized Health Tracking</h3>
                        <p>Keep a digital record of your health, from maternal care to lab results.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-users feature-icon"></i>
                        <h3>Community-Centric Care</h3>
                        <p>A unified platform for patients and healthcare providers to collaborate effectively.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-mobile-alt feature-icon"></i>
                        <h3>Accessible Anywhere</h3>
                        <p>Access your health information securely from any device, anytime.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="register" class="section register-section">
            <div class="container" style="text-align: center;">
                <h2 class="section-title">Join Our Health Community</h2>
                <p>Register based on your role to get started with BHCMS.</p>
                <div class="register-links">
                    <a href="{{ route('register.bhw') }}"><i class="fas fa-user-md"></i> BHW</a>
                    <a href="{{ route('register.doctor') }}"><i class="fas fa-stethoscope"></i> Doctor</a>
                    <a href="{{ route('register.midwife') }}"><i class="fas fa-baby"></i> Midwife</a>
                    <a href="{{ route('register.patient') }}"><i class="fas fa-user"></i> Patient</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Barangay Health Center Monitoring and Scheduling System. All Rights Reserved.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
