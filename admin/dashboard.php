<?php
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Motorbike.php';
require_once '../classes/Rental.php';

User::requireAdmin();

$motorbike = new Motorbike();
$rental = new Rental();

$motorbikes = $motorbike->getAll();
$activeRentals = $rental->getActive();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .navbar { background: #333; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 24px; }
        .navbar nav a { color: white; text-decoration: none; margin-left: 20px; }
        .navbar nav a:hover { text-decoration: underline; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .welcome { background: white; padding: 20px; border-radius: 5px; margin-bottom: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stat-card h3 { color: #667eea; margin-bottom: 10px; }
        .stat-card .number { font-size: 36px; font-weight: bold; color: #333; }
        .section { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .section h2 { margin-bottom: 20px; color: #333; }
        .btn { padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #5568d3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-small { padding: 5px 10px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background: #f8f9fa; font-weight: bold; }
        table tr:hover { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1><?php echo SITE_NAME; ?> - Admin</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="motorbikes.php">Manage Bikes</a>
            <a href="rentals.php">Manage Rentals</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>
    
    <div class="container">
        <div class="welcome">
            <h2>Welcome, <?php echo htmlspecialchars(User::getCurrentUsername()); ?>!</h2>
            <p>You are logged in as an Administrator.</p>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Total Motorbikes</h3>
                <div class="number"><?php echo count($motorbikes); ?></div>
            </div>
            <div class="stat-card">
                <h3>Available Bikes</h3>
                <div class="number">
                    <?php 
                    $available = array_filter($motorbikes, function($m) { return $m['availability'] === 'Available'; });
                    echo count($available); 
                    ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>Active Rentals</h3>
                <div class="number"><?php echo count($activeRentals); ?></div>
            </div>
        </div>
        
        <div class="section">
            <h2>Active Rentals</h2>
            <?php if (empty($activeRentals)): ?>
                <p>No active rentals at the moment.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Motorbike</th>
                            <th>Start Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activeRentals as $r): ?>
                            <tr>
                                <td><?php echo $r['id']; ?></td>
                                <td><?php echo htmlspecialchars($r['username']); ?></td>
                                <td><?php echo htmlspecialchars($r['brand'] . ' ' . $r['model']); ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($r['start_datetime'])); ?></td>
                                <td>
                                    <a href="rentals.php" class="btn btn-small">View Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <div class="section">
            <h2>Quick Actions</h2>
            <a href="motorbikes.php?action=add" class="btn">Add New Motorbike</a>
            <a href="rentals.php" class="btn" style="margin-left: 10px;">View All Rentals</a>
        </div>
    </div>
</body>
</html>
