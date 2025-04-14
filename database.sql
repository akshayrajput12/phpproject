-- Create the database
CREATE DATABASE IF NOT EXISTS db_friend CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_friend;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE COMMENT 'Unique username for login',
    email VARCHAR(100) NOT NULL UNIQUE COMMENT 'User email address for notifications',
    password VARCHAR(255) NOT NULL COMMENT 'Hashed password',
    location VARCHAR(100) COMMENT 'User location for weather data',
    soil_type ENUM('Clay', 'Sandy', 'Silt', 'Loam', 'Peat', 'Chalk') COMMENT 'Soil type for crop recommendations',
    profile_image VARCHAR(255) COMMENT 'Profile image path',
    phone VARCHAR(20) COMMENT 'Contact phone number',
    bio TEXT COMMENT 'User biography',
    preferences JSON COMMENT 'User preferences as JSON',
    last_login DATETIME COMMENT 'Last login timestamp',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Account status',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Account creation date',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update date'
) COMMENT 'Stores user account information';

-- Create crops table
CREATE TABLE IF NOT EXISTS crops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'Reference to user who owns this crop',
    crop_name VARCHAR(100) NOT NULL COMMENT 'Name of the crop',
    crop_variety VARCHAR(100) COMMENT 'Specific variety of the crop',
    planting_date DATE COMMENT 'Date when crop was planted',
    expected_harvest_date DATE COMMENT 'Expected harvest date',
    area_planted DECIMAL(10,2) COMMENT 'Area planted in square meters',
    soil_ph DECIMAL(3,1) COMMENT 'Soil pH level',
    irrigation_type VARCHAR(50) COMMENT 'Type of irrigation used',
    fertilizer_used VARCHAR(100) COMMENT 'Type of fertilizer used',
    notes TEXT COMMENT 'Additional notes about the crop',
    status ENUM('Planning', 'Planted', 'Growing', 'Harvested', 'Failed') DEFAULT 'Planning' COMMENT 'Current status of the crop',
    yield_amount DECIMAL(10,2) COMMENT 'Actual yield amount',
    yield_unit VARCHAR(20) COMMENT 'Unit of measurement for yield',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation date',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update date',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) COMMENT 'Stores information about user crops';

-- Create weather_alerts table
CREATE TABLE IF NOT EXISTS weather_alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'Reference to user who receives this alert',
    alert_type ENUM('extreme_heat', 'frost', 'rain', 'wind', 'humidity', 'drought', 'storm') NOT NULL COMMENT 'Type of weather alert',
    severity ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium' COMMENT 'Severity level of the alert',
    message TEXT NOT NULL COMMENT 'Alert message content',
    start_date DATETIME COMMENT 'Start date of the alert period',
    end_date DATETIME COMMENT 'End date of the alert period',
    location VARCHAR(100) COMMENT 'Specific location for this alert',
    is_read BOOLEAN DEFAULT FALSE COMMENT 'Whether user has read this alert',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Alert creation date',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update date',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) COMMENT 'Stores weather alerts for users';

-- Create pest_warnings table
CREATE TABLE IF NOT EXISTS pest_warnings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    crop_name VARCHAR(100) NOT NULL COMMENT 'Name of the affected crop',
    pest_name VARCHAR(100) NOT NULL COMMENT 'Name of the pest or disease',
    pest_type ENUM('Insect', 'Fungus', 'Bacteria', 'Virus', 'Weed', 'Other') COMMENT 'Type of pest or disease',
    description TEXT NOT NULL COMMENT 'Description of the pest/disease',
    warning_signs TEXT COMMENT 'Early warning signs to look for',
    risk_factors TEXT COMMENT 'Conditions that increase risk',
    prevention_measures TEXT COMMENT 'Preventive measures',
    treatment_options TEXT COMMENT 'Treatment options if detected',
    image_url VARCHAR(255) COMMENT 'Image of the pest/disease',
    severity ENUM('low', 'medium', 'high') DEFAULT 'medium' COMMENT 'Severity level',
    season VARCHAR(50) COMMENT 'Season when risk is highest',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation date',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update date'
) COMMENT 'Stores information about pest and disease warnings';

