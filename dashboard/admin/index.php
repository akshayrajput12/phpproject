<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../index.php");
    exit;
}

// Include database connection
require_once '../../config/database.php';

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get all users
$stmt = $conn->prepare("SELECT * FROM users ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
$stmt->close();

// Get total number of users
$total_users = count($users);

// Get total number of crops
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM crops");
$stmt->execute();
$total_crops = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Get recent registrations (last 7 days)
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
$stmt->execute();
$recent_registrations = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Include header
include_once '../../includes/header.php';
?>

<div class="flex flex-col md:flex-row">
    <!-- Sidebar -->
    <div class="w-full md:w-64 md:min-h-screen">
        <?php include_once 'sidebar.php'; ?>
    </div>
    
    <!-- Main Content -->
    <div class="flex-1 p-4">
        <h1 class="text-2xl font-bold mb-6 fade-in">Admin Dashboard</h1>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="glass p-6 rounded-lg fade-in">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-accent/20 flex items-center justify-center mr-4">
                        <i class="fas fa-users text-accent"></i>
                    </div>
                    <div>
                        <h2 class="text-sm opacity-75">Total Users</h2>
                        <p class="text-2xl font-bold"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="glass p-6 rounded-lg fade-in">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-accent/20 flex items-center justify-center mr-4">
                        <i class="fas fa-seedling text-accent"></i>
                    </div>
                    <div>
                        <h2 class="text-sm opacity-75">Total Crops</h2>
                        <p class="text-2xl font-bold"><?php echo $total_crops; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="glass p-6 rounded-lg fade-in">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-accent/20 flex items-center justify-center mr-4">
                        <i class="fas fa-user-plus text-accent"></i>
                    </div>
                    <div>
                        <h2 class="text-sm opacity-75">New Users (7 days)</h2>
                        <p class="text-2xl font-bold"><?php echo $recent_registrations; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Users Table -->
        <div class="glass p-6 rounded-lg mb-6 fade-in">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Registered Users</h2>
                <div class="relative">
                    <input type="text" id="user-search" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full pl-10 p-2.5" placeholder="Search users...">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-white/10">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Username</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Location</th>
                            <th scope="col" class="px-6 py-3">Soil Type</th>
                            <th scope="col" class="px-6 py-3">Admin</th>
                            <th scope="col" class="px-6 py-3">Registered</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body">
                        <?php foreach ($users as $user): ?>
                            <tr class="border-b border-white/10 hover:bg-white/5">
                                <td class="px-6 py-4"><?php echo $user['id']; ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($user['username']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($user['location'] ?? 'Not set'); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($user['soil_type'] ?? 'Not set'); ?></td>
                                <td class="px-6 py-4">
                                    <?php if ($user['is_admin']): ?>
                                        <span class="bg-green-500/20 text-green-400 text-xs font-medium px-2.5 py-0.5 rounded">Yes</span>
                                    <?php else: ?>
                                        <span class="bg-gray-500/20 text-gray-400 text-xs font-medium px-2.5 py-0.5 rounded">No</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4"><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                <td class="px-6 py-4">
                                    <button type="button" class="text-accent hover:text-purple-400 view-user-btn" data-id="<?php echo $user['id']; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="text-blue-400 hover:text-blue-300 ml-3 toggle-admin-btn" data-id="<?php echo $user['id']; ?>" data-is-admin="<?php echo $user['is_admin']; ?>">
                                        <?php if ($user['is_admin']): ?>
                                            <i class="fas fa-user-minus"></i>
                                        <?php else: ?>
                                            <i class="fas fa-user-shield"></i>
                                        <?php endif; ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- User Details Modal -->
        <div id="user-details-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden fade-in">
            <div class="glass p-6 rounded-xl w-full max-w-2xl">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">User Details</h2>
                    <button type="button" class="close-modal text-white/70 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div id="user-details-content" class="space-y-4">
                    <div class="flex items-center justify-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl mr-3"></i>
                        <span>Loading user details...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User search functionality
        const userSearch = document.getElementById('user-search');
        const usersTableBody = document.getElementById('users-table-body');
        const userRows = usersTableBody.querySelectorAll('tr');
        
        userSearch.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            
            userRows.forEach(row => {
                const username = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                const location = row.cells[3].textContent.toLowerCase();
                
                if (username.includes(searchTerm) || email.includes(searchTerm) || location.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // View user details
        const viewUserBtns = document.querySelectorAll('.view-user-btn');
        const userDetailsModal = document.getElementById('user-details-modal');
        const userDetailsContent = document.getElementById('user-details-content');
        const closeModalBtns = document.querySelectorAll('.close-modal');
        
        viewUserBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                
                // Show modal with loading state
                userDetailsModal.classList.remove('hidden');
                userDetailsContent.innerHTML = `
                    <div class="flex items-center justify-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl mr-3"></i>
                        <span>Loading user details...</span>
                    </div>
                `;
                
                // Fetch user details
                fetch(`get_user_details.php?user_id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const user = data.user;
                            const crops = data.crops;
                            
                            userDetailsContent.innerHTML = `
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="glass p-4 rounded-lg bg-white/5">
                                        <h3 class="font-medium mb-2">Account Information</h3>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-sm opacity-75">Username:</span>
                                                <span class="font-medium">${user.username}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-sm opacity-75">Email:</span>
                                                <span class="font-medium">${user.email}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-sm opacity-75">Location:</span>
                                                <span class="font-medium">${user.location || 'Not set'}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-sm opacity-75">Soil Type:</span>
                                                <span class="font-medium">${user.soil_type || 'Not set'}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-sm opacity-75">Admin Status:</span>
                                                <span class="font-medium">${user.is_admin ? 'Admin' : 'Regular User'}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-sm opacity-75">Registered:</span>
                                                <span class="font-medium">${new Date(user.created_at).toLocaleDateString()}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="glass p-4 rounded-lg bg-white/5">
                                        <h3 class="font-medium mb-2">User Crops</h3>
                                        ${crops.length > 0 ? `
                                            <div class="space-y-2">
                                                ${crops.map(crop => `
                                                    <div class="glass p-2 rounded-lg bg-white/5">
                                                        <div class="flex justify-between">
                                                            <span class="font-medium">${crop.crop_name}</span>
                                                            <span class="text-xs opacity-75">Planted: ${new Date(crop.planting_date).toLocaleDateString()}</span>
                                                        </div>
                                                        ${crop.notes ? `<p class="text-xs opacity-75 mt-1">${crop.notes}</p>` : ''}
                                                    </div>
                                                `).join('')}
                                            </div>
                                        ` : `
                                            <p class="text-sm opacity-75">No crops added yet.</p>
                                        `}
                                    </div>
                                </div>
                            `;
                        } else {
                            userDetailsContent.innerHTML = `
                                <div class="text-center py-6">
                                    <i class="fas fa-exclamation-circle text-4xl mb-2 text-red-400"></i>
                                    <p>Failed to load user details.</p>
                                    <p class="text-sm opacity-75 mt-1">${data.message || 'Unknown error'}</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        userDetailsContent.innerHTML = `
                            <div class="text-center py-6">
                                <i class="fas fa-exclamation-circle text-4xl mb-2 text-red-400"></i>
                                <p>Failed to load user details.</p>
                                <p class="text-sm opacity-75 mt-1">${error.message}</p>
                            </div>
                        `;
                    });
            });
        });
        
        // Close modal
        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                userDetailsModal.classList.add('hidden');
            });
        });
        
        // Toggle admin status
        const toggleAdminBtns = document.querySelectorAll('.toggle-admin-btn');
        
        toggleAdminBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                const isAdmin = this.getAttribute('data-is-admin') === '1';
                
                if (confirm(`Are you sure you want to ${isAdmin ? 'remove' : 'grant'} admin privileges for this user?`)) {
                    fetch('toggle_admin.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            user_id: userId,
                            is_admin: !isAdmin
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showAlert('Admin status updated successfully!', 'success');
                            
                            // Reload page to reflect changes
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showAlert('Failed to update admin status: ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showAlert('Error: ' + error.message, 'error');
                    });
                }
            });
        });
    });
</script>

<?php
// Include footer
include_once '../../includes/footer.php';
?>
