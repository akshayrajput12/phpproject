<?php
// Start session
session_start();

// Include database connection
require_once '../config/database.php';

$success = '';
$error = '';

// Process contact form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);
    
    // Validate input
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        // In a real application, you would send an email or store the message in a database
        // For this demo, we'll just show a success message
        $success = "Thank you for your message! We will get back to you soon.";
    }
}

// Include header
include_once '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-center fade-in">Contact Us</h1>
    
    <?php if ($success): ?>
        <div class="glass p-4 rounded-lg mb-6 bg-green-500/20 border border-green-500/50 max-w-2xl mx-auto fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                <span><?php echo $success; ?></span>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="glass p-4 rounded-lg mb-6 bg-red-500/20 border border-red-500/50 max-w-2xl mx-auto fade-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-400 mr-2"></i>
                <span><?php echo $error; ?></span>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Contact Form -->
        <div class="glass p-6 rounded-lg mb-8 w-full md:w-2/3 fade-in">
            <h2 class="text-xl font-semibold mb-6">Get in Touch</h2>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2">Your Name</label>
                        <input type="text" id="name" name="name" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Enter your name" required>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">Your Email</label>
                        <input type="email" id="email" name="email" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Enter your email" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium mb-2">Subject</label>
                    <input type="text" id="subject" name="subject" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Enter subject" required>
                </div>
                
                <div class="mb-6">
                    <label for="message" class="block text-sm font-medium mb-2">Message</label>
                    <textarea id="message" name="message" rows="6" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Enter your message" required></textarea>
                </div>
                
                <button type="submit" class="bg-gradient-to-r from-primary to-accent hover:opacity-90 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-300 hover-scale">
                    Send Message
                </button>
            </form>
        </div>
        
        <!-- Contact Information -->
        <div class="w-full md:w-1/3">
            <div class="glass p-6 rounded-lg mb-6 fade-in">
                <h2 class="text-xl font-semibold mb-4">Contact Information</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center mr-4">
                            <i class="fas fa-map-marker-alt text-accent"></i>
                        </div>
                        <div>
                            <h3 class="font-medium">Address</h3>
                            <p class="text-sm opacity-75 mt-1">123 Farming Lane, Cropville, CA 98765, United States</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center mr-4">
                            <i class="fas fa-phone-alt text-accent"></i>
                        </div>
                        <div>
                            <h3 class="font-medium">Phone</h3>
                            <p class="text-sm opacity-75 mt-1">+1 (555) 123-4567</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center mr-4">
                            <i class="fas fa-envelope text-accent"></i>
                        </div>
                        <div>
                            <h3 class="font-medium">Email</h3>
                            <p class="text-sm opacity-75 mt-1">info@farmersfriend.com</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center mr-4">
                            <i class="fas fa-clock text-accent"></i>
                        </div>
                        <div>
                            <h3 class="font-medium">Hours</h3>
                            <p class="text-sm opacity-75 mt-1">Monday - Friday: 9:00 AM - 5:00 PM<br>Saturday: 10:00 AM - 2:00 PM<br>Sunday: Closed</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="glass p-6 rounded-lg mb-6 fade-in">
                <h2 class="text-xl font-semibold mb-4">Connect With Us</h2>
                
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 hover:bg-accent/20 flex items-center justify-center transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 hover:bg-accent/20 flex items-center justify-center transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 hover:bg-accent/20 flex items-center justify-center transition-colors">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 hover:bg-accent/20 flex items-center justify-center transition-colors">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 hover:bg-accent/20 flex items-center justify-center transition-colors">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
            
            <div class="glass p-6 rounded-lg fade-in">
                <h2 class="text-xl font-semibold mb-4">FAQ</h2>
                
                <div class="space-y-4">
                    <div class="glass p-4 rounded-lg bg-white/5 hover-scale">
                        <h3 class="font-medium mb-2">Is Farmer's Friend free to use?</h3>
                        <p class="text-sm opacity-75">Yes, the basic features of Farmer's Friend are completely free. We also offer premium plans with advanced features for professional farmers.</p>
                    </div>
                    
                    <div class="glass p-4 rounded-lg bg-white/5 hover-scale">
                        <h3 class="font-medium mb-2">How accurate are the weather forecasts?</h3>
                        <p class="text-sm opacity-75">Our weather data comes from reliable meteorological sources and is updated regularly to ensure high accuracy for agricultural planning.</p>
                    </div>
                    
                    <div class="glass p-4 rounded-lg bg-white/5 hover-scale">
                        <h3 class="font-medium mb-2">Can I use Farmer's Friend on mobile?</h3>
                        <p class="text-sm opacity-75">Yes, our platform is fully responsive and works on all devices, including smartphones and tablets.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include_once '../includes/footer.php';
?>
