import { u8ToB64 } from './utils.js';

export class MessageSender {
    constructor(config, ui, crypto) {
        this.config = config;
        this.ui = ui;
        this.crypto = crypto;
        this.isSending = false;
    }

    async send(input, sendBtn, chatMessages) {
        if (this.isSending) return;

        const text = input.value.trim();
        if (!text) return;

        this.isSending = true;
        sendBtn.disabled = true;

        const originalInputValue = input.value;
        input.value = '';

        // Сохраняем replyingToMessageId ДО вызова cancelReply
        const replyToMessageId = this.ui.replyingToMessageId;

        // СРАЗУ скрываем превью ответа
        this.ui.cancelReply();

        // Шифруем
        const bytes = new TextEncoder().encode(text);
        const nonce = nacl.randomBytes(nacl.secretbox.nonceLength);
        const nonceB64 = u8ToB64(nonce);
        const encrypted = nacl.secretbox(bytes, nonce, this.crypto.chatKey);

        const isEditing = !!this.ui.editingMessageId;

        try {
            const url = isEditing
                ? `/messages/${this.ui.editingMessageId}`
                : this.config.sendUrl;

            const method = isEditing ? 'PATCH' : 'POST';

            const body = {
                message: u8ToB64(encrypted),
                nonce: nonceB64,
            };

            if (replyToMessageId && !isEditing) {
                body.reply_to_message_id = parseInt(replyToMessageId);
            }

            const res = await fetch(url, {
                method,
                headers: {
                    'X-CSRF-TOKEN': this.config.csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(body),
            });

            const data = await res.json();

            if (data.status !== 'success') throw new Error('Server error');

            if (isEditing) {
                this.commitEdit(data, nonceB64, encrypted, text);
            }
            // Для новых сообщений ничего не делаем — poller подхватит через 3 секунды

            this.ui.cancelEditing();

        } catch (err) {
            console.error('Send error:', err);
            input.value = originalInputValue;
            alert(this.config.translations.sendError);
        } finally {
            this.isSending = false;
            sendBtn.disabled = false;
        }
    }

    commitEdit(data, nonceB64, encrypted, text) {
        const msgEl = document.querySelector(`.message[data-id="${this.ui.editingMessageId}"]`);
        if (!msgEl) return;

        msgEl.classList.remove('editing');

        const newCid = data.message.ipfs_cid || `${nonceB64}|${u8ToB64(encrypted)}`;
        msgEl.setAttribute('data-cid', newCid);

        const cardText = msgEl.querySelector('.card-text');
        if (cardText) {
            cardText.setAttribute('data-encrypted', newCid);
            cardText.setAttribute('data-plaintext', text);
            cardText.dataset.decrypted = '1';
            cardText.innerHTML = text.replace(/\n/g, '<br>');
        }

        msgEl.setAttribute('data-is-edited', 'true');

        const cardTitle = msgEl.querySelector('.card-title');
        if (cardTitle && !cardTitle.querySelector('.message-status.edited')) {
            const editedSpan = document.createElement('small');
            editedSpan.className = 'message-status edited';
            editedSpan.textContent = '✏️';
            cardTitle.appendChild(editedSpan);
        }
    }
}