import type { Handle } from '@sveltejs/kit';
import apiClient, { withAuth } from '$lib/server/api-client.server';

export const handle: Handle = async ({ event, resolve }) => {
    const token = event.cookies.get('token');
    const userInfoCookie = event.cookies.get('user_info');

    event.locals.user = null;

    if (token && userInfoCookie) {
        try {
            const jsonString = Buffer.from(userInfoCookie, 'base64').toString('utf-8');
            event.locals.user = JSON.parse(jsonString);

        } catch (err) {
            event.cookies.delete('token', { path: '/' });
            event.cookies.delete('user_info', { path: '/' });
        }
    }

    // ให้ Request ไปทำงานที่ส่วนต่อไป
    return resolve(event);
};