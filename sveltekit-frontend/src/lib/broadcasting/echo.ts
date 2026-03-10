import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { env } from '$env/dynamic/public';

if (typeof window !== 'undefined') {
    window.Pusher = Pusher;
}

let echoInstance = null;

export const getEcho = () => {
    if (typeof window === 'undefined') return null;

    if (!echoInstance) {
        echoInstance = new Echo({
            broadcaster: 'reverb',
            key: env.PUBLIC_REVERB_APP_KEY,
            wsHost: env.PUBLIC_REVERB_HOST,
            wsPort: env.PUBLIC_REVERB_PORT ?? 6001,
            wssPort: env.PUBLIC_REVERB_PORT ?? 6001,
            forceTLS: (env.PUBLIC_REVERB_SCHEME ?? 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
            disableStats: true,
        });
    }

    return echoInstance;
};