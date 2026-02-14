<?php
require_once 'config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

// Redirect to appropriate dashboard if logged in
if (User::isLoggedIn()) {
    if (User::isAdmin()) {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: user/dashboard.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Premium Motorbike Rentals</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; }
        
        .hero { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column;
        }
        
        .navbar { 
            padding: 20px 50px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        
        .navbar h1 { font-size: 28px; }
        
        .navbar nav a { 
            color: white; 
            text-decoration: none; 
            margin-left: 30px; 
            padding: 10px 20px; 
            border: 2px solid white; 
            border-radius: 5px; 
            transition: all 0.3s; 
        }
        
        .navbar nav a:hover { 
            background: white; 
            color: #667eea; 
        }
        
        .hero-content { 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: center; 
            text-align: center; 
            padding: 50px 20px; 
        }
        
        .hero-content h2 { 
            font-size: 48px; 
            margin-bottom: 20px; 
        }
        
        .hero-content p { 
            font-size: 20px; 
            margin-bottom: 40px; 
            max-width: 600px; 
        }
        
        .cta-buttons { 
            display: flex; 
            gap: 20px; 
        }
        
        .btn { 
            padding: 15px 40px; 
            font-size: 18px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            text-decoration: none; 
            transition: transform 0.3s; 
        }
        
        .btn:hover { 
            transform: translateY(-2px); 
        }
        
        .btn-primary { 
            background: white; 
            color: #667eea; 
            font-weight: bold; 
        }
        
        .btn-secondary { 
            background: transparent; 
            color: white; 
            border: 2px solid white; 
        }
        
        .features { 
            background: white; 
            padding: 80px 50px; 
        }
        
        .features h2 { 
            text-align: center; 
            font-size: 36px; 
            margin-bottom: 50px; 
            color: #333; 
        }
        
        .features-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 40px; 
            max-width: 1200px; 
            margin: 0 auto; 
        }
        
        .feature { 
            text-align: center; 
            padding: 30px; 
        }
        
        .feature-icon { 
            font-size: 48px; 
            margin-bottom: 20px; 
        }
        
        .feature h3 { 
            color: #667eea; 
            margin-bottom: 15px; 
            font-size: 24px; 
        }
        
        .feature p { 
            color: #666; 
            line-height: 1.6; 
        }
        
        footer { 
            background: #333; 
            color: white; 
            text-align: center; 
            padding: 30px; 
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="navbar">
            <h1><?php echo SITE_NAME; ?></h1>
            <nav>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            </nav>
        </div>
        
        <div class="hero-content">
            <h2>Premium Motorbike Rentals</h2>
            <p>Experience the thrill of riding premium motorcycles. Browse our collection and hit the road today!</p>
            <div class="cta-buttons">
                <a href="register.php" class="btn btn-primary">Get Started</a>
                <a href="login.php" class="btn btn-secondary">Sign In</a>
            </div>
        </div>
    </div>
    
    <div class="features">
        <h2>Why Choose MotoCity?</h2>
        <div class="features-grid">
            <div class="feature">
                <div class="feature-icon">🏍️</div>
                <h3>Premium Bikes</h3>
                <p>Choose from a wide selection of top-brand motorcycles including Harley-Davidson, BMW, and Ducati.</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon">⚡</div>
                <h3>Easy Booking</h3>
                <p>Simple and fast rental process. Book your bike in just a few clicks with flexible rental periods.</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon">💰</div>
                <h3>Transparent Pricing</h3>
                <p>No hidden fees. Automatic cost calculation based on actual rental duration with competitive daily rates.</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon">🔒</div>
                <h3>Secure Platform</h3>
                <p>Your data is safe with us. We use industry-standard security measures to protect your information.</p>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
        <p>Premium Motorbike Rentals - Ride with Confidence</p>
    </footer>
</body>
</html>
