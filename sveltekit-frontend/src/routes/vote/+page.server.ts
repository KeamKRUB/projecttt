import type { PageServerLoad } from './$types';
import apiClient from '$lib/server/api-client.server';

export const load: PageServerLoad = async function () {
    try {
        const response = await apiClient.get('/dinner-poll');

        return {
            initialResults: response.data
        };
    } catch (error) {
        console.error('ไม่สามารถดึงข้อมูลผลโหวตตั้งต้นได้:', error);
        return {
            initialResults: []
        };
    }
};