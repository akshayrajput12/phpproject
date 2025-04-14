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

// Get user's crops
$stmt = $conn->prepare("SELECT * FROM crops WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$crops = [];
while ($row = $result->fetch_assoc()) {
    $crops[] = $row;
}
$stmt->close();

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
        <div class="flex justify-between items-center mb-6 fade-in">
            <h1 class="text-2xl font-bold">My Crops</h1>

            <button type="button" id="add-crop-btn" class="bg-accent hover:bg-purple-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale flex items-center">
                <i class="fas fa-plus mr-2"></i> Add Crop
            </button>
        </div>

        <!-- Add Crop Modal -->
        <div id="add-crop-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden fade-in">
            <div class="glass p-6 rounded-xl w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Add New Crop</h2>
                    <button type="button" class="close-modal text-white/70 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="add-crop-form">
                    <div class="mb-4">
                        <label for="crop_name" class="block text-sm font-medium mb-2">Crop Name</label>
                        <input type="text" id="crop_name" name="crop_name" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Enter crop name" required>
                    </div>

                    <div class="mb-4">
                        <label for="planting_date" class="block text-sm font-medium mb-2">Planting Date</label>
                        <input type="date" id="planting_date" name="planting_date" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium mb-2">Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Enter any notes about this crop"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="close-modal mr-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="bg-gradient-to-r from-primary to-accent hover:opacity-90 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale">
                            Add Crop
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Crop Modal -->
        <div id="edit-crop-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden fade-in">
            <div class="glass p-6 rounded-xl w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Edit Crop</h2>
                    <button type="button" class="close-modal text-white/70 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="edit-crop-form">
                    <input type="hidden" id="edit_crop_id" name="crop_id">

                    <div class="mb-4">
                        <label for="edit_crop_name" class="block text-sm font-medium mb-2">Crop Name</label>
                        <input type="text" id="edit_crop_name" name="crop_name" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Enter crop name" required>
                    </div>

                    <div class="mb-4">
                        <label for="edit_planting_date" class="block text-sm font-medium mb-2">Planting Date</label>
                        <input type="date" id="edit_planting_date" name="planting_date" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" required>
                    </div>

                    <div class="mb-6">
                        <label for="edit_notes" class="block text-sm font-medium mb-2">Notes</label>
                        <textarea id="edit_notes" name="notes" rows="3" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Enter any notes about this crop"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="close-modal mr-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="bg-gradient-to-r from-primary to-accent hover:opacity-90 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Crops List -->
        <div id="crops-container">
            <?php if (empty($crops)): ?>
                <div class="glass p-8 rounded-lg text-center fade-in">
                    <i class="fas fa-seedling text-4xl mb-4 text-gray-400"></i>
                    <h2 class="text-xl font-semibold mb-2">No Crops Added Yet</h2>
                    <p class="opacity-75 mb-4">Start adding crops to get personalized recommendations and pest warnings.</p>
                    <button type="button" id="empty-add-crop-btn" class="bg-accent hover:bg-purple-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale">
                        <i class="fas fa-plus mr-2"></i> Add Your First Crop
                    </button>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 fade-in">
                    <?php foreach ($crops as $crop): ?>
                        <div class="glass p-5 rounded-lg hover-scale crop-card" data-id="<?php echo $crop['id']; ?>">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center">
                                    <i class="fas fa-leaf text-accent text-xl mr-3"></i>
                                    <h3 class="font-semibold"><?php echo htmlspecialchars($crop['crop_name']); ?></h3>
                                </div>
                                <div class="flex space-x-2">
                                    <button type="button" class="edit-crop-btn text-white/70 hover:text-white" data-id="<?php echo $crop['id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="delete-crop-btn text-white/70 hover:text-red-400" data-id="<?php echo $crop['id']; ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-3 text-sm">
                                <div class="flex items-center opacity-75 mb-1">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    <span>Planted: <?php echo date('M j, Y', strtotime($crop['planting_date'])); ?></span>
                                </div>

                                <?php if (!empty($crop['notes'])): ?>
                                    <div class="mt-2 p-2 bg-white/5 rounded-lg">
                                        <?php
                                        // Check if notes contain newlines (detailed format)
                                        if (strpos($crop['notes'], "\n") !== false) {
                                            // Split by double newlines to get sections
                                            $sections = explode("\n\n", $crop['notes']);
                                            foreach ($sections as $section) {
                                                if (!empty(trim($section))) {
                                                    // Split section into label and content
                                                    $parts = explode(":", $section, 2);
                                                    if (count($parts) == 2) {
                                                        echo '<div class="mb-2">';
                                                        echo '<span class="text-xs font-medium text-accent">' . htmlspecialchars($parts[0]) . ':</span>';
                                                        echo '<p class="text-xs opacity-90">' . htmlspecialchars($parts[1]) . '</p>';
                                                        echo '</div>';
                                                    } else {
                                                        echo '<p class="text-xs opacity-90 mb-1">' . htmlspecialchars($section) . '</p>';
                                                    }
                                                }
                                            }
                                        } else {
                                            // Display as regular text
                                            echo '<p class="text-xs opacity-90">' . htmlspecialchars($crop['notes']) . '</p>';
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-3 pt-3 border-t border-white/10 flex justify-between items-center">
                                    <span class="text-xs opacity-75">Added <?php echo date('M j, Y', strtotime($crop['created_at'])); ?></span>
                                    <button type="button" class="get-pest-warnings-btn text-xs bg-white/10 hover:bg-white/20 rounded-full px-3 py-1" data-crop="<?php echo htmlspecialchars($crop['crop_name']); ?>">
                                        <i class="fas fa-bug mr-1"></i> Pest Warnings
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pest Warnings Modal -->
        <div id="pest-warnings-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden fade-in">
            <div class="glass p-6 rounded-xl w-full max-w-2xl max-h-[80vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Pest & Disease Warnings</h2>
                    <button type="button" class="close-modal text-white/70 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div id="pest-warnings-content" class="space-y-4">
                    <div class="flex items-center justify-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl mr-3"></i>
                        <span>Loading pest warnings...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal functionality
        const addCropModal = document.getElementById('add-crop-modal');
        const editCropModal = document.getElementById('edit-crop-modal');
        const pestWarningsModal = document.getElementById('pest-warnings-modal');
        const addCropBtn = document.getElementById('add-crop-btn');
        const emptyAddCropBtn = document.getElementById('empty-add-crop-btn');

        // Open add crop modal
        if (addCropBtn) {
            addCropBtn.addEventListener('click', function() {
                addCropModal.classList.remove('hidden');
            });
        }

        // Open add crop modal from empty state
        if (emptyAddCropBtn) {
            emptyAddCropBtn.addEventListener('click', function() {
                addCropModal.classList.remove('hidden');
            });
        }

        // Close modals
        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', function() {
                addCropModal.classList.add('hidden');
                editCropModal.classList.add('hidden');
                pestWarningsModal.classList.add('hidden');
            });
        });

        // Add crop form submission
        const addCropForm = document.getElementById('add-crop-form');
        if (addCropForm) {
            addCropForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const cropName = document.getElementById('crop_name').value;
                const plantingDate = document.getElementById('planting_date').value;
                const notes = document.getElementById('notes').value;

                fetch('../api/crops.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'add_crop',
                        crop_name: cropName,
                        planting_date: plantingDate,
                        notes: notes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Close modal
                        addCropModal.classList.add('hidden');

                        // Show success message
                        showAlert('Crop added successfully!', 'success');

                        // Reload page to show new crop
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showAlert('Failed to add crop: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    showAlert('Error: ' + error.message, 'error');
                });
            });
        }

        // Edit crop buttons
        document.querySelectorAll('.edit-crop-btn').forEach(button => {
            button.addEventListener('click', function() {
                const cropId = this.getAttribute('data-id');
                const cropCard = document.querySelector(`.crop-card[data-id="${cropId}"]`);

                if (cropCard) {
                    const cropName = cropCard.querySelector('h3').textContent;
                    const plantingDateText = cropCard.querySelector('.fas.fa-calendar-alt').nextElementSibling.textContent;
                    const plantingDate = new Date(plantingDateText.replace('Planted: ', '')).toISOString().split('T')[0];
                    // Get all notes content
                    const notesContainer = cropCard.querySelector('.bg-white\\/5');
                    let notes = '';

                    if (notesContainer) {
                        // Check if it's the detailed format with multiple sections
                        const sections = notesContainer.querySelectorAll('div.mb-2');

                        if (sections.length > 0) {
                            // It's the detailed format, reconstruct the original notes
                            const notesArray = [];
                            sections.forEach(section => {
                                const label = section.querySelector('span.text-accent').textContent;
                                const content = section.querySelector('p').textContent;
                                notesArray.push(label + content);
                            });
                            notes = notesArray.join('\n\n');
                        } else {
                            // It's the simple format
                            const simpleNote = notesContainer.querySelector('p');
                            notes = simpleNote ? simpleNote.textContent : '';
                        }
                    }

                    // Fill edit form
                    document.getElementById('edit_crop_id').value = cropId;
                    document.getElementById('edit_crop_name').value = cropName;
                    document.getElementById('edit_planting_date').value = plantingDate;
                    document.getElementById('edit_notes').value = notes;

                    // Show edit modal
                    editCropModal.classList.remove('hidden');
                }
            });
        });

        // Edit crop form submission
        const editCropForm = document.getElementById('edit-crop-form');
        if (editCropForm) {
            editCropForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const cropId = document.getElementById('edit_crop_id').value;
                const cropName = document.getElementById('edit_crop_name').value;
                const plantingDate = document.getElementById('edit_planting_date').value;
                const notes = document.getElementById('edit_notes').value;

                fetch('../api/crops.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'update_crop',
                        crop_id: cropId,
                        crop_name: cropName,
                        planting_date: plantingDate,
                        notes: notes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Close modal
                        editCropModal.classList.add('hidden');

                        // Show success message
                        showAlert('Crop updated successfully!', 'success');

                        // Reload page to show updated crop
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showAlert('Failed to update crop: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    showAlert('Error: ' + error.message, 'error');
                });
            });
        }

        // Delete crop buttons
        document.querySelectorAll('.delete-crop-btn').forEach(button => {
            button.addEventListener('click', function() {
                const cropId = this.getAttribute('data-id');
                const cropCard = document.querySelector(`.crop-card[data-id="${cropId}"]`);

                if (cropCard) {
                    const cropName = cropCard.querySelector('h3').textContent;

                    if (confirm(`Are you sure you want to delete "${cropName}"? This action cannot be undone.`)) {
                        fetch('../api/crops.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                action: 'delete_crop',
                                crop_id: cropId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                // Show success message
                                showAlert('Crop deleted successfully!', 'success');

                                // Remove crop card from UI
                                cropCard.remove();

                                // If no crops left, reload page to show empty state
                                if (document.querySelectorAll('.crop-card').length === 0) {
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                }
                            } else {
                                showAlert('Failed to delete crop: ' + data.message, 'error');
                            }
                        })
                        .catch(error => {
                            showAlert('Error: ' + error.message, 'error');
                        });
                    }
                }
            });
        });

        // Get pest warnings buttons
        document.querySelectorAll('.get-pest-warnings-btn').forEach(button => {
            button.addEventListener('click', function() {
                const cropName = this.getAttribute('data-crop');
                const pestWarningsContent = document.getElementById('pest-warnings-content');

                // Show modal with loading state
                pestWarningsModal.classList.remove('hidden');
                pestWarningsContent.innerHTML = `
                    <div class="flex items-center justify-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl mr-3"></i>
                        <span>Loading pest warnings for ${cropName}...</span>
                    </div>
                `;

                // Get user's location and soil type
                const location = "<?php echo $user['location'] ?? ''; ?>";
                const soilType = "<?php echo $user['soil_type'] ?? ''; ?>";

                if (!location || !soilType) {
                    pestWarningsContent.innerHTML = `
                        <div class="text-center py-6">
                            <i class="fas fa-exclamation-circle text-4xl mb-2 text-yellow-400"></i>
                            <h3 class="text-lg font-medium mb-2">Missing Information</h3>
                            <p class="opacity-75 mb-4">Please update your profile with your location and soil type to get pest warnings.</p>
                            <a href="profile.php" class="inline-block bg-accent hover:bg-purple-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale">
                                Update Profile
                            </a>
                        </div>
                    `;
                    return;
                }

                // Fetch weather data first
                fetch(`../api/weather.php?action=current&location=${encodeURIComponent(location)}`)
                    .then(response => response.json())
                    .then(weatherResponse => {
                        if (weatherResponse.status === 'success') {
                            const weatherData = weatherResponse.data;

                            // Now fetch pest warnings with weather data
                            return fetch(`../api/gemini.php?action=pest_warnings&weather=${encodeURIComponent(JSON.stringify(weatherData))}&crop_name=${encodeURIComponent(cropName)}&soil_type=${encodeURIComponent(soilType)}`);
                        } else {
                            throw new Error('Failed to fetch weather data: ' + (weatherResponse.message || 'Unknown error'));
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            pestWarningsContent.innerHTML = `
                                <h3 class="font-medium text-lg mb-3">Pest & Disease Warnings for ${cropName}</h3>
                                <div class="space-y-4">
                                    ${data.data.map(pest => `
                                        <div class="glass p-4 rounded-lg bg-white/5">
                                            <div class="flex items-start">
                                                <i class="fas fa-bug text-red-400 text-xl mt-1 mr-3"></i>
                                                <div>
                                                    <h4 class="font-medium">${pest.name}</h4>
                                                    <p class="text-sm opacity-90 mt-1">${pest.risk_factors}</p>

                                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                                        <div class="glass p-2 rounded-lg bg-white/5">
                                                            <h5 class="text-xs font-medium mb-1">Warning Signs:</h5>
                                                            <p class="text-xs opacity-90">${pest.warning_signs}</p>
                                                        </div>

                                                        <div class="glass p-2 rounded-lg bg-white/5">
                                                            <h5 class="text-xs font-medium mb-1">Prevention:</h5>
                                                            <p class="text-xs opacity-90">${pest.prevention}</p>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 glass p-2 rounded-lg bg-white/5">
                                                        <h5 class="text-xs font-medium mb-1">Treatment:</h5>
                                                        <p class="text-xs opacity-90">${pest.treatment}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            `;
                        } else {
                            pestWarningsContent.innerHTML = `
                                <div class="text-center py-6">
                                    <i class="fas fa-exclamation-circle text-4xl mb-2 text-red-400"></i>
                                    <h3 class="text-lg font-medium mb-2">Failed to Load Pest Warnings</h3>
                                    <p class="opacity-75">${data.message || 'Unknown error'}</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        pestWarningsContent.innerHTML = `
                            <div class="text-center py-6">
                                <i class="fas fa-exclamation-circle text-4xl mb-2 text-red-400"></i>
                                <h3 class="text-lg font-medium mb-2">Error</h3>
                                <p class="opacity-75">${error.message}</p>
                            </div>
                        `;
                    });
            });
        });
    });
</script>

<?php
// Include footer
include_once '../includes/footer.php';
?>
