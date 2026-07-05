import { b64ToU8 } from './utils.js';

export class MessagePoller {
    constructor(config, crypto, ui) {
        this.config = config;
        this.crypto = crypto;
        this.ui = ui;
        this.allMessageIds = new Set();
        this.highestKnownMessageId = 0;
        this.autoScrollEnabled = true;
        this.pollingInterval = null;
        this.deletedCheckInterval = null;
        this.isLoading = false;
        this.initialLoadDone = false;

        window.__chatPoller = this;
    }

    initExistingMessages() {
        document.querySelectorAll('.message').forEach(msgEl => {
            const id = parseInt(msgEl.dataset.id);
            if (!isNaN(id)) {
                this.allMessageIds.add(id);
                this.highestKnownMessageId = Math.max(this.highestKnownMessageId, id);
            }
        });
        console.log('[Poller] Init, highest ID:', this.highestKnownMessageId);
    }

    isAtBottom() {
        const el = document.getElementById('chat-messages');
        return el.scrollHeight - el.scrollTop <= el.clientHeight + 10;
    }

    scrollToBottom() {
        if (this.autoScrollEnabled) {
            const el = document.getElementById('chat-messages');
            el.scrollTop = el.scrollHeight;
        }
    }

    decryptAndAttach() {
        if (!this.crypto.chatKey) return;

        document.querySelectorAll('.card-text').forEach(el => {
            const messageEl = el.closest('.message');
            if (!messageEl || el.dataset.decrypted) return;

            const encrypted = el.dataset.encrypted;
            if (!encrypted || !encrypted.includes('|')) return;

            const txt = this.crypto.decrypt(encrypted);
            el.innerHTML = txt.replace(/\n/g, '<br>');
            el.dataset.plaintext = txt;
            el.dataset.decrypted = '1';

            if (messageEl.classList.contains('sent')) {
                const nonceB64 = encrypted.split('|')[0];
                this.ui.attachEditButton(messageEl, txt, nonceB64);
            } else {
                // Меню с тремя точками для чужих сообщений
                this.ui.attachMessageMenu(messageEl);
            }
        });

        this.loadReplyPreviews();
    }

