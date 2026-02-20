<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

$success = '';
$error = '';
$project = null;

// Get project ID
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$project_id = intval($_GET['id']);

// Fetch project
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: dashboard.php');
    exit;
}

$project = $result->fetch_assoc();
$stmt->close();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $tech_stack = htmlspecialchars(trim($_POST['tech_stack']));
    $category = htmlspecialchars(trim($_POST['category']));
    $demo_link = htmlspecialchars(trim($_POST['demo_link']));
    
    $image_path = $project['image'];
    
    // Handle new image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../assets/images/";
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = 'project_' . time() . '_' . uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Delete old image
                if (file_exists('../' . $project['image'])) {
                    unlink('../' . $project['image']);
                }
                $image_path = "assets/images/" . $new_filename;
            }
        }
    }
    
    $stmt = $conn->prepare("UPDATE projects SET title = ?, description = ?, image = ?, tech_stack = ?, category = ?, demo_link = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $title, $description, $image_path, $tech_stack, $category, $demo_link, $project_id);
    
    if ($stmt->execute()) {
        $success = "Project updated successfully!";
        // Refresh project data
        $stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->bind_param("i", $project_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $project = $result->fetch_assoc();
    } else {
        $error = "Failed to update project";
    }
    
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project - Varelis Architects</title>
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
        .nav-link:hover {
            color: #f1f5f9;
            background: rgba(99, 102, 241, 0.1);
            border-left-color: #6366f1;
        }
        .main-content {
            padding: 2rem;
        }
        .form-container {
            background: #1e293b;
            padding: 2rem;
            border-radius: 15px;
            border: 1px solid rgba(148, 163, 184, 0.1);
        }
        .form-control, .form-select {
            background: #0f172a;
            border: 1px solid rgba(148, 163, 184, 0.1);
            color: #f1f5f9;
        }
        .form-control:focus, .form-select:focus {
            background: #0f172a;
            border-color: #6366f1;
            color: #f1f5f9;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        }
        .form-label {
            color: #f1f5f9;
            font-weight: 500;
        }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(99, 102, 241, 0.4);
        }
        .current-image {
            max-width: 200px;
            border-radius: 10px;
            margin-top: 10px;
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
                    <a class="nav-link" href="dashboard.php">
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
                <h2 class="mb-4">Edit Project</h2>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="form-container">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Project Title</label>
                                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category" required>
                                    <option value="residential" <?php echo $project['category'] === 'residential' ? 'selected' : ''; ?>>Residential</option>
                                    <option value="commercial" <?php echo $project['category'] === 'commercial' ? 'selected' : ''; ?>>Commercial</option>
                                    <option value="interior" <?php echo $project['category'] === 'interior' ? 'selected' : ''; ?>>Interior</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="4" required><?php echo htmlspecialchars($project['description']); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tech Stack / Features (comma separated)</label>
                            <input type="text" class="form-control" name="tech_stack" value="<?php echo htmlspecialchars($project['tech_stack']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Demo Link (optional)</label>
                            <input type="url" class="form-control" name="demo_link" value="<?php echo htmlspecialchars($project['demo_link']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Project Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                            <div>
                                <img src="../<?php echo htmlspecialchars($project['image']); ?>" class="current-image" alt="Current">
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Project
                            </button>
                            <a href="dashboard.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
