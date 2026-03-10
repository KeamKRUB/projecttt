import { redirect } from '@sveltejs/kit';
import apiClient, { withAuth } from '$lib/server/api-client.server';
import type { Actions } from './$types';

export const actions: Actions = {
    default: async ({ cookies }) => {
        const token = cookies.get('token');

        if (token) {
            try {
                await apiClient.delete('/api/revoke', {}, withAuth(token));
            } catch (err) {
                console.error('Logout API failed, but continuing to clear cookie...');
            }
        }
        cookies.delete('token', { path: '/' });
        cookies.delete('user_info', { path: '/' });
        throw redirect(303, '/login');
    }
};