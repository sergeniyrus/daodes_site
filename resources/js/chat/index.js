import nacl from 'tweetnacl';
window.nacl = nacl;

import { loadChatConfig } from './config.js';
import { ChatCrypto } from './crypto.js';
import { ChatUI } from './ui.js';
import { MessageSender } from './sender.js';
import { MessagePoller } from './polling.js';

document.addEventListener('DOMContentLoaded', async () => {
    const config = loadChatConfig();
    const crypto = new ChatCrypto(null);
    const ui = new ChatUI(config);
    const sender = new MessageSender(config, ui, crypto);
    const poller = new MessagePoller(config, crypto, ui);

    const form = document.getElementById('messageForm');
    const input = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const chatMessages = document.getElementById('chat-messages');

    form.addEventListener('submit', e => {
        e.preventDefault();
        e.stopPropagation();
        return false;
    });

    chatMessages.addEventListener('scroll', () => {
        poller.autoScrollEnabled = poller.isAtBottom();
    });

    input.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            e.preventDefault();
            if (ui.replyingToMessageId) {
                ui.cancelReply();
            } else {
                ui.cancelEditing();
            }
        } else if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sender.send(input, sendBtn, chatMessages);
        }
    });

    sendBtn.addEventListener('click', () => sender.send(input, sendBtn, chatMessages));

    const cancelReplyBtn = document.getElementById('cancelReply');
    if (cancelReplyBtn) {
        cancelReplyBtn.addEventListener('click', () => ui.cancelReply());
    }

    if (config.firstUnreadId) {
        setTimeout(() => {
            const el = document.querySelector(`.message[data-id="${config.firstUnreadId}"]`);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                poller.autoScrollEnabled = false;
            } else {
                poller.scrollToBottom();
            }
        }, 100);
    } else {
        setTimeout(() => poller.scrollToBottom(), 100);
    }

    const privKeyB64 = localStorage.getItem(`userPrivateKey_${config.userId}`);
    if (!privKeyB64) {
        sessionStorage.setItem('url.intended', location.href);
        location.href = '/setup-keys?new_device=1';
        return;
    }

    try {
        const r = await fetch(`/chats/${config.chatId}/my-key`, {
            headers: {
                'X-CSRF-TOKEN': config.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (r.redirected || r.url.includes('/login')) {
            location.href = '/login';
            return;
        }

        const data = await r.json();

        crypto.chatKey = nacl.box.open(
            new Uint8Array(atob(data.encrypted_key).split('').map(c => c.charCodeAt(0))),
            new Uint8Array(atob(data.nonce).split('').map(c => c.charCodeAt(0))),
            new Uint8Array(atob(data.initiator_public_key).split('').map(c => c.charCodeAt(0))),
            new Uint8Array(atob(privKeyB64).split('').map(c => c.charCodeAt(0)))
        );

        if (!crypto.chatKey) {
            console.error('Ключ чата не получен');
            return;
        }

        poller.initExistingMessages();
        // Запускаем polling через startPolling (с защитой от параллельных запросов)
        poller.startPolling(3000);
    } catch (err) {
        console.error('Failed to load chat key:', err);
    }

    let audioUnlocked = false;
    const unlockAudio = () => {
        if (audioUnlocked) return;
        const sound = document.getElementById('notificationSound');
        if (sound) {
            sound.play().then(() => {
                sound.pause();
                sound.currentTime = 0;
                audioUnlocked = true;
            }).catch(() => {});
        }
    };
    ['click', 'keydown', 'touchstart'].forEach(evt =>
        document.addEventListener(evt, unlockAudio, { once: true, passive: true })
    );

    window.addEventListener('beforeunload', () => {
        poller.stopPolling();
    });
});