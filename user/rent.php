<?php
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Motorbike.php';
require_once '../classes/Rental.php';

User::requireLogin();

$motorbike = new Motorbike();
$rental = new Rental();
$error = '';
$success = '';

// Get bike ID
if (!isset($_GET['id'])) {
    header("Location: bikes.php");
    exit();
}

$bikeId = $_GET['id'];
$bike = $motorbike->getById($bikeId);

if (!$bike) {
    header("Location: bikes.php");
    exit();
}

// Handle rental submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $rental->create(
        User::getCurrentUserId(),
        $bikeId,
        $_POST['start_datetime'] ?? ''
    );
    
    if ($result === true) {
        $success = "Bike rented successfully! You can view it in your rentals.";
        // Refresh bike data
        $bike = $motorbike->getById($bikeId);
    } else {
        $error = $result;
    }
}

// Get minimum datetime (current time + 1 hour)
$minDatetime = date('Y-m-d\TH:i', strtotime('+1 hour'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Bike - <?php echo SITE_NAME; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .navbar { background: #333; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 24px; }
        .navbar nav a { color: white; text-decoration: none; margin-left: 20px; }
        .navbar nav a:hover { text-decoration: underline; }
        .container { max-width: 800px; margin: 30px auto; padding: 0 20px; }
        .section { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .section h2 { margin-bottom: 20px; color: #333; }
        .bike-details { margin-bottom: 20px; }
        .bike-details h3 { color: #667eea; font-size: 28px; margin-bottom: 15px; }
        .bike-details p { margin-bottom: 10px; color: #555; }
        .bike-details .price { font-size: 24px; font-weight: bold; color: #333; margin: 15px 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; }
        input[type="datetime-local"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        .btn { padding: 12px 30px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn:hover { background: #5568d3; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #5a6268; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .badge { padding: 5px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .info-box { background: #e7f3ff; border-left: 4px solid #2196F3; padding: 15px; margin: 20px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1><?php echo SITE_NAME; ?></h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="bikes.php">Browse Bikes</a>
            <a href="rentals.php">My Rentals</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>
    
    <div class="container">
        <div class="section">
            <h2>Rent Motorbike</h2>
            
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success">
                    <?php echo htmlspecialchars($success); ?>
                    <br><br>
                    <a href="rentals.php" class="btn">View My Rentals</a>
                    <a href="bikes.php" class="btn btn-secondary" style="margin-left: 10px;">Browse More Bikes</a>
                </div>
            <?php endif; ?>
            
            <div class="bike-details">
                <h3><?php echo htmlspecialchars($bike['brand'] . ' ' . $bike['model']); ?></h3>
                <p><strong>Year:</strong> <?php echo $bike['year']; ?></p>
                <p>
                    <strong>Availability:</strong> 
                    <span class="badge <?php echo $bike['availability'] === 'Available' ? 'badge-success' : 'badge-danger'; ?>">
                        <?php echo $bike['availability']; ?>
                    </span>
                </p>
                <div class="price">$<?php echo number_format($bike['price_per_day'], 2); ?> per day</div>
                <?php if ($bike['description']): ?>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($bike['description']); ?></p>
                <?php endif; ?>
            </div>
            
            <?php if ($bike['availability'] === 'Available' && !$success): ?>
                <div class="info-box">
                    <strong>Rental Information:</strong>
                    <ul style="margin-left: 20px; margin-top: 10px;">
                        <li>Select your desired rental start date and time</li>
                        <li>The rental cost will be calculated automatically when you return the bike</li>
                        <li>Minimum rental period is 1 day</li>
                        <li>Partial days are rounded up to full days</li>
                    </ul>
                </div>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Start Date & Time:</label>
                        <input type="datetime-local" name="start_datetime" min="<?php echo $minDatetime; ?>" required>
                        <small style="color: #666; display: block; margin-top: 5px;">Select when you want to start your rental</small>
                    </div>
                    
                    <button type="submit" class="btn">Confirm Rental</button>
                    <a href="bikes.php" class="btn btn-secondary" style="margin-left: 10px; text-decoration: none;">Cancel</a>
                </form>
            <?php elseif ($bike['availability'] !== 'Available'): ?>
                <div class="error">This bike is currently not available for rent.</div>
                <a href="bikes.php" class="btn">Browse Other Bikes</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
