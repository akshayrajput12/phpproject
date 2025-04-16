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

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password";
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id, username, password, is_admin FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, create session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'];

                // Show dashboard selection if admin
                if ($user['is_admin']) {
                    // Redirect to dashboard selection page
                    header("Location: ../dashboard/select.php");
                } else {
                    // Redirect to user dashboard
                    header("Location: ../dashboard/index.php");
                }
                exit;
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "User not found";
        }

        $stmt->close();
    }
}

// Include header
include_once '../includes/header.php';
?>

<div class="flex justify-center items-center min-h-[80vh]">
    <div class="glass p-8 rounded-xl w-full max-w-md fade-in">
        <div class="text-center mb-8">
            <i class="fas fa-seedling text-accent text-4xl mb-4"></i>
            <h1 class="text-2xl font-bold">Welcome Back</h1>
            <p class="text-sm opacity-75 mt-2">Sign in to access your Farmer's Friend account</p>
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
                    <input type="text" id="username" name="username" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full pl-10 p-2.5" placeholder="Enter your username" required>
                </div>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="password" name="password" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full pl-10 p-2.5" placeholder="Enter your password" required>
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-primary to-accent hover:opacity-90 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition-all duration-300 hover-scale">
                Sign In
            </button>

            <div class="text-sm text-center mt-4">
                Don't have an account? <a href="register.php" class="text-accent hover:underline">Register here</a>
            </div>
        </form>
    </div>
</div>

<?php
// Include footer
include_once '../includes/footer.php';
?>
