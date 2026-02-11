let pollingPaused = false;

function setPollingPaused(paused) {
    pollingPaused = paused;
}

document.addEventListener('DOMContentLoaded', function() {
    let currentConversation = null;
    let userId = null;

    // Load messages for a conversation
    function loadMessages(conversationId, scroll = true) {
        fetch('index.php?page=chat', {
            method: 'POST',
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            body: new URLSearchParams({
                ajax_get_messages: 1,
                conversation_id: conversationId
            })
        })
        .then(res => res.json())
        .then(data => {
            userId = data.user_id;
            const chatMain = document.querySelector('.chat-main');
            let messages = data.messages || [];

            let messagesHtml = '';
            if (messages.length) {
                messagesHtml = `
                    <div class="chat-messages">
                        ${messages.map(msg => `
                            <div class="chat-message${msg.send_id == userId ? ' me' : ''}">
                                <span class="chat-author">${escapeHtml(msg.sender_name)}:</span>
                                <span class="chat-text">${escapeHtml(msg.text)}</span>
                            </div>
                        `).join('')}
                    </div>
                `;
            } else {
                messagesHtml = '<div class="chat-empty">No messages yet.</div>';
            }

            // Always render the header with the service title
            chatMain.innerHTML = `
                <div class="chat-header">
                    ${data.service_title ? escapeHtml(data.service_title) : 'Select a conversation'}
                </div>
                ${messagesHtml}
                <form class="chat-send-form">
                    <input type="hidden" name="conversation_id" value="${conversationId}">
                    <input type="text" name="message" placeholder="Type a message..." required autocomplete="off">
                    <button type="submit" class="main-btn">Send</button>
                </form>
            `;

            if (scroll) scrollToBottom();
            attachSendHandler();
        });
    }

    // Send message via AJAX
    function attachSendHandler() {
        const sendForm = document.querySelector('.chat-send-form');
        if (sendForm) {
            const input = sendForm.querySelector('input[name="message"]');
            if (input) {
                input.addEventListener('focus', () => setPollingPaused(true));
                input.addEventListener('blur', () => setPollingPaused(false));
            }
            sendForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(sendForm);
                formData.append('ajax_send_message', 1);
                fetch('index.php?page=chat', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        loadMessages(currentConversation);
                    }
                });
                sendForm.reset();
            });
        }
    }

    // Auto-scroll to bottom
    function scrollToBottom() {
        const chatMessages = document.querySelector('.chat-messages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }

    // Escape HTML for safe output
    function escapeHtml(text) {
        return text.replace(/[&<>"']/g, function(m) {
            return ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            })[m];
        });
    }

    // Conversation click handler
    document.querySelectorAll('.conversation-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.conversation-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentConversation = this.closest('form').querySelector('input[name="conversation_id"]').value;
            loadMessages(currentConversation);
        });
    });

    // Conversation form submit handler
    document.querySelectorAll('.conversation-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            currentConversation = formData.get('conversation_id');
            loadMessages(currentConversation);
            // Highlight active button
            document.querySelectorAll('.conversation-btn').forEach(b => b.classList.remove('active'));
            this.querySelector('.conversation-btn').classList.add('active');
        });
    });

    // Poll for new messages every 5 seconds, but pause if typing
    setInterval(() => {
        if (currentConversation && !pollingPaused) {
            loadMessages(currentConversation, false);
        }
    }, 5000);

    // Auto-open the selected conversation if set
    if (typeof selectedConversationId !== 'undefined' && selectedConversationId) {
        const form = document.querySelector('.conversation-form input[value="' + selectedConversationId + '"]')?.closest('form');
        if (form) form.dispatchEvent(new Event('submit', {cancelable: true, bubbles: true}));
    }
});