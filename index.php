<?php
// Start session
session_start();

// Include database connection
require_once 'config/database.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Include header
include_once 'includes/header.php';
?>

<!-- Hero Section -->
<div class="relative min-h-[80vh] flex items-center justify-center mb-12 overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1932&q=80" alt="Farm field at sunset" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-dark/80 to-dark/50"></div>
    </div>
    
    <!-- Hero Content -->
    <div class="container mx-auto px-4 relative z-10 text-center fade-in">
        <h1 class="text-4xl md:text-6xl font-bold mb-6">Crop Guidance and Farmer's Friend</h1>
        <p class="text-xl md:text-2xl opacity-90 mb-8 max-w-3xl mx-auto">Empowering farmers with intelligent crop recommendations, weather insights, and pest warnings.</p>
        
        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
            <?php if ($isLoggedIn): ?>
                <a href="dashboard/index.php" class="bg-gradient-to-r from-primary to-accent hover:opacity-90 text-white font-bold rounded-lg text-lg px-8 py-4 transition-all duration-300 hover-scale">
                    Go to Dashboard
                </a>
            <?php else: ?>
                <a href="auth/register.php" class="bg-gradient-to-r from-primary to-accent hover:opacity-90 text-white font-bold rounded-lg text-lg px-8 py-4 transition-all duration-300 hover-scale">
                    Get Started
                </a>
                <a href="auth/login.php" class="bg-white/10 hover:bg-white/20 text-white font-bold rounded-lg text-lg px-8 py-4 transition-all duration-300 hover-scale">
                    Login
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Animated Scroll Down Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10">
        <a href="#features" class="text-white/80 hover:text-white animate-bounce">
            <i class="fas fa-chevron-down text-2xl"></i>
        </a>
    </div>
</div>

<!-- Features Section -->
<div id="features" class="container mx-auto px-4 py-12">
    <div class="text-center mb-12 fade-in">
        <h2 class="text-3xl font-bold mb-4">Why Choose Farmer's Friend?</h2>
        <p class="text-xl opacity-75 max-w-3xl mx-auto">Our platform combines weather data, AI-powered recommendations, and agricultural expertise to help you make informed decisions.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 fade-in">
        <div class="glass p-6 rounded-xl hover-scale">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-accent/20 flex items-center justify-center mb-4">
                    <i class="fas fa-cloud-sun-rain text-accent text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Real-time Weather Updates</h3>
                <p class="opacity-75">Access accurate weather forecasts and receive alerts for extreme conditions that might affect your crops.</p>
            </div>
        </div>
        
        <div class="glass p-6 rounded-xl hover-scale">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-accent/20 flex items-center justify-center mb-4">
                    <i class="fas fa-seedling text-accent text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">AI-Powered Crop Recommendations</h3>
                <p class="opacity-75">Get personalized crop suggestions based on your location, soil type, and current weather conditions.</p>
            </div>
        </div>
        
        <div class="glass p-6 rounded-xl hover-scale">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-accent/20 flex items-center justify-center mb-4">
                    <i class="fas fa-bug text-accent text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Pest & Disease Warnings</h3>
                <p class="opacity-75">Stay ahead of potential threats with timely warnings and prevention strategies for common pests and diseases.</p>
            </div>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<div class="bg-gradient-to-r from-primary/20 to-accent/20 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 fade-in">
            <h2 class="text-3xl font-bold mb-4">How It Works</h2>
            <p class="text-xl opacity-75 max-w-3xl mx-auto">Our simple process helps you get the most out of your farming experience.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 fade-in">
            <div class="glass p-6 rounded-xl hover-scale">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-xl font-bold mb-4">1</div>
                    <h3 class="text-lg font-semibold mb-2">Create Account</h3>
                    <p class="opacity-75">Sign up and set your location and soil type to get personalized recommendations.</p>
                </div>
            </div>
            
            <div class="glass p-6 rounded-xl hover-scale">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-xl font-bold mb-4">2</div>
                    <h3 class="text-lg font-semibold mb-2">Check Weather</h3>
                    <p class="opacity-75">Access real-time weather data and forecasts specific to your location.</p>
                </div>
            </div>
            
            <div class="glass p-6 rounded-xl hover-scale">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-xl font-bold mb-4">3</div>
                    <h3 class="text-lg font-semibold mb-2">Get Recommendations</h3>
                    <p class="opacity-75">Receive AI-generated crop suggestions based on your specific conditions.</p>
                </div>
            </div>
            
            <div class="glass p-6 rounded-xl hover-scale">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-xl font-bold mb-4">4</div>
                    <h3 class="text-lg font-semibold mb-2">Monitor & Grow</h3>
                    <p class="opacity-75">Track your crops and receive timely alerts about potential issues.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Showcase -->
