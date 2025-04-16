/**
 * Farmer's Friend - Chatbot
 * Powered by Google's Gemini AI
 */

document.addEventListener('DOMContentLoaded', function() {
    // Create chatbot UI if it doesn't exist
    if (!document.getElementById('chatbot-container')) {
        createChatbotUI();
    }

    // Initialize chatbot
    initChatbot();
});

/**
 * Create the chatbot UI elements
 */
function createChatbotUI() {
    // Create main container
    const chatbotContainer = document.createElement('div');
    chatbotContainer.id = 'chatbot-container';
    chatbotContainer.className = 'fixed bottom-5 right-5 z-50';

    // Create chatbot button
    const chatbotButton = document.createElement('button');
    chatbotButton.id = 'chatbot-button';
    chatbotButton.className = 'bg-accent hover:bg-purple-600 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg transition-all duration-300 hover-scale';
    chatbotButton.innerHTML = '<i class="fas fa-comment-dots text-xl"></i>';

    // Create chatbot panel
    const chatbotPanel = document.createElement('div');
    chatbotPanel.id = 'chatbot-panel';
    chatbotPanel.className = 'glass absolute bottom-16 right-0 w-80 md:w-96 rounded-lg shadow-xl overflow-hidden transition-all duration-300 transform scale-0 origin-bottom-right';

    // Create chatbot header
    const chatbotHeader = document.createElement('div');
    chatbotHeader.className = 'bg-gradient-to-r from-primary to-accent p-4 text-white flex justify-between items-center';
    chatbotHeader.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-robot mr-2"></i>
            <h3 class="font-medium">Farmer's Assistant</h3>
        </div>
        <div class="flex items-center">
            <select id="chatbot-language" class="bg-white/20 text-white text-sm rounded px-2 py-1 mr-2">
                <option value="en">English</option>
                <option value="hi">हिंदी</option>
                <option value="pa">ਪੰਜਾਬੀ</option>
            </select>
            <button id="chatbot-close" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    // Create chatbot messages container
    const chatbotMessages = document.createElement('div');
    chatbotMessages.id = 'chatbot-messages';
    chatbotMessages.className = 'h-80 overflow-y-auto p-4 bg-white/5';

    // Add welcome message
    chatbotMessages.innerHTML = `
        <div class="flex mb-4">
            <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center mr-2 flex-shrink-0">
                <i class="fas fa-robot text-accent"></i>
            </div>
            <div class="glass p-3 rounded-lg max-w-[80%] bg-white/10">
                <p class="text-sm" data-translate="chatbot-welcome">Hello! I'm your Farmer's Assistant. How can I help you today?</p>
            </div>
        </div>
    `;

    // Create chatbot input area
    const chatbotInput = document.createElement('div');
    chatbotInput.className = 'p-3 border-t border-white/10 bg-white/5 flex items-center';
    chatbotInput.innerHTML = `
        <input type="text" id="chatbot-input-field" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg focus:ring-accent focus:border-accent block w-full p-2.5" placeholder="Type your question..." data-translate-placeholder="chatbot-placeholder">
        <button id="chatbot-send" class="bg-accent hover:bg-purple-600 text-white rounded-full w-10 h-10 flex items-center justify-center ml-2 transition-colors">
            <i class="fas fa-paper-plane"></i>
        </button>
    `;

    // Assemble the chatbot
    chatbotPanel.appendChild(chatbotHeader);
    chatbotPanel.appendChild(chatbotMessages);
    chatbotPanel.appendChild(chatbotInput);

    chatbotContainer.appendChild(chatbotButton);
    chatbotContainer.appendChild(chatbotPanel);

    // Add to the document
    document.body.appendChild(chatbotContainer);
}

/**
 * Initialize chatbot functionality
 */
