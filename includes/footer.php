    </div><!-- End of Main Content Container -->

    <!-- Footer -->
    <footer class="glass mt-8 py-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <a href="#" class="flex items-center space-x-3">
                        <i class="fas fa-seedling text-accent text-2xl"></i>
                        <span class="self-center text-xl font-semibold whitespace-nowrap">Farmer's Friend</span>
                    </a>
                    <p class="mt-2 text-sm opacity-75">Empowering farmers with technology</p>
                </div>

                <div class="flex flex-wrap justify-center space-x-4">
                    <a href="#" class="text-gray-300 hover:text-accent">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-accent">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-accent">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-accent">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>

                <div class="mt-4 md:mt-0 text-sm opacity-75">
                    &copy; <?php echo date('Y'); ?> Farmer's Friend. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Toggle mobile menu
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.querySelector('[data-collapse-toggle="navbar-default"]');
            const menu = document.getElementById('navbar-default');

            if (menuButton && menu) {
                menuButton.addEventListener('click', function() {
                    menu.classList.toggle('hidden');
                });
            }

            // Add fade-in animation to elements with fade-in class
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach(element => {
                element.style.opacity = '0';
                setTimeout(() => {
                    element.style.opacity = '1';
                }, 100);
            });

            // Initialize hover scale effect
            const scaleElements = document.querySelectorAll('.hover-scale');
            scaleElements.forEach(element => {
                element.addEventListener('mouseenter', () => {
                    element.style.transform = 'scale(1.05)';
                });
                element.addEventListener('mouseleave', () => {
                    element.style.transform = 'scale(1)';
                });
            });
        });

        // Function to show sliding alerts
        function showAlert(message, type = 'info') {
            const alertContainer = document.createElement('div');
            alertContainer.className = `fixed top-20 right-4 glass slide-in p-4 rounded-lg max-w-xs z-50 ${
                type === 'success' ? 'bg-green-500/20 border-green-500/50' :
                type === 'error' ? 'bg-red-500/20 border-red-500/50' :
                type === 'warning' ? 'bg-yellow-500/20 border-yellow-500/50' :
                'bg-blue-500/20 border-blue-500/50'
            }`;

            alertContainer.innerHTML = `
                <div class="flex items-center">
                    <div class="mr-3">
                        <i class="fas ${
                            type === 'success' ? 'fa-check-circle' :
                            type === 'error' ? 'fa-times-circle' :
                            type === 'warning' ? 'fa-exclamation-triangle' :
                            'fa-info-circle'
                        }"></i>
                    </div>
                    <div>${message}</div>
                    <button class="ml-auto" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.body.appendChild(alertContainer);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertContainer.parentNode) {
                    alertContainer.classList.add('opacity-0');
                    alertContainer.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => alertContainer.remove(), 500);
                }
            }, 5000);
        }
    </script>

    <!-- Custom JavaScript -->
    <script src="<?php echo $base_url; ?>/assets/js/animations.js"></script>
    <script src="<?php echo $base_url; ?>/assets/js/translations.js"></script>
    <script src="<?php echo $base_url; ?>/assets/js/chatbot.js"></script>
</body>
</html>
