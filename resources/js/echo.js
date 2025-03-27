import Echo from 'laravel-echo';
//import { Reverb } from '@laravel/reverb';

// Инициализация до экспорта
window.Echo = new Echo({
    broadcaster: reverb,
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: true,
    enabledTransports: ['wss'],
});

// Уберите лишний экспорт
// window.Reverb = Reverb; // Это не требуется