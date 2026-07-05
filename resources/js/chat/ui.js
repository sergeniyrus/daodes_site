export class ChatUI {
    constructor(config) {
        this.config = config;
        this.editingMessageId = null;
        this.editingBackupText = null;
        this.editingNonceB64 = null;
        this.replyingToMessageId = null;
    }

    attachEditButton(messageEl, plaintext, nonceB64) {
        if (messageEl.querySelector('.edit-actions')) return;
        const body = messageEl.querySelector('.my-card-body');
        if (!body) return;
        const actions = document.createElement('div');
        actions.className = 'edit-actions';
        actions.innerHTML = '<span class="dots">⋮</span>';
        body.appendChild(actions);
        actions.querySelector('.dots').onclick = e => {
            e.stopPropagation();
            this.createContextMenu(messageEl.dataset.id, messageEl, plaintext, nonceB64);
        };
        body.onmouseenter = () => actions.style.opacity = '1';
        body.onmouseleave = () => actions.style.opacity = '0';
    }

    // Меню с тремя точками для чужих сообщений (Ответить + Копировать)
    attachMessageMenu(messageEl) {
        if (messageEl.classList.contains('sent')) return; // только для чужих
        if (messageEl.querySelector('.edit-actions')) return; // уже есть

        const body = messageEl.querySelector('.card-body');
        if (!body) return;

        const actions = document.createElement('div');
        actions.className = 'edit-actions';
        actions.innerHTML = '<span class="dots">⋮</span>';
        body.appendChild(actions);

        actions.querySelector('.dots').onclick = e => {
            e.stopPropagation();
            const msgId = messageEl.dataset.id;
            const text = messageEl.querySelector('.card-text')?.dataset.plaintext || '';
            this.createReceivedContextMenu(msgId, messageEl, text);
        };

        body.onmouseenter = () => actions.style.opacity = '1';
        body.onmouseleave = () => actions.style.opacity = '0';
    }

    createReceivedContextMenu(messageId, messageEl, text) {
        document.querySelectorAll('.context-menu').forEach(e => e.remove());
        const menu = document.createElement('div');
        menu.className = 'context-menu';

        // Кнопка Ответить — с переводом и иконкой
        const replyBtn = document.createElement('div');
        replyBtn.innerHTML = '<i class="fa fa-reply" aria-hidden="true"></i> ' + (this.config.translations.reply || 'Ответить');
        replyBtn.onclick = () => {
            menu.remove();
            const titleEl = messageEl.querySelector('.card-title');
            const senderName = titleEl
                ? titleEl.childNodes[0]?.textContent.trim() || ''
                : '';
            this.startReply(messageId, senderName, text);
        };

        // Кнопка Копировать — с переводом и иконкой
        const copyBtn = document.createElement('div');
        copyBtn.innerHTML = '<i class="fa fa-copy" aria-hidden="true"></i> ' + (this.config.translations.copy || 'Копировать');
        copyBtn.onclick = async () => {
            try {
                await navigator.clipboard.writeText(text);
            } catch (err) {
                console.error('Failed to copy:', err);
            }
            menu.remove();
        };

        menu.append(replyBtn, copyBtn);
        document.body.appendChild(menu);

        const r = messageEl.getBoundingClientRect();
        menu.style.top = (r.top + window.scrollY + 10) + 'px';
        menu.style.left = (r.right + window.scrollX - 120) + 'px';

        setTimeout(() => {
            document.addEventListener('click', e => {
                if (!menu.contains(e.target)) menu.remove();
            }, { once: true });
        }, 0);
    }

    createContextMenu(messageId, messageEl, text, nonceB64) {
        document.querySelectorAll('.context-menu').forEach(e => e.remove());
        const menu = document.createElement('div');
        menu.className = 'context-menu';

        // Кнопка Ответить — с переводом и иконкой
        const replyBtn = document.createElement('div');
        replyBtn.innerHTML = '<i class="fa fa-reply" aria-hidden="true"></i> ' + (this.config.translations.reply || 'Ответить');
        replyBtn.onclick = () => {
            menu.remove();
            const titleEl = messageEl.querySelector('.card-title');
            const senderName = titleEl
                ? titleEl.childNodes[0]?.textContent.trim() || ''
                : '';
            const currentText = messageEl.querySelector('.card-text')?.dataset.plaintext || text;
            this.startReply(messageId, senderName, currentText);
        };

        // Кнопка Редактировать — с переводом
        const editBtn = document.createElement('div');
        editBtn.textContent = this.config.translations.edit;
        editBtn.onclick = () => {
            menu.remove();
            const currentText = messageEl.querySelector('.card-text')?.dataset.plaintext || text;
            this.startEditing(messageId, messageEl, currentText, nonceB64);
        };

        // Кнопка Удалить — с переводом
        const delBtn = document.createElement('div');
        delBtn.textContent = this.config.translations.delete;
        delBtn.style.color = '#ff6b6b';
        delBtn.onclick = () => {
            menu.remove();
            this.confirmDelete(messageId, messageEl);
        };

        menu.append(replyBtn, editBtn, delBtn);
        document.body.appendChild(menu);

        const r = messageEl.getBoundingClientRect();
        menu.style.top = (r.top + window.scrollY + 10) + 'px';
        menu.style.left = (r.right + window.scrollX - 120) + 'px';

        setTimeout(() => {
            document.addEventListener('click', e => {
                if (!menu.contains(e.target)) menu.remove();
            }, { once: true });
        }, 0);
    }

    startEditing(messageId, messageEl, text, nonceB64) {
        this.cancelEditing();
        this.cancelReply();
        this.editingMessageId = messageId;
        this.editingBackupText = text;
        this.editingNonceB64 = nonceB64;
        messageEl.classList.add('editing');
        const input = document.getElementById('messageInput');
        input.value = text;
        input.focus();
        document.getElementById('sendBtn').textContent = 'Сохранить';
    }

    cancelEditing() {
        if (!this.editingMessageId) return;
        const msgEl = document.querySelector(`.message[data-id="${this.editingMessageId}"]`);
        if (msgEl) msgEl.classList.remove('editing');
        this.editingMessageId = null;
        this.editingBackupText = null;
        this.editingNonceB64 = null;
        document.getElementById('messageInput').value = '';
        document.getElementById('sendBtn').textContent = this.config.translations?.send || 'Отправить';
    }

    startReply(messageId, senderName, text) {
        this.cancelEditing();
        this.replyingToMessageId = messageId;
        const preview = document.getElementById('replyPreview');
        if (!preview) return;
        preview.querySelector('.reply-to-name').textContent = senderName;
        preview.querySelector('.reply-to-text').textContent = text.substring(0, 100);
        preview.style.display = 'flex';
        preview.dataset.replyId = messageId;
        document.getElementById('messageInput').focus();
    }

    cancelReply() {
        this.replyingToMessageId = null;
        const preview = document.getElementById('replyPreview');
        if (preview) {
            preview.style.display = 'none';
            delete preview.dataset.replyId;
        }
    }

    async confirmDelete(messageId, messageEl) {
        if (!confirm('Удалить сообщение?')) return;
        const res = await fetch(`/messages/${messageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': this.config.csrfToken,
                Accept: 'application/json',
            },
        });
        const data = await res.json();
        if (data.status === 'success') {
            messageEl.remove();
            if (this.editingMessageId == messageId) this.cancelEditing();
            if (this.replyingToMessageId == messageId) this.cancelReply();
        }
    }
}