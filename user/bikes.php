<?php
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Motorbike.php';

User::requireLogin();

$motorbike = new Motorbike();

// Handle search
$bikes = [];
$searchTerm = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $bikes = $motorbike->search($searchTerm);
} else {
    $bikes = $motorbike->getAvailable();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Bikes - <?php echo SITE_NAME; ?></title>
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
        .search-form { display: flex; gap: 10px; margin-bottom: 20px; }
        .search-form input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        .search-form button { padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .search-form button:hover { background: #5568d3; }
        .bikes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .bike-card { background: white; border: 1px solid #ddd; border-radius: 5px; padding: 20px; }
        .bike-card h3 { color: #333; margin-bottom: 10px; }
        .bike-card p { color: #666; margin-bottom: 8px; }
        .bike-card .price { font-size: 24px; font-weight: bold; color: #667eea; margin: 15px 0; }
        .bike-card .description { color: #888; font-size: 14px; margin: 10px 0; }
        .btn { padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; width: 100%; text-align: center; }
        .btn:hover { background: #5568d3; }
        .badge { padding: 5px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; display: inline-block; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
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
            <h2>Browse Motorbikes</h2>
            
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search by brand, model, or description..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit">Search</button>
                <?php if ($searchTerm): ?>
                    <a href="bikes.php" class="btn">Clear</a>
                <?php endif; ?>
            </form>
            
            <?php if ($searchTerm): ?>
                <p style="margin-bottom: 20px;">Found <?php echo count($bikes); ?> result(s) for "<?php echo htmlspecialchars($searchTerm); ?>"</p>
            <?php endif; ?>
        </div>
        
        <?php if (empty($bikes)): ?>
            <div class="section">
                <p>No motorbikes found.</p>
            </div>
        <?php else: ?>
            <div class="bikes-grid">
                <?php foreach ($bikes as $bike): ?>
                    <div class="bike-card">
                        <h3><?php echo htmlspecialchars($bike['brand'] . ' ' . $bike['model']); ?></h3>
                        <p><strong>Year:</strong> <?php echo $bike['year']; ?></p>
                        <p>
                            <strong>Status:</strong> 
                            <span class="badge <?php echo $bike['availability'] === 'Available' ? 'badge-success' : 'badge-danger'; ?>">
                                <?php echo $bike['availability']; ?>
                            </span>
                        </p>
                        <div class="price">$<?php echo number_format($bike['price_per_day'], 2); ?> / day</div>
                        <?php if ($bike['description']): ?>
                            <p class="description"><?php echo htmlspecialchars($bike['description']); ?></p>
                        <?php endif; ?>
                        <?php if ($bike['availability'] === 'Available'): ?>
                            <a href="rent.php?id=<?php echo $bike['id']; ?>" class="btn">Rent Now</a>
                        <?php else: ?>
                            <button class="btn" disabled style="background: #ccc; cursor: not-allowed;">Not Available</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
