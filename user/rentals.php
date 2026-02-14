<?php
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Rental.php';

User::requireLogin();

$rental = new Rental();
$userRentals = $rental->getByUserId(User::getCurrentUserId());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Rentals - <?php echo SITE_NAME; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .navbar { background: #333; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 24px; }
        .navbar nav a { color: white; text-decoration: none; margin-left: 20px; }
        .navbar nav a:hover { text-decoration: underline; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .section { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .section h2 { margin-bottom: 20px; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background: #f8f9fa; font-weight: bold; }
        table tr:hover { background: #f8f9fa; }
        .badge { padding: 5px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-success { background: #d4edda; color: #155724; }
        .rental-card { background: white; border: 1px solid #ddd; border-radius: 5px; padding: 20px; margin-bottom: 20px; }
        .rental-card h3 { color: #333; margin-bottom: 15px; }
        .rental-card .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; padding: 8px 0; border-bottom: 1px solid #eee; }
        .rental-card .info-row:last-child { border-bottom: none; }
        .rental-card .label { font-weight: bold; color: #666; }
        .rental-card .value { color: #333; }
        .btn { padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #5568d3; }
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
            <h2>My Rental History</h2>
            <a href="bikes.php" class="btn">Rent a Bike</a>
        </div>
        
        <?php if (empty($userRentals)): ?>
            <div class="section">
                <p>You haven't rented any bikes yet.</p>
                <br>
                <a href="bikes.php" class="btn">Browse Available Bikes</a>
            </div>
        <?php else: ?>
            <?php foreach ($userRentals as $r): ?>
                <div class="rental-card">
                    <h3><?php echo htmlspecialchars($r['brand'] . ' ' . $r['model']); ?></h3>
                    
                    <div class="info-row">
                        <span class="label">Rental ID:</span>
                        <span class="value">#<?php echo $r['id']; ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="label">Start Date:</span>
                        <span class="value"><?php echo date('F j, Y - g:i A', strtotime($r['start_datetime'])); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="label">End Date:</span>
                        <span class="value">
                            <?php 
                            if ($r['end_datetime']) {
                                echo date('F j, Y - g:i A', strtotime($r['end_datetime']));
                            } else {
                                echo 'Still rented';
                            }
                            ?>
                        </span>
                    </div>
                    
                    <?php if ($r['end_datetime'] && $r['start_datetime']): ?>
                        <?php 
                        $start = new DateTime($r['start_datetime']);
                        $end = new DateTime($r['end_datetime']);
                        $interval = $start->diff($end);
                        $days = $interval->days;
                        if ($interval->h > 0 || $interval->i > 0) {
                            $days++;
                        }
                        if ($days < 1) {
                            $days = 1;
                        }
                        ?>
                        <div class="info-row">
                            <span class="label">Duration:</span>
                            <span class="value"><?php echo $days; ?> day<?php echo $days > 1 ? 's' : ''; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="info-row">
                        <span class="label">Price per Day:</span>
                        <span class="value">$<?php echo number_format($r['price_per_day'], 2); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="label">Total Cost:</span>
                        <span class="value">
                            <?php 
                            if ($r['total_cost']) {
                                echo '$' . number_format($r['total_cost'], 2);
                            } else {
                                echo 'To be calculated';
                            }
                            ?>
                        </span>
                    </div>
                    
                    <div class="info-row">
                        <span class="label">Status:</span>
                        <span class="value">
                            <span class="badge <?php echo $r['status'] === 'Active' ? 'badge-warning' : 'badge-success'; ?>">
                                <?php echo $r['status']; ?>
                            </span>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
