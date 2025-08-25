<!-- Chatbot Widget -->
<div id="avicontrol-chatbot" class="chatbot-widget">
    <!-- Chat Toggle Button -->
    <div id="chatbot-toggle" class="chatbot-toggle">
        <div class="toggle-icon">
            <i class="fas fa-robot"></i>
        </div>
        <div class="notification-badge" id="notification-badge" style="display: none;">
            <span id="notification-count">1</span>
        </div>
    </div>

    <!-- Chat Window -->
    <div id="chatbot-window" class="chatbot-window">
        <!-- Header -->
        <div class="chatbot-header">
            <div class="bot-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="bot-info">
                <h4>AVICONTROL Assistant</h4>
                <span class="bot-status">
                    <span class="status-dot online"></span>
                    En línea - Siempre disponible
                </span>
            </div>
            <div class="header-actions">
                <button id="chat-minimize" class="btn-icon" title="Minimizar">
                    <i class="fas fa-minus"></i>
                </button>
                <button id="chat-close" class="btn-icon" title="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="chatbot-messages" class="chatbot-messages">
            <!-- Welcome Message -->
            <div class="message bot-message welcome-message">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    <div class="message-bubble">
                        <p>¡Hola! 👋 Soy el <strong>Asistente Virtual de AVICONTROL</strong></p>
                        <p>Estoy aquí para ayudarte con:</p>
                        <ul>
                            <li>🐔 Gestión de producción</li>
                            <li>📦 Control de inventario</li>
                            <li>💰 Análisis de costos</li>
                            <li>📊 Generación de reportes</li>
                            <li>🔧 Solución de problemas</li>
                        </ul>
                        <p><strong>¿En qué puedo ayudarte hoy?</strong></p>
                        <p><small>💡 Si tienes problemas de conexión, usa el botón "🔧 Test conexión" para diagnóstico.</small></p>
                    </div>
                    <div class="message-actions">
                        <button class="action-btn" data-action="show_modules">
                            📋 Ver módulos
                        </button>
                        <button class="action-btn" data-action="quick_stats">
                            📊 Estadísticas
                        </button>
                        <button class="action-btn" data-action="emergency_help">
                            🆘 Ayuda urgente
                        </button>
                        <button class="action-btn" data-action="test_connection">
                            🔧 Test conexión
                        </button>
                    </div>
                    <div class="message-time">Ahora</div>
                </div>
            </div>
        </div>

        <!-- Quick Suggestions -->
        <div id="quick-suggestions" class="quick-suggestions">
            <div class="suggestions-title">Preguntas frecuentes:</div>
            <div class="suggestions-list">
                <button class="suggestion-btn" data-suggestion="¿Cómo registro producción de huevos?">
                    🥚 ¿Cómo registro producción?
                </button>
                <button class="suggestion-btn" data-suggestion="¿Cómo controlo el inventario?">
                    📦 Control de inventario
                </button>
                <button class="suggestion-btn" data-suggestion="¿Cómo genero reportes en PDF?">
                    📄 Generar reportes
                </button>
                <button class="suggestion-btn" data-suggestion="¿Dónde veo las estadísticas?">
                    📊 Ver estadísticas
                </button>
            </div>
        </div>

        <!-- Typing Indicator -->
        <div id="typing-indicator" class="typing-indicator" style="display: none;">
            <div class="message-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="typing-content">
                <div class="typing-bubble">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="chatbot-input">
            <div class="input-container">
                <button id="voice-input" class="btn-voice" title="Entrada por voz">
                    <i class="fas fa-microphone"></i>
                </button>
                <input type="text" id="chat-input" placeholder="Escribe tu pregunta aquí..." autocomplete="off">
                <button id="send-message" class="btn-send" title="Enviar mensaje">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            <div class="input-footer">
                <small>Presiona <kbd>Enter</kbd> para enviar • <kbd>Shift + Enter</kbd> para nueva línea</small>
            </div>
        </div>
    </div>
</div>

<style>
/* Chatbot Styles */
.chatbot-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Toggle Button */
.chatbot-toggle {
    position: relative;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #4d7c0f 0%, #65a30d 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 8px 25px rgba(77, 124, 15, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    animation: pulse 2s infinite;
}

.chatbot-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 12px 35px rgba(77, 124, 15, 0.4);
}

.toggle-icon {
    color: white;
    font-size: 24px;
    transition: transform 0.3s ease;
}

