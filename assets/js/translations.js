/**
 * Farmer's Friend - Translations
 * Multilingual support for the application
 */

document.addEventListener('DOMContentLoaded', function() {
    // Create language selector if it doesn't exist
    if (!document.getElementById('language-selector')) {
        createLanguageSelector();
    }

    // Initialize translations
    initTranslations();
});

/**
 * Create language selector UI
 */
function createLanguageSelector() {
    // Create container
    const container = document.createElement('div');
    container.className = 'fixed top-20 right-4 z-40 glass p-2 rounded-lg';

    // Create language selector
    const selector = document.createElement('div');
    selector.className = 'flex items-center space-x-2';
    selector.innerHTML = `
        <span class="text-xs opacity-75" data-translate="language">Language:</span>
        <select id="language-selector" class="bg-white/10 border border-white/20 text-white text-xs rounded focus:ring-accent focus:border-accent p-1">
            <option value="en">English</option>
            <option value="hi">हिंदी</option>
            <option value="pa">ਪੰਜਾਬੀ</option>
        </select>
    `;

    container.appendChild(selector);
    document.body.appendChild(container);
}

/**
 * Initialize translations functionality
 */
function initTranslations() {
    const languageSelector = document.getElementById('language-selector');

    if (languageSelector) {
        // Check for saved language preference
        const savedLanguage = localStorage.getItem('preferred_language');
        if (savedLanguage) {
            languageSelector.value = savedLanguage;
            applyTranslations(savedLanguage);
        }

        // Listen for language changes
        languageSelector.addEventListener('change', function() {
            const selectedLanguage = this.value;
            localStorage.setItem('preferred_language', selectedLanguage);
            applyTranslations(selectedLanguage);
        });
    }
}

/**
 * Apply translations to the page
 */
function applyTranslations(language) {
    // Get all elements with data-translate attribute
    const elements = document.querySelectorAll('[data-translate]');

    elements.forEach(element => {
        const key = element.getAttribute('data-translate');
        const translation = getTranslation(key, language);

        if (translation) {
            element.textContent = translation;
        }
    });

    // Get all elements with data-translate-placeholder attribute
    const placeholderElements = document.querySelectorAll('[data-translate-placeholder]');

    placeholderElements.forEach(element => {
        const key = element.getAttribute('data-translate-placeholder');
        const translation = getTranslation(key, language);

        if (translation) {
            element.placeholder = translation;
        }
    });

    // Update HTML lang attribute
    document.documentElement.lang = language;

    // Dispatch event for other components to react
    document.dispatchEvent(new CustomEvent('languageChanged', { detail: { language } }));
}

/**
 * Get translation for a key in the specified language
 */
