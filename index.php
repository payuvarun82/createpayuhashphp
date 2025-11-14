<?php
session_start();
$site_title = "Simple PHP Project";
$version = "1.0.0";
$features = ["PHP 7.4+ Compatible", "Simple Routing", "Session Management", "Form Validation", "Database Ready"];

$message = "";
$isLoggedIn = isset($_SESSION['user_name']) && !empty($_SESSION['user_name']);

if ($_POST) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    if (!empty($name) && !empty($email)) {
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        
        // Set timezone to IST and get current time
        date_default_timezone_set('Asia/Kolkata');
        $_SESSION['login_time'] = date('d M Y, H:i:s');
        $_SESSION['login_timestamp'] = time();
        
        $isLoggedIn = true;
        $message = "Welcome " . htmlspecialchars($name) . "! You now have access to all API endpoints.";
    } else {
        $message = "Please fill in all fields to access the application.";
    }
}

// Function to get current IST time
function getCurrentISTTime() {
    date_default_timezone_set('Asia/Kolkata');
    return date('d M Y, H:i:s');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Modern CSS Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Color Palette */
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #f1f5f9;
            --accent-color: #0ea5e9;
            --success-color: #10b981;
            --error-color: #ef4444;
            --warning-color: #f59e0b;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-light: #94a3b8;
            --border-color: #e2e8f0;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            
            /* Typography */
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-size-xs: 0.75rem;
            --font-size-sm: 0.875rem;
            --font-size-base: 1rem;
            --font-size-lg: 1.125rem;
            --font-size-xl: 1.25rem;
            --font-size-2xl: 1.5rem;
            --font-size-3xl: 1.875rem;
            --font-size-4xl: 2.25rem;
            
            /* Spacing */
            --spacing-1: 0.25rem;
            --spacing-2: 0.5rem;
            --spacing-3: 0.75rem;
            --spacing-4: 1rem;
            --spacing-5: 1.25rem;
            --spacing-6: 1.5rem;
            --spacing-8: 2rem;
            --spacing-10: 2.5rem;
            --spacing-12: 3rem;
            --spacing-16: 4rem;
            
            /* Border Radius */
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            
            /* Transitions */
            --transition-fast: 150ms ease-in-out;
            --transition-normal: 250ms ease-in-out;
            --transition-slow: 350ms ease-in-out;
        }

        body {
            font-family: var(--font-family);
            line-height: 1.6;
            color: var(--text-primary);
            background-color: var(--bg-light);
            font-size: var(--font-size-base);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--spacing-4);
        }

        /* Header Styles */
        .main-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            box-shadow: var(--shadow-lg);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-4) 0;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
        }

        .logo-section i {
            font-size: var(--font-size-2xl);
            color: #fbbf24;
        }

        .logo-section h1 {
            font-size: var(--font-size-2xl);
            font-weight: 700;
            margin: 0;
        }

        .version-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: var(--spacing-1) var(--spacing-3);
            border-radius: var(--radius-xl);
            font-size: var(--font-size-sm);
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .header-nav {
            display: flex;
            gap: var(--spacing-6);
        }

        .nav-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: var(--spacing-2);
            padding: var(--spacing-2) var(--spacing-4);
            border-radius: var(--radius-md);
            transition: var(--transition-fast);
            font-weight: 500;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
        }

        /* User Status in Header */
        .user-status {
            display: flex;
            align-items: center;
            gap: var(--spacing-2);
            background: rgba(255, 255, 255, 0.1);
            padding: var(--spacing-2) var(--spacing-4);
            border-radius: var(--radius-md);
            font-size: var(--font-size-sm);
            backdrop-filter: blur(10px);
        }

        .user-status i {
            color: #10b981;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: var(--spacing-8) 0;
        }

        /* Alert Styles */
        .alert {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
            padding: var(--spacing-4);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-6);
            font-weight: 500;
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        /* Hero Section */
        .hero-section {
            text-align: center;
            margin-bottom: var(--spacing-12);
            padding: var(--spacing-16) 0;
            background: linear-gradient(135deg, var(--bg-white) 0%, var(--secondary-color) 100%);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
        }

        .hero-content h2 {
            font-size: var(--font-size-4xl);
            font-weight: 700;
            margin-bottom: var(--spacing-4);
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-content p {
            font-size: var(--font-size-lg);
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Horizontal Session Bar */
        .session-bar {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
            padding: var(--spacing-4);
            border-radius: var(--radius-xl);
            margin-bottom: var(--spacing-8);
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .session-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 25%, rgba(255,255,255,0.1) 25%, rgba(255,255,255,0.1) 50%, transparent 50%, transparent 75%, rgba(255,255,255,0.1) 75%);
            background-size: 20px 20px;
            animation: stripes 3s linear infinite;
        }

        @keyframes stripes {
            0% { transform: translateX(-20px); }
            100% { transform: translateX(0); }
        }

        .session-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .session-info-horizontal {
            display: flex;
            gap: var(--spacing-8);
            align-items: center;
        }

        .session-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
        }

        .session-item i {
            font-size: var(--font-size-lg);
            color: #dcfce7;
        }

        .session-details {
            display: flex;
            flex-direction: column;
        }

        .session-label {
            font-size: var(--font-size-xs);
            color: #dcfce7;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .session-value {
            font-size: var(--font-size-base);
            font-weight: 600;
            color: white;
        }

        .session-actions {
            display: flex;
            gap: var(--spacing-3);
            align-items: center;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: var(--spacing-6);
            margin-bottom: var(--spacing-8);
        }

        /* Card Styles */
        .card {
            background: var(--bg-white);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: var(--transition-normal);
            border: 1px solid var(--border-color);
        }

        .card:hover {
            box-shadow: var(--shadow-xl);
            transform: translateY(-2px);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
            padding: var(--spacing-6);
            background: linear-gradient(135deg, var(--secondary-color) 0%, #e2e8f0 100%);
            border-bottom: 1px solid var(--border-color);
        }

        .card-header i {
            font-size: var(--font-size-xl);
            color: var(--primary-color);
        }

        .card-header h3 {
            font-size: var(--font-size-xl);
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-content {
            padding: var(--spacing-6);
        }

        /* Features List */
        .features-list {
            list-style: none;
            space-y: var(--spacing-3);
        }

        .features-list li {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
            padding: var(--spacing-3) 0;
            border-bottom: 1px solid var(--border-color);
        }

        .features-list li:last-child {
            border-bottom: none;
        }

        .features-list i {
            color: var(--success-color);
            font-size: var(--font-size-sm);
            width: 16px;
        }

        /* Form Styles */
        .registration-form {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-5);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-2);
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: var(--spacing-2);
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-group label i {
            color: var(--primary-color);
            width: 16px;
        }

        .form-group input {
            padding: var(--spacing-3) var(--spacing-4);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: var(--font-size-base);
            transition: var(--transition-fast);
            background: var(--bg-white);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-2);
            padding: var(--spacing-3) var(--spacing-6);
            border: none;
            border-radius: var(--radius-md);
            font-size: var(--font-size-base);
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition-fast);
            min-height: 44px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--error-color) 0%, #dc2626 100%);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
        }

        .btn-white {
            background: white;
            color: var(--success-color);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-white:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-1px);
        }

        /* Server Info */
        .server-info {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-4);
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
            padding: var(--spacing-3);
            background: var(--bg-light);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
        }

        .info-item i {
            color: var(--primary-color);
            width: 20px;
            text-align: center;
        }

        .info-item div {
            flex: 1;
        }

        .info-item label {
            display: block;
            font-size: var(--font-size-sm);
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: var(--spacing-1);
        }

        .info-item span {
            display: block;
            color: var(--text-primary);
            font-weight: 500;
        }

        .session-id {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: var(--font-size-sm);
            word-break: break-all;
            background: var(--bg-white);
            padding: var(--spacing-2);
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
        }

        .session-id-horizontal {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: var(--font-size-sm);
            color: #dcfce7;
            font-weight: 400;
        }

        /* API Grid */
        .api-grid {
            display: grid;
            gap: var(--spacing-4);
        }

        .api-link {
            display: flex;
            align-items: center;
            gap: var(--spacing-4);
            padding: var(--spacing-4);
            background: var(--bg-light);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            text-decoration: none;
            color: var(--text-primary);
            transition: var(--transition-normal);
            position: relative;
            overflow: hidden;
        }

        .api-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            transform: scaleX(0);
            transition: var(--transition-normal);
            transform-origin: left;
        }

        .api-link:hover::before {
            transform: scaleX(1);
        }

        .api-link:hover {
            background: var(--bg-white);
            border-color: var(--primary-color);
            box-shadow: var(--shadow-md);
        }

        .api-link > i:first-child {
            font-size: var(--font-size-2xl);
            color: var(--primary-color);
            width: 40px;
            text-align: center;
        }

        .api-link > div {
            flex: 1;
        }

        .api-link h4 {
            font-size: var(--font-size-lg);
            font-weight: 600;
            margin-bottom: var(--spacing-1);
            color: var(--text-primary);
        }

        .api-link p {
            font-size: var(--font-size-sm);
            color: var(--text-secondary);
        }

        .api-link > i:last-child {
            color: var(--text-light);
            font-size: var(--font-size-sm);
        }

        /* API Link Color Variants */
        .api-link.payment > i:first-child { color: #059669; }
        .api-link.verify > i:first-child { color: #0ea5e9; }
        .api-link.refund > i:first-child { color: #f59e0b; }
        .api-link.status > i:first-child { color: #8b5cf6; }
        .api-link.settlement > i:first-child { color: #ef4444; }

        /* Footer */
        .main-footer {
            background: var(--text-primary);
            color: white;
            padding: var(--spacing-8) 0;
            margin-top: auto;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-content p {
            display: flex;
            align-items: center;
            gap: var(--spacing-2);
        }

        .footer-content .fa-heart {
            color: #ef4444;
            animation: pulse 2s infinite;
        }

        .footer-links {
            display: flex;
            gap: var(--spacing-4);
        }

        .footer-links a {
            color: var(--text-light);
            font-size: var(--font-size-lg);
            transition: var(--transition-fast);
        }

        .footer-links a:hover {
            color: white;
            transform: translateY(-2px);
        }

        /* Animations */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 0 var(--spacing-3);
            }
            
            .header-content {
                flex-direction: column;
                gap: var(--spacing-4);
                text-align: center;
            }
            
            .header-nav {
                justify-content: center;
                flex-wrap: wrap;
                gap: var(--spacing-3);
            }
            
            .content-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-4);
            }
            
            .hero-content h2 {
                font-size: var(--font-size-3xl);
            }
            
            .hero-section {
                padding: var(--spacing-8) 0;
            }
            
            .card-header,
            .card-content {
                padding: var(--spacing-4);
            }
            
            .footer-content {
                flex-direction: column;
                gap: var(--spacing-4);
                text-align: center;
            }
            
            .api-link {
                flex-direction: column;
                text-align: center;
                gap: var(--spacing-3);
            }
            
            .api-link > i:last-child {
                display: none;
            }

            /* Mobile Session Bar */
            .session-content {
                flex-direction: column;
                gap: var(--spacing-4);
            }

            .session-info-horizontal {
                flex-direction: column;
                gap: var(--spacing-4);
                width: 100%;
            }

            .session-item {
                justify-content: center;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .logo-section {
                flex-direction: column;
                gap: var(--spacing-2);
            }
            
            .version-badge {
                margin-top: var(--spacing-2);
            }
            
            .hero-content h2 {
                font-size: var(--font-size-2xl);
            }
            
            .hero-content p {
                font-size: var(--font-size-base);
            }

            .session-info-horizontal {
                gap: var(--spacing-2);
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo-section">
                    <i class="fas fa-rocket"></i>
                    <h1><?php echo $site_title; ?></h1>
                    <span class="version-badge">v<?php echo $version; ?></span>
                </div>
                <div style="display: flex; align-items: center; gap: var(--spacing-6);">
                    <nav class="header-nav">
                        <a href="index.php" class="nav-link active">
                            <i class="fas fa-home"></i> Home
                        </a>
                        <a href="about.php" class="nav-link">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                        <a href="contact.php" class="nav-link">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                    </nav>
                    <?php if ($isLoggedIn): ?>
                        <div class="user-status">
                            <i class="fas fa-user-check"></i>
                            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            
            <!-- Alert Messages -->
            <?php if ($message): ?>
                <div class="alert <?php echo strpos($message, 'Welcome') !== false ? 'alert-success' : 'alert-error'; ?>">
                    <i class="fas <?php echo strpos($message, 'Welcome') !== false ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Horizontal Session Bar (Only for logged in users) -->
            <?php if ($isLoggedIn): ?>
            <div class="session-bar">
                <div class="session-content">
                    <div class="session-info-horizontal">
                        <div class="session-item">
                            <i class="fas fa-user"></i>
                            <div class="session-details">
                                <span class="session-label">User</span>
                                <span class="session-value"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            </div>
                        </div>
                        <div class="session-item">
                            <i class="fas fa-envelope"></i>
                            <div class="session-details">
                                <span class="session-label">Email</span>
                                <span class="session-value"><?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                            </div>
                        </div>
                        <div class="session-item">
                            <i class="fas fa-clock"></i>
                            <div class="session-details">
                                <span class="session-label">Login Time (IST)</span>
                                <span class="session-value"><?php echo $_SESSION['login_time'] ?? 'Unknown'; ?></span>
                            </div>
                        </div>
                        <div class="session-item">
                            <i class="fas fa-fingerprint"></i>
                            <div class="session-details">
                                <span class="session-label">Session ID</span>
                                <span class="session-value session-id-horizontal"><?php echo substr(session_id(), 0, 8) . '...'; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="session-actions">
                        <a href="clear-session.php" class="btn btn-white">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Content Grid -->
            <div class="content-grid">
                
                <!-- BEFORE LOGIN: Show Features, Login Form, Server Info -->
                <?php if (!$isLoggedIn): ?>
                
                <!-- Features Card -->
                <div class="card features-card">
                    <div class="card-header">
                        <i class="fas fa-star"></i>
                        <h3>Application Features</h3>
                    </div>
                    <div class="card-content">
                        <ul class="features-list">
                            <?php foreach ($features as $feature): ?>
                                <li>
                                    <i class="fas fa-check"></i>
                                    <?php echo $feature; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Login Form -->
                <div class="card form-card">
                    <div class="card-header">
                        <i class="fas fa-sign-in-alt"></i>
                        <h3>User Login</h3>
                    </div>
                    <div class="card-content">
                        <form method="POST" action="" class="registration-form">
                            <div class="form-group">
                                <label for="name">
                                    <i class="fas fa-user"></i>
                                    Full Name
                                </label>
                                <input type="text" id="name" name="name" required placeholder="Enter your full name">
                            </div>
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope"></i>
                                    Email Address
                                </label>
                                <input type="email" id="email" name="email" required placeholder="Enter your email address">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i>
                                Login to Application
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Server Info Card -->
                <div class="card server-card">
                    <div class="card-header">
                        <i class="fas fa-server"></i>
                        <h3>Server Information</h3>
                    </div>
                    <div class="card-content">
                        <div class="server-info">
                            <div class="info-item">
                                <i class="fab fa-php"></i>
                                <div>
                                    <label>PHP Version</label>
                                    <span><?php echo phpversion(); ?></span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-server"></i>
                                <div>
                                    <label>Server Software</label>
                                    <span><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <label>Current Time (IST)</label>
                                    <span id="current-time"></span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-fingerprint"></i>
                                <div>
                                    <label>Session ID</label>
                                    <span class="session-id"><?php echo session_id(); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php else: ?>
                
                <!-- AFTER LOGIN: ONLY Show APIs (Session info is now horizontal above) -->
                
                <!-- API Endpoints -->
                <div class="card api-card" style="grid-column: 1 / -1;">
                    <div class="card-header">
                        <i class="fas fa-code"></i>
                        <h3>API Endpoints</h3>
                    </div>
                    <div class="card-content">
                        <div style="margin-bottom: var(--spacing-4); padding: var(--spacing-3); background: #dcfce7; border-radius: var(--radius-md); color: #166534;">
                            <i class="fas fa-check-circle"></i> <strong>API Access Available:</strong> All endpoints are ready for use. Click any API to open in a new window.
                        </div>
                        <div class="api-grid">
                            <a href="api.php" target="_blank" class="api-link payment">
                                <i class="fas fa-credit-card"></i>
                                <div>
                                    <h4>Initiate Payment</h4>
                                    <p>Start payment process</p>
                                </div>
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <a href="verify.php" target="_blank" class="api-link verify">
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <h4>Verify Payment</h4>
                                    <p>Verify payment status</p>
                                </div>
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <a href="refund.php" target="_blank" class="api-link refund">
                                <i class="fas fa-undo"></i>
                                <div>
                                    <h4>Refund API</h4>
                                    <p>Process refunds</p>
                                </div>
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <a href="refundstatus.php" target="_blank" class="api-link status">
                                <i class="fas fa-search"></i>
                                <div>
                                    <h4>Refund Status</h4>
                                    <p>Check refund status</p>
                                </div>
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <a href="payu_settlement_api.php" target="_blank" class="api-link settlement">
                                <i class="fas fa-calculator"></i>
                                <div>
                                    <h4>Settlement API</h4>
                                    <p>Handle settlements</p>
                                </div>
                                <i class="fas fa-external-link-alt"></i>
                            </a>

                              <a href="createPayuHashKey.php" target="_blank" class="api-link settlement">
                                <i class="fas fa-calculator"></i>
                                <div>
                                    <h4>Create Hash API</h4>
                                    <p>Handle settlements</p>
                                </div>
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <?php endif; ?>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <p>&copy; 2024 Simple PHP Project. Built with <i class="fas fa-heart"></i> and PHP.</p>
                <div class="footer-links">
                    <a href="#"><i class="fab fa-github"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function updateISTTime() {
            const now = new Date();
            const istTime = new Date(now.toLocaleString("en-US", { timeZone: "Asia/Kolkata" }));

            const day = istTime.getDate();
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];
            const month = monthNames[istTime.getMonth()];
            const year = istTime.getFullYear();
            const hours = String(istTime.getHours()).padStart(2, '0');
            const minutes = String(istTime.getMinutes()).padStart(2, '0');
            const seconds = String(istTime.getSeconds()).padStart(2, '0');

            const formattedTime = `${day} ${month} ${year} ${hours}:${minutes}:${seconds}`;
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = formattedTime;
            }
        }

        updateISTTime();
        setInterval(updateISTTime, 1000);

        // Add smooth scrolling and animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in animation to cards
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in');
            });

            // Add hover effects to API links (only if user is logged in)
            const apiLinks = document.querySelectorAll('.api-link');
            apiLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                link.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>

</html>