// Единая точка получения данных из DOM
export function loadChatConfig() {
    const el = document.getElementById('chat-config');
    if (!el) throw new Error('Chat config element not found');
    return {
        chatId: Number(el.dataset.chatId),
        userId: Number(el.dataset.userId),
        userName: el.dataset.userName || '',
        firstUnreadId: el.dataset.firstUnread ? Number(el.dataset.firstUnread) : null,
        sendUrl: el.dataset.sendUrl,
        csrfToken: el.dataset.csrf,
        translations: {
            edit: el.dataset.translateEdit,
            delete: el.dataset.translateDelete,
            reply: el.dataset.translateReply,
            send: el.dataset.translateSend,
            save: el.dataset.translateSave,
            copy: el.dataset.translateCopy,
            download: el.dataset.translateDownload,
            messageDeleted: el.dataset.translateMessageDeleted,
            deleteConfirm: el.dataset.translateDeleteConfirm,
            sendError: el.dataset.translateSendError,
            you: el.dataset.translateYou
        },
    };
}