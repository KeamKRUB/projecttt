import { redirect } from '@sveltejs/kit';    // import redirect
import type { PageServerLoad } from './$types';
import apiClient, { withAuth } from '$lib/server/api-client.server'  // import withAuth

export const load: PageServerLoad = async ({ cookies }) => {

	const token = cookies.get('token'); // ดึงค่า token จาก cookies

	if (!token) {
		throw redirect(303, '/login');
	}
	try {
		// ส่ง token ไปกับ request ของ apiClient
		const response = await apiClient.get('/artists', withAuth(token));
		if (response.status === 200) {
			return {
				artists: response.data.data,
				pagination: response.data.meta
			};
		}
	} catch (err: any) {
		// ถ้า Token หมดอายุ หรือไม่มีสิทธิ์ (401, 403)
		if (err.response?.status === 401 || err.response?.status === 403) {
			// ลบ Cookie ทิ้ง แล้วกลับไปหน้า Login
			cookies.delete('token', { path: '/' });
			throw redirect(303, '/login');
		}

		throw err;
	}
}