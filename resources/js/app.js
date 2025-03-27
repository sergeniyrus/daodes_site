import './bootstrap';
import './echo';

import Alpine from 'alpinejs';
import Echo from 'laravel-echo';

// Инициализация Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Логирование переменных окружения
console.log('Инициализация Echo...');
console.log('Reverb Key:', import.meta.env.VITE_REVERB_APP_KEY);
console.log('Reverb Host:', import.meta.env.VITE_REVERB_HOST);
console.log('Reverb Port:', import.meta.env.VITE_REVERB_PORT);
console.log('Reverb Scheme:', import.meta.env.VITE_REVERB_SCHEME);

// Инициализация Laravel Echo для Reverb
window.Echo = new Echo({
    broadcaster: 'reverb', // Используем Reverb
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME === 'https'),
    enabledTransports: ['ws', 'wss'],
});

console.log('Echo initialized'); // Логирование для отладки


window.Echo.channel('chat')
    .listen('NewMessage', (data) => {
        console.log('New message:', data.message);
    });
    

// // Звук для уведомлений
// const notificationSound = new Audio('/sounds/notification.mp3');
// console.log('Звук уведомления загружен:', notificationSound.src);

// // Подписка на канал чата
// if (window.currentChatId) {
//     console.log('Подписка на канал:', `chat.${window.currentChatId}`); // Исправлено на `chat.{chatId}`
//     window.Echo.private(`chat.${window.currentChatId}`) // Исправлено на `chat.{chatId}`
//         .listen('.new.message', (data) => { // Исправлено на `.new.message`
//             console.log('Новое сообщение получено в app.js:', data);

//             // Воспроизводим звук, если сообщение не от текущего пользователя
//             if (data.senderId !== window.currentUserId) {
//                 console.log('Воспроизведение звука уведомления');
//                 notificationSound.play();
//             }

//             // Добавляем сообщение в интерфейс
//             addMessageToChat({
//                 text: data.message,
//                 senderId: data.senderId,
//                 timestamp: data.timestamp,
//             });
//         });
// } else {
//     console.error('Ошибка: window.currentChatId не определен');
// }

// // Функция для добавления сообщения в интерфейс
// function addMessageToChat(message) {
//     const chatContainer = document.getElementById('chat-messages');
//     if (chatContainer) {
//         console.log('Добавление сообщения в интерфейс:', message);
//         const messageElement = document.createElement('div');
//         messageElement.classList.add('message');
//         messageElement.textContent = `${message.senderId}: ${message.text}`;
//         chatContainer.appendChild(messageElement);
//         chatContainer.scrollTop = chatContainer.scrollHeight; // Прокрутка вниз
//     } else {
//         console.error('Ошибка: контейнер сообщений не найден');
//     }
// }