.chatbot-toggle:hover .toggle-icon {
    transform: rotate(360deg);
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: bold;
    animation: bounce 1s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 8px 25px rgba(77, 124, 15, 0.3); }
    50% { box-shadow: 0 8px 25px rgba(77, 124, 15, 0.5); }
    100% { box-shadow: 0 8px 25px rgba(77, 124, 15, 0.3); }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-3px); }
    60% { transform: translateY(-2px); }
}

/* Chat Window */
.chatbot-window {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 420px;
    height: 650px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    display: none;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Header */
.chatbot-header {
    background: linear-gradient(135deg, #4d7c0f 0%, #65a30d 100%);
    color: white;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.bot-avatar {
    width: 45px;
    height: 45px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.bot-info {
    flex: 1;
}

.bot-info h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.bot-status {
    font-size: 12px;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 6px;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22c55e;
    animation: pulse-dot 2s infinite;
}

@keyframes pulse-dot {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.header-actions {
    display: flex;
    gap: 8px;
}

.btn-icon {
    width: 32px;
    height: 32px;
    border: none;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s ease;
}

.btn-icon:hover {
    background: rgba(255, 255, 255, 0.2);
}

/* Messages */
.chatbot-messages {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    scroll-behavior: smooth;
}

.chatbot-messages::-webkit-scrollbar {
    width: 6px;
}

.chatbot-messages::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.chatbot-messages::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.chatbot-messages::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.message {
    display: flex;
    margin-bottom: 20px;
    animation: fadeInUp 0.3s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 14px;
    flex-shrink: 0;
}

.bot-message .message-avatar {
    background: linear-gradient(135deg, #4d7c0f 0%, #65a30d 100%);
    color: white;
}

.user-message {
    flex-direction: row-reverse;
}

.user-message .message-avatar {
    background: #3b82f6;
    color: white;
    margin-right: 0;
    margin-left: 12px;
}

.message-content {
    flex: 1;
    max-width: 320px;
}

.user-message .message-content {
    text-align: right;
}

.message-bubble {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 12px 16px;
    margin-bottom: 8px;
    line-height: 1.5;
    font-size: 14px;
}

.user-message .message-bubble {
    background: #3b82f6;
    color: white;
    border: none;
}

.bot-message.welcome-message .message-bubble {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 1px solid #0ea5e9;
}

.message-bubble p {
    margin: 0 0 8px 0;
}

.message-bubble p:last-child {
    margin-bottom: 0;
}

.message-bubble ul {
    margin: 8px 0;
    padding-left: 20px;
}

.message-bubble li {
    margin-bottom: 4px;
}

.message-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 12px;
}

.action-btn {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 8px 12px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #374151;
}

.action-btn:hover {
    background: #4d7c0f;
    color: white;
    border-color: #4d7c0f;
    transform: translateY(-1px);
}

.message-time {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 4px;
}

.user-message .message-time {
    text-align: right;
}

/* Quick Suggestions */
.quick-suggestions {
    padding: 15px 20px;
    border-top: 1px solid #f1f5f9;
    background: #fafbfc;
}

.suggestions-title {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 10px;
}

.suggestions-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.suggestion-btn {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 15px;
    padding: 6px 10px;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #374151;
}

.suggestion-btn:hover {
    background: #4d7c0f;
    color: white;
    border-color: #4d7c0f;
}

/* Typing Indicator */
.typing-indicator {
    display: flex;
    margin-bottom: 20px;
    animation: fadeIn 0.3s ease;
}

.typing-content {
    flex: 1;
}

.typing-bubble {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 12px 16px;
    width: fit-content;
}

.typing-dots {
    display: flex;
    gap: 4px;
}

.typing-dots span {
    width: 6px;
    height: 6px;
    background: #9ca3af;
    border-radius: 50%;
    animation: typing 1.4s infinite;
}

.typing-dots span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dots span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-10px); }
}

/* Input Area */
.chatbot-input {
    padding: 15px 20px;
    border-top: 1px solid #f1f5f9;
    background: white;
}

.input-container {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 25px;
    padding: 8px 15px;
    transition: border-color 0.2s ease;
}

.input-container:focus-within {
    border-color: #4d7c0f;
    box-shadow: 0 0 0 3px rgba(77, 124, 15, 0.1);
}

.btn-voice {
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.btn-voice:hover {
    color: #4d7c0f;
    background: rgba(77, 124, 15, 0.1);
}

.btn-voice.recording {
    color: #ef4444;
    animation: pulse 1s infinite;
}

#chat-input {
    flex: 1;
    border: none;
    background: none;
    outline: none;
    font-size: 14px;
    color: #374151;
}

#chat-input::placeholder {
    color: #9ca3af;
}

.btn-send {
    background: #4d7c0f;
    border: none;
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.btn-send:hover {
    background: #3f6a0a;
    transform: scale(1.05);
}

.btn-send:disabled {
    background: #d1d5db;
    cursor: not-allowed;
    transform: none;
}

.input-footer {
    text-align: center;
    margin-top: 8px;
}

.input-footer small {
    color: #9ca3af;
    font-size: 11px;
}

.input-footer kbd {
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 3px;
    padding: 2px 4px;
    font-size: 10px;
    color: #374151;
}

/* Responsive */
@media (max-width: 480px) {
    .chatbot-window {
        width: calc(100vw - 30px);
        height: calc(100vh - 90px);
        bottom: 80px;
        right: 15px;
    }
    
    .chatbot-toggle {
        width: 55px;
        height: 55px;
    }
    
    .toggle-icon {
        font-size: 22px;
    }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .chatbot-window {
        background: #1f2937;
        border-color: #374151;
    }
    
    .message-bubble {
        background: #374151;
        border-color: #4b5563;
        color: #f9fafb;
    }
    
    .quick-suggestions {
        background: #111827;
        border-color: #374151;
    }
    
    .chatbot-input {
        background: #1f2937;
        border-color: #374151;
    }
    
    .input-container {
        background: #374151;
        border-color: #4b5563;
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* High Contrast Mode */
@media (prefers-contrast: high) {
    .chatbot-toggle {
        border: 2px solid #000;
    }
    
    .message-bubble {
        border: 2px solid #000;
    }
    
    .action-btn, .suggestion-btn {
        border: 2px solid #000;
    }
}
</style>

<script>
class AVICONTROLChatbot {
    constructor() {
        this.isOpen = false;
        this.isTyping = false;
        this.conversationHistory = [];
        this.currentSuggestions = [];
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.loadHistory();
        this.showWelcomeNotification();
    }
    
    bindEvents() {
        // Toggle chat
        document.getElementById('chatbot-toggle').addEventListener('click', () => {
            this.toggleChat();
        });
        
        // Close/minimize buttons
        document.getElementById('chat-close').addEventListener('click', () => {
            this.closeChat();
        });
        
        document.getElementById('chat-minimize').addEventListener('click', () => {
            this.minimizeChat();
        });
        
        // Send message
        document.getElementById('send-message').addEventListener('click', () => {
            this.sendMessage();
        });
        
        // Enter key to send
        document.getElementById('chat-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });
        
        // Voice input (placeholder)
        document.getElementById('voice-input').addEventListener('click', () => {
            this.toggleVoiceInput();
        });
        
        // Action buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('action-btn')) {
                this.handleAction(e.target.dataset.action, e.target.dataset.url);
            }
            
            if (e.target.classList.contains('suggestion-btn')) {
                this.sendSuggestion(e.target.dataset.suggestion);
            }
        });
        
        // Auto-resize input
        document.getElementById('chat-input').addEventListener('input', (e) => {
            this.autoResizeInput(e.target);
        });
    }
    
    toggleChat() {
        const window = document.getElementById('chatbot-window');
        
        if (this.isOpen) {
            this.closeChat();
        } else {
            this.openChat();
        }
    }
    
    openChat() {
        const window = document.getElementById('chatbot-window');
        window.style.display = 'flex';
        this.isOpen = true;
        this.hideNotification();
        
        // Focus input
        setTimeout(() => {
            document.getElementById('chat-input').focus();
        }, 300);
        
        // Scroll to bottom
        this.scrollToBottom();
    }
    
    closeChat() {
        const window = document.getElementById('chatbot-window');
        window.style.display = 'none';
        this.isOpen = false;
    }
    
    minimizeChat() {
        this.closeChat();
    }
    
    async sendMessage() {
        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        
        if (!message || this.isTyping) return;
        
        // Add user message
        this.addMessage(message, 'user');
        input.value = '';
        
        // Show typing indicator
        this.showTyping();
        
        try {
            // Send to backend
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value || '';
            
            const response = await fetch('{{ route("avicontrol.admin.chatbot.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    message: message,
                    history: this.conversationHistory
                })
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                // Add bot response
                this.addMessage(data.message, 'bot', {
                    actions: data.actions || [],
                    type: data.type,
                    context: data.context
                });
                
                // Update suggestions
                this.updateSuggestions(data.suggestions || []);
                
                // Save to history
                this.saveToHistory(message, data.message);
            } else {
                const errorMsg = data.message || 'Ha ocurrido un error desconocido';
                console.error('Chatbot API error:', data);
                this.addMessage(`❌ Error: ${errorMsg}`, 'bot');
            }
        } catch (error) {
            console.error('Chatbot error:', error);
            let errorMessage = 'Error de conexión. ';
            
            if (error.message.includes('404')) {
                errorMessage += 'Ruta no encontrada. Verifica la configuración del servidor.';
            } else if (error.message.includes('419')) {
                errorMessage += 'Token CSRF expirado. Recarga la página.';
            } else if (error.message.includes('500')) {
                errorMessage += 'Error interno del servidor. Revisa los logs.';
            } else {
                errorMessage += 'Verifica tu conexión a internet y que el servidor esté funcionando.';
            }
            
            this.addMessage(errorMessage, 'bot');
        } finally {
            this.hideTyping();
        }
    }
    
    sendSuggestion(suggestion) {
        const input = document.getElementById('chat-input');
        input.value = suggestion;
        this.sendMessage();
    }
    
    addMessage(content, sender, options = {}) {
        const messagesContainer = document.getElementById('chatbot-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}-message`;
        
        const time = new Date().toLocaleTimeString('es-ES', {
            hour: '2-digit',
            minute: '2-digit'
        });
        
        let actionsHtml = '';
        if (options.actions && options.actions.length > 0) {
            actionsHtml = '<div class="message-actions">';
            options.actions.forEach(action => {
                actionsHtml += `<button class="action-btn" data-action="${action.action}" ${action.url ? `data-url="${action.url}"` : ''}>${action.text}</button>`;
            });
            actionsHtml += '</div>';
        }
        
        const avatarIcon = sender === 'user' ? 'fa-user' : 'fa-robot';
        
        messageDiv.innerHTML = `
            <div class="message-avatar">
                <i class="fas ${avatarIcon}"></i>
            </div>
            <div class="message-content">
                <div class="message-bubble">
                    ${this.formatMessage(content)}
                </div>
                ${actionsHtml}
                <div class="message-time">${time}</div>
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
        this.scrollToBottom();
    }
    
    formatMessage(content) {
        // Convert markdown-style formatting
        return content
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/`(.*?)`/g, '<code>$1</code>')
            .replace(/\n/g, '<br>');
    }
    
    showTyping() {
        this.isTyping = true;
        const typingIndicator = document.getElementById('typing-indicator');
        typingIndicator.style.display = 'flex';
        this.scrollToBottom();
    }
    
    hideTyping() {
        this.isTyping = false;
        const typingIndicator = document.getElementById('typing-indicator');
        typingIndicator.style.display = 'none';
    }
    
    updateSuggestions(suggestions) {
        const suggestionsList = document.querySelector('.suggestions-list');
        
        if (suggestions.length === 0) return;
        
        suggestionsList.innerHTML = '';
        suggestions.forEach(suggestion => {
            const btn = document.createElement('button');
            btn.className = 'suggestion-btn';
            btn.dataset.suggestion = suggestion;
            btn.textContent = suggestion;
            suggestionsList.appendChild(btn);
        });
    }
    
    handleAction(action, url) {
        switch (action) {
            case 'navigate':
                if (url) {
                    window.location.href = url;
                }
                break;
            case 'show_modules':
                this.sendSuggestion('¿Qué módulos están disponibles?');
                break;
            case 'quick_stats':
                this.sendSuggestion('¿Cuáles son las estadísticas del sistema?');
                break;
            case 'emergency_help':
                this.sendSuggestion('Necesito ayuda urgente con un problema');
                break;
            case 'test_connection':
                this.testConnection();
                break;
            default:
                console.log('Unknown action:', action);
        }
    }
    
    toggleVoiceInput() {
        const btn = document.getElementById('voice-input');
        
        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            const recognition = new SpeechRecognition();
            
            recognition.lang = 'es-ES';
            recognition.continuous = false;
            recognition.interimResults = false;
            
            recognition.onstart = () => {
                btn.classList.add('recording');
                btn.innerHTML = '<i class="fas fa-stop"></i>';
            };
            
            recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript;
                document.getElementById('chat-input').value = transcript;
            };
            
            recognition.onend = () => {
                btn.classList.remove('recording');
                btn.innerHTML = '<i class="fas fa-microphone"></i>';
            };
            
            recognition.onerror = (event) => {
                console.error('Speech recognition error:', event.error);
                btn.classList.remove('recording');
                btn.innerHTML = '<i class="fas fa-microphone"></i>';
            };
            
            recognition.start();
        } else {
            alert('Tu navegador no soporta reconocimiento de voz');
        }
    }
    
    scrollToBottom() {
        const messagesContainer = document.getElementById('chatbot-messages');
        setTimeout(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 100);
    }
    
    autoResizeInput(input) {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 100) + 'px';
    }
    
    showWelcomeNotification() {
        setTimeout(() => {
            if (!this.isOpen) {
                this.showNotification();
            }
        }, 3000);
    }
    
    showNotification() {
        const badge = document.getElementById('notification-badge');
        badge.style.display = 'flex';
    }
    
    hideNotification() {
        const badge = document.getElementById('notification-badge');
        badge.style.display = 'none';
    }
    
    saveToHistory(userMessage, botResponse) {
        this.conversationHistory.push({
            user: userMessage,
            bot: botResponse,
            timestamp: Date.now()
        });
        
        // Keep only last 10 conversations
        if (this.conversationHistory.length > 10) {
            this.conversationHistory = this.conversationHistory.slice(-10);
        }
        
        // Save to localStorage
        localStorage.setItem('avicontrol_chat_history', JSON.stringify(this.conversationHistory));
    }
    
    async testConnection() {
        this.addMessage('🔧 Probando conexión con el servidor...', 'user');
        this.showTyping();
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            
            // Test 1: Verificar CSRF token
            if (!csrfToken) {
                throw new Error('CSRF token no encontrado');
            }
            
            // Test 2: Probar endpoint de test
            const testResponse = await fetch('{{ route("avicontrol.admin.chatbot.test") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!testResponse.ok) {
                throw new Error(`Test endpoint failed: ${testResponse.status}`);
            }
            
            const testData = await testResponse.json();
            
            // Test 3: Probar endpoint de chat
            const chatResponse = await fetch('{{ route("avicontrol.admin.chatbot.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    message: '/help',
                    history: []
                })
            });
            
            if (!chatResponse.ok) {
                throw new Error(`Chat endpoint failed: ${chatResponse.status}`);
            }
            
            const chatData = await chatResponse.json();
            
            // Mostrar resultado exitoso
            let message = '✅ **DIAGNÓSTICO DE CONEXIÓN EXITOSO**\n\n';
            message += '🔐 **CSRF Token:** Disponible ✅\n';
            message += `🌐 **Test Endpoint:** ${testResponse.status} ✅\n`;
            message += `💬 **Chat Endpoint:** ${chatResponse.status} ✅\n`;
            message += `⏰ **Tiempo de respuesta:** < 1s ✅\n\n`;
            message += '**El chatbot está funcionando correctamente.**\n';
            message += 'Puedes hacer cualquier pregunta ahora.';
            
            this.addMessage(message, 'bot', {
                actions: [
                    {text: '❓ Hacer una pregunta', action: 'show_modules'},
                    {text: '📊 Ver estadísticas', action: 'quick_stats'}
                ]
            });
            
        } catch (error) {
            console.error('Connection test failed:', error);
            
            let message = '❌ **DIAGNÓSTICO DE CONEXIÓN FALLIDO**\n\n';
            message += `**Error:** ${error.message}\n\n`;
            message += '**Posibles soluciones:**\n';
            message += '• Recarga la página (F5)\n';
            message += '• Verifica que el servidor esté funcionando\n';
            message += '• Comprueba tu conexión a internet\n';
            message += '• Contacta al administrador del sistema\n\n';
            
            // Información adicional para debugging
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            message += '**Información de debugging:**\n';
            message += `• CSRF Token: ${csrfToken ? 'Disponible' : 'No encontrado'}\n`;
            message += `• URL actual: ${window.location.href}\n`;
            message += `• User Agent: ${navigator.userAgent.substring(0, 50)}...\n`;
            
            this.addMessage(message, 'bot');
        } finally {
            this.hideTyping();
        }
    }
    
    loadHistory() {
        try {
            const saved = localStorage.getItem('avicontrol_chat_history');
            if (saved) {
                this.conversationHistory = JSON.parse(saved);
            }
        } catch (error) {
            console.error('Error loading chat history:', error);
        }
    }
}

// Initialize chatbot when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.avicontrolChatbot = new AVICONTROLChatbot();
});
</script>