-- Create crop_recommendations table
CREATE TABLE IF NOT EXISTS crop_recommendations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    crop_name VARCHAR(100) NOT NULL COMMENT 'Name of the recommended crop',
    soil_type ENUM('Clay', 'Sandy', 'Silt', 'Loam', 'Peat', 'Chalk') COMMENT 'Suitable soil type',
    climate_zone VARCHAR(100) COMMENT 'Suitable climate zone',
    min_temperature DECIMAL(5,2) COMMENT 'Minimum temperature in Celsius',
    max_temperature DECIMAL(5,2) COMMENT 'Maximum temperature in Celsius',
    water_needs ENUM('Low', 'Medium', 'High') COMMENT 'Water requirements',
    growing_season VARCHAR(50) COMMENT 'Optimal growing season',
    growing_period INT COMMENT 'Growing period in days',
    sunlight_needs ENUM('Full Sun', 'Partial Shade', 'Full Shade') COMMENT 'Sunlight requirements',
    companion_plants TEXT COMMENT 'Good companion plants',
    avoid_plants TEXT COMMENT 'Plants to avoid planting nearby',
    planting_instructions TEXT COMMENT 'Planting instructions',
    care_instructions TEXT COMMENT 'Care instructions',
    harvesting_instructions TEXT COMMENT 'Harvesting instructions',
    nutritional_value TEXT COMMENT 'Nutritional information',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation date',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update date'
) COMMENT 'Stores crop recommendation information';

-- Create soil_data table
CREATE TABLE IF NOT EXISTS soil_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'Reference to user',
    location VARCHAR(100) COMMENT 'Specific location for this soil data',
    soil_type ENUM('Clay', 'Sandy', 'Silt', 'Loam', 'Peat', 'Chalk') COMMENT 'Soil type',
    ph_level DECIMAL(3,1) COMMENT 'Soil pH level',
    nitrogen_level DECIMAL(5,2) COMMENT 'Nitrogen level',
    phosphorus_level DECIMAL(5,2) COMMENT 'Phosphorus level',
    potassium_level DECIMAL(5,2) COMMENT 'Potassium level',
    organic_matter_percent DECIMAL(5,2) COMMENT 'Percentage of organic matter',
    moisture_content DECIMAL(5,2) COMMENT 'Soil moisture content',
    test_date DATE COMMENT 'Date when soil was tested',
    notes TEXT COMMENT 'Additional notes about soil',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation date',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update date',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) COMMENT 'Stores soil testing data';

-- Create weather_data table
CREATE TABLE IF NOT EXISTS weather_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(100) NOT NULL COMMENT 'Location for this weather data',
    date DATE NOT NULL COMMENT 'Date of weather record',
    temperature_min DECIMAL(5,2) COMMENT 'Minimum temperature in Celsius',
    temperature_max DECIMAL(5,2) COMMENT 'Maximum temperature in Celsius',
    humidity DECIMAL(5,2) COMMENT 'Humidity percentage',
    precipitation DECIMAL(5,2) COMMENT 'Precipitation in mm',
    wind_speed DECIMAL(5,2) COMMENT 'Wind speed in m/s',
    wind_direction VARCHAR(10) COMMENT 'Wind direction',
    weather_condition VARCHAR(50) COMMENT 'General weather condition',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation date'
) COMMENT 'Stores historical weather data';

