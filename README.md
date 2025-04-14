# Crop Guidance and Farmer's Friend

A PHP web application that assists farmers with crop suggestions, weather-based precautions, pest attack warnings, and weather alerts.

## Features

- **User Authentication**: Secure login and registration system
- **Weather Integration**: Real-time weather updates and forecasts based on user location
- **Crop Recommendations**: AI-powered crop suggestions based on weather and soil type
- **Pest Warnings**: Timely alerts about potential pest and disease risks
- **Modern UI**: Glassmorphism effects, animations, and responsive design

## Requirements

- XAMPP (Apache, MySQL, PHP)
- PHP 7.4 or higher
- Web browser with JavaScript enabled
- Internet connection (for API calls)

## Installation

1. **Clone or download** this repository to your XAMPP's htdocs folder:
   ```
   C:\xampp\htdocs\farmer
   ```

2. **Start XAMPP** and ensure Apache and MySQL services are running.

3. **Open your browser** and navigate to:
   ```
   http://localhost/farmer/
   ```
   The application will automatically create the database and tables on first run.

4. **API Keys**: 
   - Get a free API key from [WeatherAPI](https://www.weatherapi.com/) and update it in `api/weather.php`
   - Get a Gemini API key from [Google AI Studio](https://ai.google.dev/) and update it in `api/gemini.php`

## Usage

1. **Register** a new account
2. **Set your location and soil type** in your profile
3. View **weather updates** and **crop recommendations** on your dashboard
4. Add crops to your profile to get **pest warnings**
5. Explore detailed **weather forecasts** and **farming tips**

## Database Structure

The application uses a MySQL database with the following main tables:

- `users`: Stores user account information
- `crops`: Tracks user's crops
- `weather_alerts`: Stores weather alerts for users
- `pest_warnings`: Contains information about pest and disease warnings
- `crop_recommendations`: Stores crop recommendation information

## Technologies Used

- **Backend**: PHP
- **Frontend**: HTML, Tailwind CSS, JavaScript
- **Database**: MySQL
- **APIs**: WeatherAPI, Google Gemini AI
- **Effects**: Glassmorphism, animations

## Customization

- **Colors**: Edit the Tailwind configuration in `includes/header.php`
- **Images**: Replace with your own from [Unsplash](https://unsplash.com/)
- **API Keys**: Update in `api/weather.php` and `api/gemini.php`

## Troubleshooting

- If you encounter database connection issues, check your MySQL service in XAMPP
- For API errors, verify your API keys and internet connection
- Clear your browser cache if you experience display issues

## Credits

- Weather data provided by [WeatherAPI](https://www.weatherapi.com/)
- AI-powered recommendations by [Google Gemini](https://ai.google.dev/)
- Images from [Unsplash](https://unsplash.com/)
- Icons from [Font Awesome](https://fontawesome.com/)
