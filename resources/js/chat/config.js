// Единая точка получения данных из DOM
export function loadChatConfig() {
    const el = document.getElementById('chat-config');
    if (!el) throw new Error('Chat config element not found');

    return {
        chatId: Number(el.dataset.chatId),
        userId: Number(el.dataset.userId),
        firstUnreadId: el.dataset.firstUnread ? Number(el.dataset.firstUnread) : null,
        sendUrl: el.dataset.sendUrl,
        csrfToken: el.dataset.csrf,
        translations: {
            edit: el.dataset.translateEdit,
            delete: el.dataset.translateDelete,
            reply: el.dataset.translateReply,
            send: el.dataset.translateSend,
            copy: el.dataset.translateCopy
            
        },
    };
}