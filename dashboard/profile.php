<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Include database connection
require_once '../config/database.php';

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$error = '';
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $location = sanitize($_POST['location']);
    $soil_type = sanitize($_POST['soil_type']);
    $phone = sanitize($_POST['phone'] ?? '');
    $bio = sanitize($_POST['bio'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate input
    if (empty($username) || empty($email)) {
        $error = "Username and email are required";
    } elseif ($username !== $user['username']) {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $username, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already exists";
        }

        $stmt->close();
    } elseif ($email !== $user['email']) {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already exists";
        }

        $stmt->close();
    }

    // Check if password change is requested
    $update_password = false;
    if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = "All password fields are required for password change";
        } elseif ($new_password !== $confirm_password) {
            $error = "New passwords do not match";
        } elseif (strlen($new_password) < 6) {
            $error = "New password must be at least 6 characters long";
        } else {
            // Verify current password
            if (!password_verify($current_password, $user['password'])) {
                $error = "Current password is incorrect";
            } else {
                $update_password = true;
            }
        }
    }

    // Update user data if no errors
    if (empty($error)) {
        if ($update_password) {
            // Hash new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update user with new password
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, location = ?, soil_type = ?, phone = ?, bio = ?, password = ?, last_login = NOW() WHERE id = ?");
            $stmt->bind_param("sssssssi", $username, $email, $location, $soil_type, $phone, $bio, $hashed_password, $user_id);
        } else {
            // Update user without changing password
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, location = ?, soil_type = ?, phone = ?, bio = ?, last_login = NOW() WHERE id = ?");
            $stmt->bind_param("ssssssi", $username, $email, $location, $soil_type, $phone, $bio, $user_id);
        }

        if ($stmt->execute()) {
            $success = "Profile updated successfully";

            // Update session username if changed
            if ($username !== $user['username']) {
                $_SESSION['username'] = $username;
            }

            // Refresh user data
            $stmt->close();
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
        } else {
            $error = "Failed to update profile: " . $conn->error;
        }

        $stmt->close();
    }
}

// Include header
include_once '../includes/header.php';
?>

<div class="flex flex-col md:flex-row">
    <!-- Sidebar -->
    <div class="w-full md:w-64 md:min-h-screen">
        <?php include_once '../includes/sidebar.php'; ?>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4">
        <h1 class="text-2xl font-bold mb-6 fade-in">Profile Settings</h1>

        <?php if ($error): ?>
            <div class="glass p-4 rounded-lg mb-6 bg-red-500/20 border border-red-500/50 fade-in">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-400 mr-2"></i>
                    <span><?php echo $error; ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="glass p-4 rounded-lg mb-6 bg-green-500/20 border border-green-500/50 fade-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-400 mr-2"></i>
                    <span><?php echo $success; ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="glass p-6 rounded-lg mb-6 fade-in">
            <h2 class="text-lg font-semibold mb-4">Account Information</h2>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="username" class="block text-sm font-medium mb-2">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" required>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="location" class="block text-sm font-medium mb-2">Location</label>
                        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($user['location'] ?? ''); ?>" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Enter your city">
                        <p class="text-xs opacity-75 mt-1">Enter your city name for weather information</p>
                    </div>

                    <div>
                        <label for="soil_type" class="block text-sm font-medium mb-2">Soil Type</label>
                        <select id="soil_type" name="soil_type" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5">
                            <option value="" <?php echo empty($user['soil_type']) ? 'selected' : ''; ?>>Select soil type</option>
                            <option value="Clay" <?php echo ($user['soil_type'] ?? '') === 'Clay' ? 'selected' : ''; ?>>Clay</option>
                            <option value="Sandy" <?php echo ($user['soil_type'] ?? '') === 'Sandy' ? 'selected' : ''; ?>>Sandy</option>
                            <option value="Silt" <?php echo ($user['soil_type'] ?? '') === 'Silt' ? 'selected' : ''; ?>>Silt</option>
                            <option value="Loam" <?php echo ($user['soil_type'] ?? '') === 'Loam' ? 'selected' : ''; ?>>Loam</option>
                            <option value="Peat" <?php echo ($user['soil_type'] ?? '') === 'Peat' ? 'selected' : ''; ?>>Peat</option>
                            <option value="Chalk" <?php echo ($user['soil_type'] ?? '') === 'Chalk' ? 'selected' : ''; ?>>Chalk</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium mb-2">Phone Number</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Enter your phone number">
                    </div>

                    <div>
                        <label for="bio" class="block text-sm font-medium mb-2">Bio</label>
                        <textarea id="bio" name="bio" rows="3" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Tell us about yourself"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-6 mt-6">
                    <h3 class="text-md font-semibold mb-4">Change Password</h3>
                    <p class="text-sm opacity-75 mb-4">Leave these fields empty if you don't want to change your password</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label for="current_password" class="block text-sm font-medium mb-2">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Enter current password">
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium mb-2">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Enter new password">
                        </div>

                        <div>
                            <label for="confirm_password" class="block text-sm font-medium mb-2">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Confirm new password">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-primary to-accent hover:opacity-90 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-300 hover-scale">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <div class="glass p-6 rounded-lg mb-6 fade-in">
            <h2 class="text-lg font-semibold mb-4">Account Security</h2>

            <div class="mb-4">
                <p class="text-sm opacity-75">Your account was created on <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
            </div>

            <div class="border-t border-white/10 pt-4 mt-4">
                <h3 class="text-md font-semibold mb-2">Delete Account</h3>
                <p class="text-sm opacity-75 mb-4">Once you delete your account, there is no going back. Please be certain.</p>

                <button type="button" class="text-red-400 hover:text-white border border-red-400 hover:bg-red-500/50 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-300">
                    Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include_once '../includes/footer.php';
?>