<div class="container mx-auto px-4 py-16">
    <!-- Weather Feature -->
    <div class="flex flex-col md:flex-row items-center mb-16 fade-in">
        <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
            <h2 class="text-2xl font-bold mb-4">Accurate Weather Forecasts</h2>
            <p class="opacity-90 mb-4">Access detailed weather information tailored specifically for agricultural needs. Our platform provides current conditions, 5-day forecasts, and critical agricultural metrics.</p>
            <p class="opacity-90 mb-6">Receive timely alerts for extreme weather conditions that might affect your crops, allowing you to take preventive measures.</p>
            <a href="pages/weather.php" class="inline-block bg-accent hover:bg-purple-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale">
                Explore Weather Features
            </a>
        </div>
        <div class="md:w-1/2">
            <div class="glass p-6 rounded-xl">
                <img src="https://images.unsplash.com/photo-1561484930-998b6a7b22e8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Weather forecast" class="rounded-lg w-full hover-scale">
            </div>
        </div>
    </div>
    
    <!-- Crop Recommendations Feature -->
    <div class="flex flex-col md:flex-row-reverse items-center mb-16 fade-in">
        <div class="md:w-1/2 mb-8 md:mb-0 md:pl-8">
            <h2 class="text-2xl font-bold mb-4">Smart Crop Recommendations</h2>
            <p class="opacity-90 mb-4">Our AI-powered system analyzes your location, soil type, and current weather conditions to suggest the most suitable crops for your farm.</p>
            <p class="opacity-90 mb-6">Each recommendation comes with detailed growing instructions, expected harvest times, and potential challenges to help you succeed.</p>
            <?php if ($isLoggedIn): ?>
                <a href="dashboard/index.php" class="inline-block bg-accent hover:bg-purple-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale">
                    View Your Recommendations
                </a>
            <?php else: ?>
                <a href="auth/register.php" class="inline-block bg-accent hover:bg-purple-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale">
                    Get Started
                </a>
            <?php endif; ?>
        </div>
        <div class="md:w-1/2">
            <div class="glass p-6 rounded-xl">
                <img src="https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Crop recommendations" class="rounded-lg w-full hover-scale">
            </div>
        </div>
    </div>
    
    <!-- Pest Warnings Feature -->
    <div class="flex flex-col md:flex-row items-center fade-in">
        <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
            <h2 class="text-2xl font-bold mb-4">Pest & Disease Alerts</h2>
            <p class="opacity-90 mb-4">Stay ahead of potential threats with our advanced pest and disease warning system. We analyze weather patterns and crop vulnerabilities to predict potential issues.</p>
            <p class="opacity-90 mb-6">Receive detailed information about early warning signs, prevention measures, and treatment options if problems are detected.</p>
            <?php if ($isLoggedIn): ?>
                <a href="dashboard/crops.php" class="inline-block bg-accent hover:bg-purple-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale">
                    Check Pest Warnings
                </a>
            <?php else: ?>
                <a href="auth/register.php" class="inline-block bg-accent hover:bg-purple-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors hover-scale">
                    Get Started
                </a>
            <?php endif; ?>
        </div>
        <div class="md:w-1/2">
            <div class="glass p-6 rounded-xl">
                <img src="https://images.unsplash.com/photo-1471193945509-9ad0617afabf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Pest warnings" class="rounded-lg w-full hover-scale">
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<div class="bg-gradient-to-r from-primary/20 to-accent/20 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 fade-in">
            <h2 class="text-3xl font-bold mb-4">What Farmers Say</h2>
            <p class="text-xl opacity-75 max-w-3xl mx-auto">Hear from farmers who have transformed their practices with Farmer's Friend.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 fade-in">
            <div class="glass p-6 rounded-xl hover-scale">
                <div class="flex flex-col">
                    <div class="flex items-center mb-4">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Testimonial" class="w-16 h-16 rounded-full object-cover mr-4">
                        <div>
                            <h3 class="font-medium">Robert Wilson</h3>
                            <p class="text-sm opacity-75">Wheat Farmer, Kansas</p>
                        </div>
                    </div>
                    <p class="opacity-90 italic">"The weather alerts have saved my crops multiple times from unexpected frost and storms. I can't imagine farming without this tool now."</p>
                    <div class="flex text-accent mt-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            
            <div class="glass p-6 rounded-xl hover-scale">
                <div class="flex flex-col">
                    <div class="flex items-center mb-4">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Testimonial" class="w-16 h-16 rounded-full object-cover mr-4">
                        <div>
                            <h3 class="font-medium">Maria Garcia</h3>
                            <p class="text-sm opacity-75">Vegetable Grower, California</p>
                        </div>
                    </div>
                    <p class="opacity-90 italic">"I've diversified my farm with crops I wouldn't have considered before, and they're thriving in my soil conditions. The recommendations are spot-on!"</p>
                    <div class="flex text-accent mt-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="glass p-6 rounded-xl hover-scale">
                <div class="flex flex-col">
                    <div class="flex items-center mb-4">
                        <img src="https://images.unsplash.com/photo-1552058544-f2b08422138a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=699&q=80" alt="Testimonial" class="w-16 h-16 rounded-full object-cover mr-4">
                        <div>
                            <h3 class="font-medium">James Thompson</h3>
                            <p class="text-sm opacity-75">Organic Farmer, Oregon</p>
                        </div>
                    </div>
                    <p class="opacity-90 italic">"The pest warnings have been incredibly accurate. I've been able to implement organic prevention methods before issues become serious problems."</p>
                    <div class="flex text-accent mt-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="container mx-auto px-4 py-16">
    <div class="glass p-8 rounded-xl text-center max-w-4xl mx-auto fade-in">
        <h2 class="text-3xl font-bold mb-4">Ready to Transform Your Farming?</h2>
        <p class="text-xl opacity-90 mb-8 max-w-2xl mx-auto">Join thousands of farmers who are using Farmer's Friend to make data-driven decisions and improve their crop yields.</p>
        
        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
            <?php if ($isLoggedIn): ?>
                <a href="dashboard/index.php" class="bg-gradient-to-r from-primary to-accent hover:opacity-90 text-white font-bold rounded-lg text-lg px-8 py-4 transition-all duration-300 hover-scale">
                    Go to Dashboard
                </a>
            <?php else: ?>
                <a href="auth/register.php" class="bg-gradient-to-r from-primary to-accent hover:opacity-90 text-white font-bold rounded-lg text-lg px-8 py-4 transition-all duration-300 hover-scale">
                    Get Started for Free
                </a>
                <a href="pages/about.php" class="bg-white/10 hover:bg-white/20 text-white font-bold rounded-lg text-lg px-8 py-4 transition-all duration-300 hover-scale">
                    Learn More
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript for Animations -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80, // Adjust for header height
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>

<?php
// Include footer
include_once 'includes/footer.php';
?>