    async loadReplyPreviews() {
        if (!this.crypto.chatKey) return;

        const previews = document.querySelectorAll('.reply-preview[data-reply-id]:not([data-loaded])');

        for (const preview of previews) {
            const replyId = preview.dataset.replyId;
            if (!replyId) continue;

            try {
                const res = await fetch(`/messages/${replyId}/reply-info`, {
                    headers: {
                        'X-CSRF-TOKEN': this.config.csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });

                const contentEl = preview.querySelector('.reply-content');

                if (res.status === 404) {
                    contentEl.innerHTML = '<em>Сообщение удалено</em>';
                    preview.dataset.loaded = '1';
                    continue;
                }

                if (!res.ok) continue;

                const data = await res.json();
                if (data.status !== 'success') continue;

                const encrypted = data.message.content;
                if (!encrypted || !encrypted.includes('|')) continue;

                const decrypted = this.crypto.decrypt(encrypted);
                const shortText = decrypted.replace(/\n/g, ' ').substring(0, 100);
                contentEl.innerHTML = `<strong>${this.escapeHtml(data.message.sender.name)}:</strong> ${this.escapeHtml(shortText)}`;

                preview.dataset.loaded = '1';

                preview.onclick = () => {
                    const targetMsg = document.querySelector(`.message[data-id="${replyId}"]`);
                    if (targetMsg) {
                        targetMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        targetMsg.classList.remove('reply-highlight');
                        void targetMsg.offsetWidth;
                        targetMsg.classList.add('reply-highlight');
                        setTimeout(() => targetMsg.classList.remove('reply-highlight'), 3000);
                    }
                };
            } catch (err) {
                console.error('Failed to load reply preview:', err);
            }
        }
    }

    async load() {
        if (this.isLoading) return;
        this.isLoading = true;

        try {
            const lastId = this.initialLoadDone ? this.highestKnownMessageId : 0;
            const url = `/chats/${this.config.chatId}/messages?last_id=${lastId}`;

            const res = await fetch(url, {
                headers: {
                    'X-CSRF-TOKEN': this.config.csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            });

            if (res.redirected || res.url.includes('/login')) {
                this.stopPolling();
                location.href = '/login';
                return;
            }

            if (!res.ok) {
                console.error('[Poller] HTTP', res.status);
                return;
            }

            const msgs = await res.json();
            if (!Array.isArray(msgs)) return;

            const wasAtBottom = this.isAtBottom();
            let hasIncoming = false;

            msgs.forEach(msg => {
                const existing = document.querySelector(`.message[data-id="${msg.id}"]`);
                const isOwn = msg.sender.id === this.config.userId;
                const isEdited = msg.is_edited === true;

                if (existing) {
                    const cardText = existing.querySelector('.card-text');
                    if (cardText) {
                        cardText.setAttribute('data-encrypted', msg.message);
                        delete cardText.dataset.decrypted;
                    }
                    existing.setAttribute('data-is-edited', isEdited);

                    const cardTitle = existing.querySelector('.card-title');
                    if (cardTitle) {
                        const oldEdited = cardTitle.querySelector('.message-status.edited');
                        if (isEdited && !oldEdited) {
                            const span = document.createElement('small');
                            span.className = 'message-status edited';
                            span.textContent = '✏️';
                            cardTitle.appendChild(span);
                        } else if (!isEdited && oldEdited) {
                            oldEdited.remove();
                        }
                    }
                } else if (!this.allMessageIds.has(msg.id)) {
                    if (!isOwn && msg.id > this.highestKnownMessageId) hasIncoming = true;

                    const chatMessages = document.getElementById('chat-messages');

                    const replyPreview = msg.reply_to_message_id
                        ? `<div class="reply-preview" data-reply-id="${msg.reply_to_message_id}">
                               <div class="reply-indicator"><i class="fa fa-reply" aria-hidden="true"></i></div>
                               <div class="reply-content">{{ __('chats.download') }} <i class="fa fa-spinner" aria-hidden="true"></i></div>
                           </div>`
                        : '';

                    const html = `
                        <div class="message ${isOwn ? 'sent' : 'received'}"
                             data-id="${msg.id}"
                             data-cid="${this.escapeAttr(msg.ipfs_cid)}"
                             data-is-edited="${isEdited}">
                            ${replyPreview}
                            <div class="${isOwn ? 'my-card-body' : 'card-body'}">
                                <p class="card-title">
                                    ${this.escapeHtml(msg.sender.name)}
                                    <small>${new Date(msg.created_at).toLocaleTimeString()}</small>
                                </p>
                                <p class="card-text">{{ __('chats.download') }} <i class="fa fa-spinner" aria-hidden="true"></i></p>
                            </div>
                        </div>
                    `;
                    chatMessages.insertAdjacentHTML('beforeend', html);

                    const newMsg = chatMessages.lastElementChild;
                    const cardText = newMsg.querySelector('.card-text');
                    cardText.setAttribute('data-encrypted', msg.message);

                    this.allMessageIds.add(msg.id);
                    this.highestKnownMessageId = Math.max(this.highestKnownMessageId, msg.id);
                }
            });

            this.initialLoadDone = true;

            this.decryptAndAttach();
            if (wasAtBottom) this.scrollToBottom();

            if (hasIncoming) {
                document.getElementById('notificationSound')?.play().catch(() => {});
            }
        } catch (err) {
            console.error('[Poller] Network error:', err);
        } finally {
            this.isLoading = false;
        }
    }

    async checkDeletedMessages() {
        try {
            const res = await fetch(`/chats/${this.config.chatId}/messages/ids`, {
                headers: {
                    'X-CSRF-TOKEN': this.config.csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            });
            if (!res.ok) return;

            const serverIds = new Set(await res.json());

            this.allMessageIds.forEach(id => {
                if (!serverIds.has(id)) {
                    const el = document.querySelector(`.message[data-id="${id}"]`);
                    if (el) {
                        el.remove();
                        this.allMessageIds.delete(id);
                    }
                }
            });
        } catch (err) {
            console.error('Failed to check deleted:', err);
        }
    }

    startPolling(interval = 3000) {
        if (this.pollingInterval) return;
        this.load();
        this.pollingInterval = setInterval(() => this.load(), interval);
        this.checkDeletedMessages();
        this.deletedCheckInterval = setInterval(() => this.checkDeletedMessages(), 30000);
    }

    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
        if (this.deletedCheckInterval) {
            clearInterval(this.deletedCheckInterval);
            this.deletedCheckInterval = null;
        }
    }

    escapeAttr(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
}