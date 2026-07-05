export function b64ToU8(b64) {
    return Uint8Array.from(atob(b64), c => c.charCodeAt(0));
}

export function u8ToB64(u8) {
    return btoa(String.fromCharCode(...u8));
}