function getTranslation(key, language) {
    const translations = {
        // Common UI elements
        'language': {
            'en': 'Language:',
            'hi': 'भाषा:',
            'pa': 'ਭਾਸ਼ਾ:'
        },

        // Navigation
        'nav-home': {
            'en': 'Home',
            'hi': 'होम',
            'pa': 'ਹੋਮ'
        },
        'nav-weather': {
            'en': 'Weather',
            'hi': 'मौसम',
            'pa': 'ਮੌਸਮ'
        },
        'nav-about': {
            'en': 'About',
            'hi': 'हमारे बारे में',
            'pa': 'ਸਾਡੇ ਬਾਰੇ'
        },
        'nav-contact': {
            'en': 'Contact',
            'hi': 'संपर्क',
            'pa': 'ਸੰਪਰਕ'
        },
        'nav-dashboard': {
            'en': 'Dashboard',
            'hi': 'डैशबोर्ड',
            'pa': 'ਡੈਸ਼ਬੋਰਡ'
        },
        'nav-login': {
            'en': 'Login',
            'hi': 'लॉगिन',
            'pa': 'ਲਾਗਇਨ'
        },
        'nav-register': {
            'en': 'Register',
            'hi': 'रजिस्टर',
            'pa': 'ਰਜਿਸਟਰ'
        },
        'nav-logout': {
            'en': 'Logout',
            'hi': 'लॉगआउट',
            'pa': 'ਲਾਗਆਊਟ'
        },

        // Home page
        'hero-title': {
            'en': 'Crop Guidance and Farmer\'s Friend',
            'hi': 'फसल मार्गदर्शन और किसान मित्र',
            'pa': 'ਫਸਲ ਮਾਰਗਦਰਸ਼ਨ ਅਤੇ ਕਿਸਾਨ ਮਿੱਤਰ'
        },
        'hero-subtitle': {
            'en': 'Empowering farmers with intelligent crop recommendations, weather insights, and pest warnings.',
            'hi': 'बुद्धिमान फसल सिफारिशों, मौसम अंतर्दृष्टि और कीट चेतावनियों के साथ किसानों को सशक्त बनाना।',
            'pa': 'ਬੁੱਧੀਮਾਨ ਫਸਲ ਸਿਫਾਰਸ਼ਾਂ, ਮੌਸਮ ਅੰਤਰਦਿਰਸ਼ਟੀ, ਅਤੇ ਕੀੜੇ ਚੇਤਾਵਨੀਆਂ ਨਾਲ ਕਿਸਾਨਾਂ ਨੂੰ ਸ਼ਕਤੀਸ਼ਾਲੀ ਬਣਾਉਣਾ।'
        },
        'get-started': {
            'en': 'Get Started',
            'hi': 'शुरू करें',
            'pa': 'ਸ਼ੁਰੂ ਕਰੋ'
        },
        'login': {
            'en': 'Login',
            'hi': 'लॉगिन',
            'pa': 'ਲਾਗਇਨ'
        },
        'go-to-dashboard': {
            'en': 'Go to Dashboard',
            'hi': 'डैशबोर्ड पर जाएं',
            'pa': 'ਡੈਸ਼ਬੋਰਡ ਤੇ ਜਾਓ'
        },

        // Features section
        'why-choose': {
            'en': 'Why Choose Farmer\'s Friend?',
            'hi': 'किसान मित्र को क्यों चुनें?',
            'pa': 'ਕਿਸਾਨ ਮਿੱਤਰ ਨੂੰ ਕਿਉਂ ਚੁਣੋ?'
        },
        'platform-description': {
            'en': 'Our platform combines weather data, AI-powered recommendations, and agricultural expertise to help you make informed decisions.',
            'hi': 'हमारा प्लेटफॉर्म मौसम डेटा, AI-संचालित सिफारिशों और कृषि विशेषज्ञता को जोड़ता है ताकि आप सूचित निर्णय ले सकें।',
            'pa': 'ਸਾਡਾ ਪਲੇਟਫਾਰਮ ਮੌਸਮ ਡਾਟਾ, AI-ਸੰਚਾਲਿਤ ਸਿਫਾਰਸ਼ਾਂ, ਅਤੇ ਖੇਤੀਬਾੜੀ ਮਾਹਿਰਤਾ ਨੂੰ ਜੋੜਦਾ ਹੈ ਤਾਂ ਜੋ ਤੁਸੀਂ ਸੂਚਿਤ ਫੈਸਲੇ ਲੈ ਸਕੋ।'
        },
        'weather-updates': {
            'en': 'Real-time Weather Updates',
            'hi': 'रीयल-टाइम मौसम अपडेट',
            'pa': 'ਰੀਅਲ-ਟਾਈਮ ਮੌਸਮ ਅਪਡੇਟ'
        },
        'weather-description': {
            'en': 'Access accurate weather forecasts and receive alerts for extreme conditions that might affect your crops.',
            'hi': 'सटीक मौसम पूर्वानुमान तक पहुंचें और चरम स्थितियों के लिए अलर्ट प्राप्त करें जो आपकी फसलों को प्रभावित कर सकते हैं।',
            'pa': 'ਸਹੀ ਮੌਸਮ ਭਵਿੱਖਬਾਣੀ ਤੱਕ ਪਹੁੰਚ ਪ੍ਰਾਪਤ ਕਰੋ ਅਤੇ ਅਜਿਹੀਆਂ ਚਰਮ ਸਥਿਤੀਆਂ ਲਈ ਚੇਤਾਵਨੀਆਂ ਪ੍ਰਾਪਤ ਕਰੋ ਜੋ ਤੁਹਾਡੀਆਂ ਫਸਲਾਂ ਨੂੰ ਪ੍ਰਭਾਵਿਤ ਕਰ ਸਕਦੀਆਂ ਹਨ।'
        },
        'crop-recommendations': {
            'en': 'AI-Powered Crop Recommendations',
            'hi': 'AI-संचालित फसल सिफारिशें',
            'pa': 'AI-ਸੰਚਾਲਿਤ ਫਸਲ ਸਿਫਾਰਸ਼ਾਂ'
        },
        'crop-description': {
            'en': 'Get personalized crop suggestions based on your location, soil type, and current weather conditions.',
            'hi': 'अपने स्थान, मिट्टी के प्रकार और वर्तमान मौसम की स्थिति के आधार पर व्यक्तिगत फसल सुझाव प्राप्त करें।',
            'pa': 'ਆਪਣੇ ਸਥਾਨ, ਮਿੱਟੀ ਦੀ ਕਿਸਮ, ਅਤੇ ਮੌਜੂਦਾ ਮੌਸਮ ਦੀਆਂ ਸਥਿਤੀਆਂ ਦੇ ਆਧਾਰ ਤੇ ਨਿੱਜੀ ਫਸਲ ਸੁਝਾਅ ਪ੍ਰਾਪਤ ਕਰੋ।'
        },
        'pest-warnings': {
            'en': 'Pest & Disease Warnings',
            'hi': 'कीट और रोग चेतावनियां',
            'pa': 'ਕੀੜੇ ਅਤੇ ਬਿਮਾਰੀ ਚੇਤਾਵਨੀਆਂ'
        },
        'pest-description': {
            'en': 'Stay ahead of potential threats with timely warnings and prevention strategies for common pests and diseases.',
            'hi': 'सामान्य कीटों और रोगों के लिए समय पर चेतावनियों और रोकथाम रणनीतियों के साथ संभावित खतरों से आगे रहें।',
            'pa': 'ਆਮ ਕੀੜਿਆਂ ਅਤੇ ਬਿਮਾਰੀਆਂ ਲਈ ਸਮੇਂ ਸਿਰ ਚੇਤਾਵਨੀਆਂ ਅਤੇ ਰੋਕਥਾਮ ਰਣਨੀਤੀਆਂ ਨਾਲ ਸੰਭਾਵੀ ਖਤਰਿਆਂ ਤੋਂ ਅੱਗੇ ਰਹੋ।'
        },

        // Dashboard
        'dashboard': {
            'en': 'Dashboard',
            'hi': 'डैशबोर्ड',
            'pa': 'ਡੈਸ਼ਬੋਰਡ'
        },
        'complete-profile': {
            'en': 'Complete Your Profile',
            'hi': 'अपनी प्रोफ़ाइल पूरी करें',
            'pa': 'ਆਪਣੀ ਪ੍ਰੋਫਾਈਲ ਪੂਰੀ ਕਰੋ'
        },
        'profile-message': {
            'en': 'Please update your profile with your location and soil type to get personalized crop recommendations and weather alerts.',
            'hi': 'व्यक्तिगत फसल सिफारिशें और मौसम अलर्ट प्राप्त करने के लिए कृपया अपनी प्रोफ़ाइल को अपने स्थान और मिट्टी के प्रकार के साथ अपडेट करें।',
            'pa': 'ਨਿੱਜੀ ਫਸਲ ਸਿਫਾਰਸ਼ਾਂ ਅਤੇ ਮੌਸਮ ਚੇਤਾਵਨੀਆਂ ਪ੍ਰਾਪਤ ਕਰਨ ਲਈ ਕਿਰਪਾ ਕਰਕੇ ਆਪਣੀ ਪ੍ਰੋਫਾਈਲ ਨੂੰ ਆਪਣੇ ਸਥਾਨ ਅਤੇ ਮਿੱਟੀ ਦੀ ਕਿਸਮ ਨਾਲ ਅਪਡੇਟ ਕਰੋ।'
        },
        'update-profile': {
            'en': 'Update Profile',
            'hi': 'प्रोफ़ाइल अपडेट करें',
            'pa': 'ਪ੍ਰੋਫਾਈਲ ਅਪਡੇਟ ਕਰੋ'
        },
        'current-weather': {
            'en': 'Current Weather',
            'hi': 'वर्तमान मौसम',
            'pa': 'ਮੌਜੂਦਾ ਮੌਸਮ'
        },
        'view-details': {
            'en': 'View Details',
            'hi': 'विवरण देखें',
            'pa': 'ਵੇਰਵੇ ਵੇਖੋ'
        },
        'weather-alerts': {
            'en': 'Weather Alerts',
            'hi': 'मौसम अलर्ट',
            'pa': 'ਮੌਸਮ ਚੇਤਾਵਨੀਆਂ'
        },
        '5-day-forecast': {
            'en': '5-Day Forecast',
            'hi': '5-दिन का पूर्वानुमान',
            'pa': '5-ਦਿਨ ਦੀ ਭਵਿੱਖਬਾਣੀ'
        },

        // Chatbot
        'chatbot-welcome': {
            'en': 'Hello! I\'m your Farmer\'s Assistant. How can I help you today?',
            'hi': 'नमस्ते! मैं आपका किसान सहायक हूँ। आज मैं आपकी कैसे मदद कर सकता हूँ?',
            'pa': 'ਸਤ ਸ੍ਰੀ ਅਕਾਲ! ਮੈਂ ਤੁਹਾਡਾ ਕਿਸਾਨ ਸਹਾਇਕ ਹਾਂ। ਮੈਂ ਅੱਜ ਤੁਹਾਡੀ ਕਿਵੇਂ ਮਦਦ ਕਰ ਸਕਦਾ ਹਾਂ?'
        },
        'chatbot-placeholder': {
            'en': 'Type your question...',
            'hi': 'अपना प्रश्न टाइप करें...',
            'pa': 'ਆਪਣਾ ਸਵਾਲ ਟਾਈਪ ਕਰੋ...'
        }
    };

    // Return the translation or the key if not found
    return translations[key] && translations[key][language] ?
        translations[key][language] :
        (translations[key] && translations[key]['en'] ? translations[key]['en'] : key);
}

// Expose functions to global scope
window.FarmersFriend = window.FarmersFriend || {};
window.FarmersFriend.Translations = {
    applyTranslations,
    getTranslation
};
