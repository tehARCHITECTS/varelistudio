<?php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT id, password FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $username;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    } else {
        $error = 'Invalid username or password';
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Varelis Architects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }
        .login-card {
            background: rgba(30, 41, 59, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h2 {
            color: #f1f5f9;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: #94a3b8;
        }
        .form-control {
            background: #0f172a;
            border: 1px solid rgba(148, 163, 184, 0.1);
            color: #f1f5f9;
            padding: 12px 20px;
            border-radius: 10px;
        }
        .form-control:focus {
            background: #0f172a;
            border-color: #6366f1;
            color: #f1f5f9;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        }
        .form-control::placeholder {
            color: #94a3b8;
        }
        .btn-login {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            color: white;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .back-link a {
            color: #6366f1;
            text-decoration: none;
            transition: color 0.3s;
        }
        .back-link a:hover {
            color: #8b5cf6;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2><i class="fas fa-lock"></i> Admin Login</h2>
                <p>Varelis Architects Dashboard</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label" style="color: #f1f5f9;">Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Enter username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" style="color: #f1f5f9;">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                </div>
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div class="back-link">
                <a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Website</a>
            </div>
            
            <div class="text-center mt-3" style="color: #94a3b8; font-size: 0.9rem;">
                <p>Default credentials: admin / admin123</p>
            </div>
        </div>
    </div>
</body>
</html>