function initChatbot() {
    const chatbotButton = document.getElementById('chatbot-button');
    const chatbotPanel = document.getElementById('chatbot-panel');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotSend = document.getElementById('chatbot-send');
    const chatbotInput = document.getElementById('chatbot-input-field');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const chatbotLanguage = document.getElementById('chatbot-language');

    // Toggle chatbot panel
    chatbotButton.addEventListener('click', function() {
        chatbotPanel.classList.toggle('scale-0');
        if (!chatbotPanel.classList.contains('scale-0')) {
            chatbotInput.focus();
        }
    });

    // Close chatbot panel
    chatbotClose.addEventListener('click', function() {
        chatbotPanel.classList.add('scale-0');
    });

    // Send message on button click
    chatbotSend.addEventListener('click', function() {
        sendMessage();
    });

    // Send message on Enter key
    chatbotInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Change language
    chatbotLanguage.addEventListener('change', function() {
        const selectedLanguage = this.value;
        // Update the welcome message language
        updateChatbotLanguage(selectedLanguage);
    });

    /**
     * Send user message to chatbot
     */
    function sendMessage() {
        const message = chatbotInput.value.trim();
        if (message === '') return;

        // Add user message to chat
        addMessageToChat('user', message);

        // Clear input field
        chatbotInput.value = '';

        // Show typing indicator
        showTypingIndicator();

        // Get selected language
        const selectedLanguage = chatbotLanguage.value;

        // Send to Gemini API
        sendToGemini(message, selectedLanguage);
    }

    /**
     * Add a message to the chat
     */
    function addMessageToChat(sender, message) {
        const messageElement = document.createElement('div');
        messageElement.className = 'flex mb-4 ' + (sender === 'user' ? 'justify-end' : '');

        if (sender === 'user') {
            messageElement.innerHTML = `
                <div class="glass p-3 rounded-lg max-w-[80%] bg-accent/10">
                    <p class="text-sm">${escapeHTML(message)}</p>
                </div>
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center ml-2 flex-shrink-0">
                    <i class="fas fa-user text-white"></i>
                </div>
            `;
        } else {
            messageElement.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center mr-2 flex-shrink-0">
                    <i class="fas fa-robot text-accent"></i>
                </div>
                <div class="glass p-3 rounded-lg max-w-[80%] bg-white/10">
                    <p class="text-sm">${message}</p>
                </div>
            `;
        }

        chatbotMessages.appendChild(messageElement);

        // Scroll to bottom
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    /**
     * Show typing indicator
     */
    function showTypingIndicator() {
        const typingElement = document.createElement('div');
        typingElement.id = 'typing-indicator';
        typingElement.className = 'flex mb-4';
        typingElement.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center mr-2 flex-shrink-0">
                <i class="fas fa-robot text-accent"></i>
            </div>
            <div class="glass p-3 rounded-lg bg-white/10">
                <div class="typing-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        `;

        chatbotMessages.appendChild(typingElement);

        // Add CSS for typing dots
        if (!document.getElementById('typing-dots-style')) {
            const style = document.createElement('style');
            style.id = 'typing-dots-style';
            style.textContent = `
                .typing-dots {
                    display: flex;
                    align-items: center;
                    height: 20px;
                }
                .typing-dots span {
                    width: 6px;
                    height: 6px;
                    margin: 0 2px;
                    background-color: rgba(255, 255, 255, 0.7);
                    border-radius: 50%;
                    display: inline-block;
                    animation: typing-dot 1.4s infinite ease-in-out both;
                }
                .typing-dots span:nth-child(1) {
                    animation-delay: 0s;
                }
                .typing-dots span:nth-child(2) {
                    animation-delay: 0.2s;
                }
                .typing-dots span:nth-child(3) {
                    animation-delay: 0.4s;
                }
                @keyframes typing-dot {
                    0%, 80%, 100% { transform: scale(0.7); opacity: 0.5; }
                    40% { transform: scale(1); opacity: 1; }
                }
            `;
            document.head.appendChild(style);
        }

        // Scroll to bottom
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    /**
     * Remove typing indicator
     */
    function removeTypingIndicator() {
        const typingIndicator = document.getElementById('typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    /**
     * Send message to Gemini API
     */
    function sendToGemini(message, language) {
        // Construct the API URL with absolute path
        const baseUrl = window.location.pathname.includes('/farmer/') ? '/farmer' : '';
        const apiUrl = baseUrl + '/api/chatbot.php';

        // Prepare the data
        const data = {
            message: message,
            language: language
        };

        // Send the request
        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            // Remove typing indicator
            removeTypingIndicator();

            if (data.status === 'success') {
                // Add bot response to chat
                addMessageToChat('bot', data.response);
            } else {
                // Add error message
                addMessageToChat('bot', 'Sorry, I encountered an error. Please try again later.');
            }
        })
        .catch(error => {
            // Remove typing indicator
            removeTypingIndicator();

            // Add error message
            addMessageToChat('bot', 'Sorry, I encountered an error. Please try again later.');
            console.error('Error:', error);
        });
    }

    /**
     * Update chatbot language
     */
    function updateChatbotLanguage(language) {
        // Update placeholder text based on language
        switch(language) {
            case 'hi':
                chatbotInput.placeholder = 'अपना प्रश्न टाइप करें...';
                break;
            case 'pa':
                chatbotInput.placeholder = 'ਆਪਣਾ ਸਵਾਲ ਟਾਈਪ ਕਰੋ...';
                break;
            default:
                chatbotInput.placeholder = 'Type your question...';
        }

        // Send a language change notification to the chatbot
        const welcomeMessages = {
            'en': "I've switched to English. How can I help you?",
            'hi': "मैंने हिंदी में स्विच किया है। मैं आपकी कैसे मदद कर सकता हूँ?",
            'pa': "ਮੈਂ ਪੰਜਾਬੀ ਵਿੱਚ ਬਦਲ ਗਿਆ ਹਾਂ। ਮੈਂ ਤੁਹਾਡੀ ਕਿਵੇਂ ਮਦਦ ਕਰ ਸਕਦਾ ਹਾਂ?"
        };

        addMessageToChat('bot', welcomeMessages[language]);
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHTML(str) {
        return str.replace(/[&<>'"]/g,
            tag => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#39;',
                '"': '&quot;'
            }[tag]));
    }
}