-- Insert some sample soil types for recommendations
INSERT INTO crop_recommendations (crop_name, soil_type, climate_zone, water_needs, growing_season, sunlight_needs, planting_instructions, care_instructions)
VALUES
('Tomato', 'Loam', 'Temperate', 'Medium', 'Spring-Summer', 'Full Sun', 'Plant seeds 1/4 inch deep, 24-36 inches apart.', 'Water regularly and provide support as plants grow.'),
('Carrot', 'Sandy', 'Temperate', 'Medium', 'Spring-Fall', 'Full Sun', 'Sow seeds 1/4 inch deep, 2-3 inches apart.', 'Keep soil moist but not waterlogged. Thin seedlings as needed.'),
('Rice', 'Clay', 'Tropical', 'High', 'Rainy Season', 'Full Sun', 'Plant in flooded fields, 6 inches apart.', 'Maintain water level and monitor for pests.'),
('Wheat', 'Loam', 'Temperate', 'Medium', 'Fall-Spring', 'Full Sun', 'Sow seeds 1-2 inches deep in rows.', 'Ensure adequate moisture during growing period.'),
('Potato', 'Loam', 'Temperate', 'Medium', 'Spring', 'Full Sun', 'Plant seed potatoes 4-6 inches deep, 12 inches apart.', 'Hill soil around plants as they grow. Water consistently.'),
('Corn', 'Loam', 'Temperate', 'Medium', 'Spring-Summer', 'Full Sun', 'Plant seeds 1-2 inches deep, 12 inches apart in rows.', 'Water regularly, especially during tasseling and ear formation.'),
('Lettuce', 'Loam', 'Temperate', 'Medium', 'Spring-Fall', 'Partial Shade', 'Sow seeds 1/8 inch deep, 8-12 inches apart.', 'Keep soil consistently moist. Harvest outer leaves as needed.'),
('Onion', 'Loam', 'Temperate', 'Medium', 'Spring', 'Full Sun', 'Plant sets 1 inch deep, 4-6 inches apart.', 'Water regularly and weed carefully to avoid disturbing shallow roots.'),
('Cucumber', 'Loam', 'Temperate', 'High', 'Summer', 'Full Sun', 'Plant seeds 1 inch deep, 36-60 inches apart.', 'Provide trellis for climbing varieties. Water consistently.'),
('Spinach', 'Loam', 'Temperate', 'Medium', 'Spring-Fall', 'Partial Shade', 'Sow seeds 1/2 inch deep, 2-4 inches apart.', 'Keep soil moist and cool. Harvest outer leaves as needed.');

-- Insert some sample pest warnings
INSERT INTO pest_warnings (crop_name, pest_name, pest_type, description, warning_signs, prevention_measures, treatment_options, severity)
VALUES
('Tomato', 'Early Blight', 'Fungus', 'Fungal disease causing leaf spots and fruit rot', 'Dark brown spots on lower leaves with concentric rings', 'Crop rotation, adequate spacing for airflow, avoid overhead watering', 'Remove infected leaves, apply fungicide if severe', 'medium'),
('Potato', 'Colorado Potato Beetle', 'Insect', 'Striped beetle that defoliates potato plants', 'Yellow-orange eggs on leaf undersides, striped beetles on plants', 'Crop rotation, row covers, plant resistant varieties', 'Handpick beetles, apply organic insecticides if severe', 'high'),
('Corn', 'Corn Earworm', 'Insect', 'Caterpillar that feeds on corn ears', 'Small holes in husks, damaged kernels at ear tip', 'Plant early, use resistant varieties, beneficial insects', 'Apply Bt (Bacillus thuringiensis) to silk, mineral oil to silk', 'medium'),
('Cucumber', 'Powdery Mildew', 'Fungus', 'White powdery growth on leaves affecting yield', 'White powdery spots on leaves that spread quickly', 'Adequate spacing, resistant varieties, avoid overhead watering', 'Neem oil, potassium bicarbonate, sulfur sprays', 'medium'),
('Lettuce', 'Aphids', 'Insect', 'Small sap-sucking insects that weaken plants', 'Curled leaves, sticky residue, presence of small insects', 'Aluminum foil mulch, companion planting, row covers', 'Strong water spray, insecticidal soap, neem oil', 'low');
