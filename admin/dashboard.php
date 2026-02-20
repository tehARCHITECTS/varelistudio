<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn = getDBConnection();
    $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header('Location: dashboard.php');
    exit;
}

// Fetch projects
$conn = getDBConnection();
$result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

// Fetch contacts
$contacts_result = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 10");
$contacts = [];
if ($contacts_result->num_rows > 0) {
    while($row = $contacts_result->fetch_assoc()) {
        $contacts[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Varelis Architects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #0f172a;
            color: #f1f5f9;
        }
        .sidebar {
            background: #1e293b;
            min-height: 100vh;
            padding: 2rem 0;
            border-right: 1px solid rgba(148, 163, 184, 0.1);
        }
        .sidebar-brand {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
        }
        .sidebar-brand h4 {
            color: #6366f1;
            font-weight: 700;
        }
        .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .nav-link:hover, .nav-link.active {
            color: #f1f5f9;
            background: rgba(99, 102, 241, 0.1);
            border-left-color: #6366f1;
        }
        .main-content {
            padding: 2rem;
        }
        .top-bar {
            background: #1e293b;
            padding: 1rem 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stat-card {
            background: #1e293b;
            padding: 1.5rem;
            border-radius: 15px;
            border: 1px solid rgba(148, 163, 184, 0.1);
            margin-bottom: 1.5rem;
        }
        .stat-card h3 {
            font-size: 2rem;
            color: #6366f1;
            margin-bottom: 0.5rem;
        }
        .table-container {
            background: #1e293b;
            padding: 1.5rem;
            border-radius: 15px;
            border: 1px solid rgba(148, 163, 184, 0.1);
        }
        .table {
            color: #f1f5f9;
        }
        .table thead {
            border-bottom: 2px solid rgba(148, 163, 184, 0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(99, 102, 241, 0.4);
        }
        .project-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="sidebar-brand">
                    <h4><i class="fas fa-building"></i> Varelis</h4>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a class="nav-link" href="add-project.php">
                        <i class="fas fa-plus"></i> Add Project
                    </a>
                    <a class="nav-link" href="../index.php" target="_blank">
                        <i class="fas fa-globe"></i> View Website
                    </a>
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="top-bar">
                    <h2>Dashboard</h2>
                    <div>
                        <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <h3><?php echo count($projects); ?></h3>
                            <p class="text-muted mb-0">Total Projects</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <h3><?php echo count($contacts); ?></h3>
                            <p class="text-muted mb-0">Recent Messages</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <h3>Active</h3>
                            <p class="text-muted mb-0">Website Status</p>
                        </div>
                    </div>
                </div>
                
                <!-- Projects Table -->
                <div class="table-container">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Projects</h4>
                        <a href="add-project.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Project
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($projects) > 0): ?>
                                    <?php foreach ($projects as $project): ?>
                                        <tr>
                                            <td><img src="../<?php echo htmlspecialchars($project['image']); ?>" class="project-img" alt=""></td>
                                            <td><?php echo htmlspecialchars($project['title']); ?></td>
                                            <td><span class="badge bg-primary"><?php echo htmlspecialchars($project['category']); ?></span></td>
                                            <td><?php echo date('M d, Y', strtotime($project['created_at'])); ?></td>
                                            <td>
                                                <a href="edit-project.php?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?delete=<?php echo $project['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No projects yet</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Recent Contacts -->
                <div class="table-container mt-4">
                    <h4 class="mb-3">Recent Contact Messages</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($contacts) > 0): ?>
                                    <?php foreach ($contacts as $contact): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($contact['name']); ?></td>
                                            <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                            <td><?php echo htmlspecialchars(substr($contact['message'], 0, 50)); ?>...</td>
                                            <td><?php echo date('M d, Y', strtotime($contact['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No messages yet</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
