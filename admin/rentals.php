<?php
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Rental.php';

User::requireAdmin();

$rental = new Rental();
$error = '';
$success = '';

// Handle return action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'return') {
    $result = $rental->returnRental($_POST['rental_id']);
    if ($result === true) {
        $success = "Rental returned successfully!";
    } else {
        $error = $result;
    }
}

$allRentals = $rental->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rentals - <?php echo SITE_NAME; ?></title>
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
        .btn { padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #5568d3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .btn-small { padding: 5px 10px; font-size: 14px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background: #f8f9fa; font-weight: bold; }
        table tr:hover { background: #f8f9fa; }
        .badge { padding: 5px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-success { background: #d4edda; color: #155724; }
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
        <div class="section">
            <h2>Manage Rentals</h2>
            
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
        </div>
        
        <div class="section">
            <h2>All Rentals</h2>
            
            <?php if (empty($allRentals)): ?>
                <p>No rentals found.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Motorbike</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Cost</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allRentals as $r): ?>
                            <tr>
                                <td><?php echo $r['id']; ?></td>
                                <td><?php echo htmlspecialchars($r['username']); ?></td>
                                <td><?php echo htmlspecialchars($r['brand'] . ' ' . $r['model']); ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($r['start_datetime'])); ?></td>
                                <td><?php echo $r['end_datetime'] ? date('Y-m-d H:i', strtotime($r['end_datetime'])) : 'N/A'; ?></td>
                                <td><?php echo $r['total_cost'] ? '$' . number_format($r['total_cost'], 2) : 'N/A'; ?></td>
                                <td>
                                    <span class="badge <?php echo $r['status'] === 'Active' ? 'badge-warning' : 'badge-success'; ?>">
                                        <?php echo $r['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($r['status'] === 'Active'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="return">
                                            <input type="hidden" name="rental_id" value="<?php echo $r['id']; ?>">
                                            <button type="submit" class="btn btn-success btn-small">Return</button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color: #999;">Completed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
