document.addEventListener('DOMContentLoaded', function() {
    const fab = document.getElementById('lkChatFab');
    const windowEl = document.getElementById('lkChatWindow');
    const closeBtn = document.getElementById('lkCloseChat');
    const sendBtn = document.getElementById('lkSendToWaBtn');
    const userMsgInput = document.getElementById('lkUserMessage');

    if (!fab || !windowEl) return;

    // Toggle chat window open/close state
    fab.addEventListener('click', () => windowEl.classList.toggle('active'));
    closeBtn.addEventListener('click', () => windowEl.classList.remove('active'));

    // Smart redirection to WhatsApp with product info and user message
    sendBtn.addEventListener('click', function() {
        const userText = userMsgInput.value.trim();
        let message = '';

        if (LK_SUPPORT_CONFIG.isProduct) {
            message += `Hello, I have a question about the product «${LK_SUPPORT_CONFIG.name}»:\n`;
            message += `Product Link: ${LK_SUPPORT_CONFIG.url}\n\n`;
        } else {
            message += `Hello, I am messaging from the website support:\n`;
        }

        if (userText !== '') {
            message += `My question: ${userText}`;
        } else {
            message += `Please guide me regarding this.`;
        }

        // Encode message for WhatsApp URL scheme
        const encodedMsg = encodeURIComponent(message);
        const waUrl = `https://wa.me/${LK_SUPPORT_CONFIG.phone}?text=${encodedMsg}`;

        // Open in a new tab
        window.open(waUrl, '_blank');
    });
});