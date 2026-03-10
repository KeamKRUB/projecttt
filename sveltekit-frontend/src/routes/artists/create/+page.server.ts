import { fail, redirect } from '@sveltejs/kit';
import type { Actions } from './$types';
import apiClient, { withAuth } from '$lib/server/api-client.server';

export const actions: Actions = {
	default: async ({ request, cookies }) => {
		const token = cookies.get('token'); // ดึงค่า token จาก cookies

		if (!token) {
			throw redirect(303, '/login');
		}

		const formData = await request.formData();

		// สร้าง FormData ใหม่ (สำคัญมาก)
		const newFormData = new FormData();

		for (const [key, value] of formData.entries()) {
			newFormData.append(key, value as Blob | string);
		}

		try {
			const response = await apiClient.post(
				'/artists',
				newFormData,
				withAuth(token),
				{
					headers: {
						'Content-Type': 'multipart/form-data'
					}
				}
			);

			const newArtistId = response.data.data.id;

			throw redirect(303, `/artists/${newArtistId}`);

		} catch (error: any) {
			if (error.status === 303) throw error;

			if (error.response?.status === 422) {
				const data = Object.fromEntries(formData);
				delete data.image;

				return fail(422, {
					errors: error.response.data.errors,
					data
				});
			}

			console.error(error);

			return fail(500, {
				message: 'เกิดข้อผิดพลาดในการบันทึกข้อมูล'
			});
		}
	}
};
