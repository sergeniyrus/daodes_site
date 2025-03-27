import Echo from 'laravel-echo';
import './echo';

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
});

window.Echo.channel('notifications')
    .listen('NewMessageNotification', (e) => {
        let audio = new Audio('/sounds/notification.mp3');
        audio.play();
        console.log(e.message);
    });
