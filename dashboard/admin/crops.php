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

// Get all crops with user information
$stmt = $conn->prepare("
    SELECT c.*, u.username 
    FROM crops c
    JOIN users u ON c.user_id = u.id
    ORDER BY c.created_at DESC
");
$stmt->execute();
$result = $stmt->get_result();
$crops = [];
while ($row = $result->fetch_assoc()) {
    $crops[] = $row;
}
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
        <h1 class="text-2xl font-bold mb-6 fade-in">Crop Management</h1>
        
        <!-- Crops Table -->
        <div class="glass p-6 rounded-lg mb-6 fade-in">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">All Crops</h2>
                <div class="flex items-center">
                    <div class="relative mr-4">
                        <input type="text" id="crop-search" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full pl-10 p-2.5" placeholder="Search crops...">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-white/10">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Crop Name</th>
                            <th scope="col" class="px-6 py-3">User</th>
                            <th scope="col" class="px-6 py-3">Planting Date</th>
                            <th scope="col" class="px-6 py-3">Added On</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="crops-table-body">
                        <?php foreach ($crops as $crop): ?>
                            <tr class="border-b border-white/10 hover:bg-white/5">
                                <td class="px-6 py-4"><?php echo $crop['id']; ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($crop['crop_name']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($crop['username']); ?></td>
                                <td class="px-6 py-4"><?php echo date('M j, Y', strtotime($crop['planting_date'])); ?></td>
                                <td class="px-6 py-4"><?php echo date('M j, Y', strtotime($crop['created_at'])); ?></td>
                                <td class="px-6 py-4">
                                    <button type="button" class="text-accent hover:text-purple-400 view-crop-btn" data-id="<?php echo $crop['id']; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Crop Details Modal -->
        <div id="crop-details-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden fade-in">
            <div class="glass p-6 rounded-xl w-full max-w-2xl">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Crop Details</h2>
                    <button type="button" class="close-modal text-white/70 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div id="crop-details-content" class="space-y-4">
                    <div class="flex items-center justify-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl mr-3"></i>
                        <span>Loading crop details...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Crop search functionality
        const cropSearch = document.getElementById('crop-search');
        const cropsTableBody = document.getElementById('crops-table-body');
        const cropRows = cropsTableBody.querySelectorAll('tr');
        
        cropSearch.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            
            cropRows.forEach(row => {
                const cropName = row.cells[1].textContent.toLowerCase();
                const username = row.cells[2].textContent.toLowerCase();
                
                if (cropName.includes(searchTerm) || username.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // View crop details
        const viewCropBtns = document.querySelectorAll('.view-crop-btn');
        const cropDetailsModal = document.getElementById('crop-details-modal');
        const cropDetailsContent = document.getElementById('crop-details-content');
        const closeModalBtns = document.querySelectorAll('.close-modal');
        
        viewCropBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const cropId = this.getAttribute('data-id');
                const cropRow = this.closest('tr');
                const cropName = cropRow.cells[1].textContent;
                const username = cropRow.cells[2].textContent;
                const plantingDate = cropRow.cells[3].textContent;
                const addedOn = cropRow.cells[4].textContent;
                
                // Show modal with crop details
                cropDetailsModal.classList.remove('hidden');
                
                // Get crop notes from the server
                fetch(`get_crop_details.php?crop_id=${cropId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const crop = data.crop;
                            
                            cropDetailsContent.innerHTML = `
                                <div class="glass p-4 rounded-lg bg-white/5">
                                    <div class="flex items-start">
                                        <i class="fas fa-seedling text-accent text-xl mt-1 mr-3"></i>
                                        <div>
                                            <h3 class="font-medium text-lg">${cropName}</h3>
                                            <div class="flex items-center opacity-75 mt-1">
                                                <i class="fas fa-user mr-2"></i>
                                                <span>Added by: ${username}</span>
                                            </div>
                                            <div class="flex items-center opacity-75 mt-1">
                                                <i class="fas fa-calendar-alt mr-2"></i>
                                                <span>Planted: ${plantingDate}</span>
                                            </div>
                                            <div class="flex items-center opacity-75 mt-1">
                                                <i class="fas fa-clock mr-2"></i>
                                                <span>Added on: ${addedOn}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    ${crop.notes ? `
                                        <div class="mt-4 p-3 bg-white/5 rounded-lg">
                                            <h4 class="font-medium mb-2">Notes:</h4>
                                            <div class="text-sm whitespace-pre-line">${crop.notes}</div>
                                        </div>
                                    ` : ''}
                                </div>
                            `;
                        } else {
                            cropDetailsContent.innerHTML = `
                                <div class="text-center py-6">
                                    <i class="fas fa-exclamation-circle text-4xl mb-2 text-red-400"></i>
                                    <p>Failed to load crop details.</p>
                                    <p class="text-sm opacity-75 mt-1">${data.message || 'Unknown error'}</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        cropDetailsContent.innerHTML = `
                            <div class="text-center py-6">
                                <i class="fas fa-exclamation-circle text-4xl mb-2 text-red-400"></i>
                                <p>Failed to load crop details.</p>
                                <p class="text-sm opacity-75 mt-1">${error.message}</p>
                            </div>
                        `;
                    });
            });
        });
        
        // Close modal
        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                cropDetailsModal.classList.add('hidden');
            });
        });
    });
</script>

<?php
// Include footer
include_once '../../includes/footer.php';
?>
