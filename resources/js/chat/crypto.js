import { b64ToU8 } from './utils.js';

export class ChatCrypto {
    constructor(chatKey) {
        this.chatKey = chatKey;
    }

    decrypt(encryptedPayload) {
        if (!this.chatKey) return '[Ключ загружается...]';
        try {
            const [nonceB64, cipherB64] = encryptedPayload.split('|');
            const decrypted = nacl.secretbox.open(
                b64ToU8(cipherB64),
                b64ToU8(nonceB64),
                this.chatKey
            );
            return decrypted
                ? new TextDecoder().decode(decrypted)
                : '[Ошибка]';
        } catch {
            return '[Ошибка]';
        }
    }
}