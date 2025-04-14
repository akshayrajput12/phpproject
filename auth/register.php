<?php
// Start session
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/index.php");
    exit;
}

// Include database connection
require_once '../config/database.php';

$error = '';
$success = '';

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Username already exists";
        } else {
            $stmt->close();
            
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = "Email already exists";
            } else {
                $stmt->close();
                
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashed_password);
                
                if ($stmt->execute()) {
                    $success = "Registration successful! You can now login.";
                    // Redirect to login page after 2 seconds
                    header("refresh:2;url=login.php");
                } else {
                    $error = "Registration failed: " . $conn->error;
                }
                
                $stmt->close();
            }
        }
    }
}

// Include header
include_once '../includes/header.php';
?>

<div class="flex justify-center items-center min-h-[80vh]">
    <div class="glass p-8 rounded-xl w-full max-w-md fade-in">
        <div class="text-center mb-8">
            <i class="fas fa-user-plus text-accent text-4xl mb-4"></i>
            <h1 class="text-2xl font-bold">Create an Account</h1>
            <p class="text-sm opacity-75 mt-2">Join Farmer's Friend and get personalized crop guidance</p>
        </div>
        
        <?php if ($error): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-white p-3 rounded-lg mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="bg-green-500/20 border border-green-500/50 text-white p-3 rounded-lg mb-4">
                <i class="fas fa-check-circle mr-2"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium mb-2">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" id="username" name="username" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full pl-10 p-2.5" placeholder="Choose a username" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-2">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" id="email" name="email" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full pl-10 p-2.5" placeholder="Enter your email" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="password" name="password" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full pl-10 p-2.5" placeholder="Create a password" required>
                </div>
                <p class="text-xs opacity-75 mt-1">Password must be at least 6 characters long</p>
            </div>
            
            <div class="mb-6">
                <label for="confirm_password" class="block text-sm font-medium mb-2">Confirm Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="confirm_password" name="confirm_password" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full pl-10 p-2.5" placeholder="Confirm your password" required>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-primary to-accent hover:opacity-90 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition-all duration-300 hover-scale">
                Create Account
            </button>
            
            <div class="text-sm text-center mt-4">
                Already have an account? <a href="login.php" class="text-accent hover:underline">Login here</a>
            </div>
        </form>
    </div>
</div>

<?php
// Include footer
include_once '../includes/footer.php';
?>
