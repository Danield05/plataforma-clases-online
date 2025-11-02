// Auto-scroll al final del chat
const chatMessages = document.getElementById('chatMessages');
if (chatMessages) {
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Enviar mensaje con Enter
const messageInput = document.getElementById('messageInput');
if (messageInput) {
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            // Aquí iría la lógica para enviar el mensaje
            console.log('Mensaje enviado:', this.value);
            this.value = '';
        }
    });
}