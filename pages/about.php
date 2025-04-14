<?php
// Include header
include_once '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-center fade-in">About Farmer's Friend</h1>
    
    <!-- Hero Section -->
    <div class="glass p-8 rounded-lg mb-8 fade-in">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-6 md:mb-0 md:pr-8">
                <h2 class="text-2xl font-semibold mb-4">Empowering Farmers with Technology</h2>
                <p class="opacity-90 mb-4">Farmer's Friend is a comprehensive platform designed to assist farmers with data-driven insights, personalized recommendations, and timely alerts to optimize crop yields and minimize risks.</p>
                <p class="opacity-90">Our mission is to bridge the gap between traditional farming knowledge and modern technology, making advanced agricultural insights accessible to farmers of all scales.</p>
            </div>
            <div class="md:w-1/2">
                <img src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Farmer in field" class="rounded-lg w-full h-64 object-cover hover-scale">
            </div>
        </div>
    </div>
    
    <!-- Features Section -->
    <div class="glass p-8 rounded-lg mb-8 fade-in">
        <h2 class="text-2xl font-semibold mb-6 text-center">Our Features</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass p-5 rounded-lg bg-white/5 hover-scale">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-accent/20 flex items-center justify-center mb-4">
                        <i class="fas fa-cloud-sun text-accent text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-medium mb-2">Weather Insights</h3>
                    <p class="opacity-75">Real-time weather updates and forecasts tailored for agricultural needs, helping you plan your farming activities effectively.</p>
                </div>
            </div>
            
            <div class="glass p-5 rounded-lg bg-white/5 hover-scale">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-accent/20 flex items-center justify-center mb-4">
                        <i class="fas fa-seedling text-accent text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-medium mb-2">Crop Recommendations</h3>
                    <p class="opacity-75">AI-powered crop suggestions based on your location, soil type, and current weather conditions to maximize yield potential.</p>
                </div>
            </div>
            
            <div class="glass p-5 rounded-lg bg-white/5 hover-scale">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-accent/20 flex items-center justify-center mb-4">
                        <i class="fas fa-bug text-accent text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-medium mb-2">Pest & Disease Alerts</h3>
                    <p class="opacity-75">Timely warnings about potential pest and disease risks based on weather patterns and crop vulnerabilities.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- How It Works Section -->
    <div class="glass p-8 rounded-lg mb-8 fade-in">
        <h2 class="text-2xl font-semibold mb-6 text-center">How It Works</h2>
        
        <div class="space-y-6">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/4 flex justify-center mb-4 md:mb-0">
                    <div class="w-20 h-20 rounded-full bg-primary/20 flex items-center justify-center text-2xl font-bold">1</div>
                </div>
                <div class="md:w-3/4">
                    <h3 class="text-xl font-medium mb-2">Create Your Profile</h3>
                    <p class="opacity-75">Sign up and set your location and soil type to get personalized recommendations tailored to your specific farming conditions.</p>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/4 flex justify-center mb-4 md:mb-0">
                    <div class="w-20 h-20 rounded-full bg-primary/20 flex items-center justify-center text-2xl font-bold">2</div>
                </div>
                <div class="md:w-3/4">
                    <h3 class="text-xl font-medium mb-2">Get Weather Updates</h3>
                    <p class="opacity-75">Access real-time weather data and forecasts specific to your location, with agricultural insights and alerts for extreme conditions.</p>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/4 flex justify-center mb-4 md:mb-0">
                    <div class="w-20 h-20 rounded-full bg-primary/20 flex items-center justify-center text-2xl font-bold">3</div>
                </div>
                <div class="md:w-3/4">
                    <h3 class="text-xl font-medium mb-2">Receive Crop Recommendations</h3>
                    <p class="opacity-75">Our AI analyzes your conditions and suggests the most suitable crops, along with care instructions and growing information.</p>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/4 flex justify-center mb-4 md:mb-0">
                    <div class="w-20 h-20 rounded-full bg-primary/20 flex items-center justify-center text-2xl font-bold">4</div>
                </div>
                <div class="md:w-3/4">
                    <h3 class="text-xl font-medium mb-2">Monitor Pest & Disease Risks</h3>
                    <p class="opacity-75">Stay ahead of potential threats with our pest and disease warning system, which provides early detection and prevention advice.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Team Section -->
    <div class="glass p-8 rounded-lg mb-8 fade-in">
        <h2 class="text-2xl font-semibold mb-6 text-center">Our Team</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="glass p-5 rounded-lg bg-white/5 hover-scale">
                <div class="flex flex-col items-center text-center">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Team member" class="w-24 h-24 rounded-full object-cover mb-4">
                    <h3 class="text-xl font-medium">John Doe</h3>
                    <p class="text-accent">Agricultural Scientist</p>
                    <p class="opacity-75 mt-2">Expert in crop science with over 15 years of experience in sustainable farming practices.</p>
                    <div class="flex space-x-3 mt-3">
                        <a href="#" class="text-white/70 hover:text-accent"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white/70 hover:text-accent"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-white/70 hover:text-accent"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="glass p-5 rounded-lg bg-white/5 hover-scale">
                <div class="flex flex-col items-center text-center">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Team member" class="w-24 h-24 rounded-full object-cover mb-4">
                    <h3 class="text-xl font-medium">Jane Smith</h3>
                    <p class="text-accent">Meteorologist</p>
                    <p class="opacity-75 mt-2">Specialized in agricultural meteorology, helping farmers interpret weather data for optimal decision-making.</p>
                    <div class="flex space-x-3 mt-3">
                        <a href="#" class="text-white/70 hover:text-accent"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white/70 hover:text-accent"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-white/70 hover:text-accent"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="glass p-5 rounded-lg bg-white/5 hover-scale">
                <div class="flex flex-col items-center text-center">
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Team member" class="w-24 h-24 rounded-full object-cover mb-4">
                    <h3 class="text-xl font-medium">Michael Johnson</h3>
                    <p class="text-accent">AI Specialist</p>
                    <p class="opacity-75 mt-2">Develops machine learning models to provide accurate crop recommendations based on environmental factors.</p>
                    <div class="flex space-x-3 mt-3">
                        <a href="#" class="text-white/70 hover:text-accent"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white/70 hover:text-accent"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-white/70 hover:text-accent"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Testimonials Section -->
    <div class="glass p-8 rounded-lg mb-8 fade-in">
        <h2 class="text-2xl font-semibold mb-6 text-center">What Farmers Say</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="glass p-5 rounded-lg bg-white/5 hover-scale">
                <div class="flex flex-col">
                    <div class="flex items-center mb-4">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Testimonial" class="w-16 h-16 rounded-full object-cover mr-4">
                        <div>
                            <h3 class="font-medium">Robert Wilson</h3>
                            <p class="text-sm opacity-75">Wheat Farmer, Kansas</p>
                        </div>
                    </div>
                    <p class="opacity-90 italic">"Farmer's Friend has transformed how I plan my planting schedule. The weather alerts have saved my crops multiple times from unexpected frost and storms."</p>
                    <div class="flex text-accent mt-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            
            <div class="glass p-5 rounded-lg bg-white/5 hover-scale">
                <div class="flex flex-col">
                    <div class="flex items-center mb-4">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Testimonial" class="w-16 h-16 rounded-full object-cover mr-4">
                        <div>
                            <h3 class="font-medium">Maria Garcia</h3>
                            <p class="text-sm opacity-75">Vegetable Grower, California</p>
                        </div>
                    </div>
                    <p class="opacity-90 italic">"The crop recommendations are spot-on! I've diversified my farm with crops I wouldn't have considered before, and they're thriving in my soil conditions."</p>
                    <div class="flex text-accent mt-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Call to Action -->
    <div class="glass p-8 rounded-lg text-center fade-in">
        <h2 class="text-2xl font-semibold mb-4">Ready to Transform Your Farming?</h2>
        <p class="opacity-90 mb-6 max-w-2xl mx-auto">Join thousands of farmers who are using Farmer's Friend to make data-driven decisions and improve their crop yields.</p>
        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
            <a href="../auth/register.php" class="bg-gradient-to-r from-primary to-accent hover:opacity-90 text-white font-medium rounded-lg text-lg px-8 py-3 transition-all duration-300 hover-scale">
                Get Started
            </a>
            <a href="../pages/contact.php" class="bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg text-lg px-8 py-3 transition-all duration-300 hover-scale">
                Contact Us
            </a>
        </div>
    </div>
</div>

<?php
// Include footer
include_once '../includes/footer.php';
?>
