import { error, redirect, fail } from '@sveltejs/kit';
import type { PageServerLoad, Actions } from './$types';
import apiClient, { withAuth } from '$lib/server/api-client.server';
export const load: PageServerLoad = async ({ params, cookies }) => {
	const id = params.id;
	const token = cookies.get('token');

	if (!token) {
		throw redirect(303, '/login');
	}
	try {
		const response = await apiClient.get(`/artists/${id}`, withAuth(token));
		if (response.status === 200) {
			return {
				artist: response.data.data
			};
		}

	} catch (err: any) {
		if (err.response.status == 404) {
			error(404, {
				message: `Artist ID ${id} Not Found`
			});
		} else {
			throw err;
		}
	}
};

export const actions: Actions = {
	delete: async ({ params, cookies }) => {
		const token = cookies.get('token'); // ดึงค่า token จาก cookies

		if (!token) {
			throw redirect(303, '/login');
		}

		try {
			await apiClient.delete(`/artists/${params.id}`, withAuth(token));

		} catch (err: any) {
			return fail(500, {
				message: 'ไม่สามารถลบข้อมูลได้ กรุณาลองใหม่'
			});
		}
		throw redirect(303, '/artists');
	}
};