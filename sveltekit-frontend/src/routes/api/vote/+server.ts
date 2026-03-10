import { json } from '@sveltejs/kit';
import apiClient from '$lib/server/api-client.server.ts';
import axios from 'axios';

export async function POST({ request }) {
    const body = await request.json();

    try {
        const laravelResponse = await apiClient.post('/dinner-poll', body);

        return json(laravelResponse.data);

    } catch (err) {
        if (axios.isAxiosError(err) && err.response) {
            return json(
                {
                    message: err.response.data.message || 'ข้อมูลไม่ถูกต้อง',
                    errors: err.response.data.errors || {}
                },
                { status: err.response.status }
            );
        }

        console.error('API Vote Error:', err);
        return json(
            { message: 'ไม่สามารถเชื่อมต่อระบบโหวตได้ กรุณาลองใหม่' },
            { status: 500 }
        );
    }
}