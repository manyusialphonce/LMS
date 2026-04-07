<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to LMS</title>
    <link rel="stylesheet" href="includes/style.css">
    <style>
        body { 
            margin:0; 
            font-family:'Segoe UI', sans-serif; 
            background:#f4f6f9; 
            color:#333; 
        }
        header {
            background:#1f6de0; 
            color:#fff; 
            padding:50px 20px; 
            text-align:center;
        }
        header h1 { margin:0; font-size:2.5em; }
        header p { font-size:1.2em; margin-top:10px; }

        .container { max-width:1000px; margin:50px auto; padding:0 20px; text-align:center; }
        .features { display:flex; justify-content:space-around; flex-wrap:wrap; margin-top:50px; }
        .feature-card { background:#fff; border-radius:12px; padding:30px; margin:15px; flex:1 1 250px; box-shadow:0 4px 12px rgba(0,0,0,0.1);}
        .feature-card h3 { color:#1f6de0; margin-bottom:15px; }
        .feature-card p { font-size:1em; }

        .cta { margin-top:50px; }
        .cta a { background:#1f6de0; color:#fff; padding:15px 30px; border-radius:8px; text-decoration:none; font-weight:bold; margin:10px; display:inline-block;}
        .cta a:hover { background:#155ab6; }

        footer {
            background:#1f6de0; 
            color:#fff; 
            padding:30px 20px; 
            text-align:center;
            margin-top:50px;
        }
        footer p { margin:5px 0; }
        footer a { color:#fff; text-decoration:underline; }

        /* Mobile Responsive */
        @media (max-width:768px){
            header h1 { font-size:2em; }
            header p { font-size:1em; }
            .features { flex-direction:column; align-items:center; }
            .feature-card { max-width:90%; }
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome to Our Online Learning Platform</h1>
    <p>Learn online, take exams, and track your progress securely.</p>
</header>

<div class="container">
    <h2>Why Use Our LMS?</h2>
    <div class="features">
        <div class="feature-card">
            <h3>Online Courses</h3>
            <p>Access courses anytime, anywhere, with rich content and resources.</p>
        </div>
        <div class="feature-card">
            <h3>Secure Exams</h3>
            <p>Take exams online with timers and anti-cheating measures in place.</p>
        </div>
        <div class="feature-card">
            <h3>Track Your Progress</h3>
            <p>View results, analytics, and improve your performance over time.</p>
        </div>
    </div>

    <div class="cta">
        <a href="register.php">Register as Student</a>
        <a href="login.php">Login</a>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> LMS Online Learning Platform</p>
    <p>Contact: <a href="mailto:support@lms.com">support@lms.com</a></p>
    <p><a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
</footer>

</body>
</html>