<?php
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Motorbike.php';

User::requireAdmin();

$motorbike = new Motorbike();
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $result = $motorbike->insert($_POST);
                if ($result === true) {
                    $success = "Motorbike added successfully!";
                } else {
                    $error = $result;
                }
                break;
                
            case 'edit':
                $result = $motorbike->update($_POST['id'], $_POST);
                if ($result === true) {
                    $success = "Motorbike updated successfully!";
                } else {
                    $error = $result;
                }
                break;
                
            case 'delete':
                if ($motorbike->delete($_POST['id'])) {
                    $success = "Motorbike deleted successfully!";
                } else {
                    $error = "Failed to delete motorbike";
                }
                break;
        }
    }
}

// Get all motorbikes
$motorbikes = $motorbike->getAll();

// Check if editing
$editMode = false;
$editBike = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $editBike = $motorbike->getById($_GET['id']);
    if ($editBike) {
        $editMode = true;
    }
}

$showForm = isset($_GET['action']) && ($_GET['action'] === 'add' || $editMode);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Motorbikes - <?php echo SITE_NAME; ?></title>
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
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-small { padding: 5px 10px; font-size: 14px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        textarea { min-height: 100px; resize: vertical; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background: #f8f9fa; font-weight: bold; }
        table tr:hover { background: #f8f9fa; }
        .badge { padding: 5px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
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
            <h2>Manage Motorbikes</h2>
            
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if (!$showForm): ?>
                <a href="?action=add" class="btn" style="margin-bottom: 20px;">Add New Motorbike</a>
            <?php else: ?>
                <a href="motorbikes.php" class="btn" style="margin-bottom: 20px;">Back to List</a>
            <?php endif; ?>
        </div>
        
        <?php if ($showForm): ?>
            <div class="section">
                <h2><?php echo $editMode ? 'Edit Motorbike' : 'Add New Motorbike'; ?></h2>
                
                <form method="POST" action="motorbikes.php">
                    <input type="hidden" name="action" value="<?php echo $editMode ? 'edit' : 'add'; ?>">
                    <?php if ($editMode): ?>
                        <input type="hidden" name="id" value="<?php echo $editBike['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Brand:</label>
                        <input type="text" name="brand" value="<?php echo $editMode ? htmlspecialchars($editBike['brand']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Model:</label>
                        <input type="text" name="model" value="<?php echo $editMode ? htmlspecialchars($editBike['model']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Year:</label>
                        <input type="number" name="year" value="<?php echo $editMode ? $editBike['year'] : date('Y'); ?>" min="1900" max="<?php echo date('Y') + 1; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Price per Day ($):</label>
                        <input type="number" name="price_per_day" step="0.01" value="<?php echo $editMode ? $editBike['price_per_day'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description"><?php echo $editMode ? htmlspecialchars($editBike['description']) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-success"><?php echo $editMode ? 'Update' : 'Add'; ?> Motorbike</button>
                </form>
            </div>
        <?php else: ?>
            <div class="section">
                <h2>All Motorbikes</h2>
                
                <?php if (empty($motorbikes)): ?>
                    <p>No motorbikes found.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Year</th>
                                <th>Price/Day</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($motorbikes as $bike): ?>
                                <tr>
                                    <td><?php echo $bike['id']; ?></td>
                                    <td><?php echo htmlspecialchars($bike['brand']); ?></td>
                                    <td><?php echo htmlspecialchars($bike['model']); ?></td>
                                    <td><?php echo $bike['year']; ?></td>
                                    <td>$<?php echo number_format($bike['price_per_day'], 2); ?></td>
                                    <td>
                                        <span class="badge <?php echo $bike['availability'] === 'Available' ? 'badge-success' : 'badge-danger'; ?>">
                                            <?php echo $bike['availability']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="?action=edit&id=<?php echo $bike['id']; ?>" class="btn btn-small">Edit</a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this motorbike?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $bike['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-small">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
