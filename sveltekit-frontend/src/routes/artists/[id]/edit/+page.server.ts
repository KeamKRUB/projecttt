import { fail, error, redirect } from '@sveltejs/kit';
import type { Actions, PageServerLoad } from './$types';
import apiClient, { withAuth } from '$lib/server/api-client.server';

export const load: PageServerLoad = async ({ params, cookies, locals }) => {
	const id = params.id;
	const token = cookies.get('token');

	if (!locals.user) {
		throw redirect(303, '/login');
	}

	if (locals.user.role !== 'ADMIN') {
		throw redirect(303, '/artists');
	}
	if (!token) {
		throw redirect(303, '/login');
	}
	try {
		const response = await apiClient.get(`/artists/${id}`, withAuth(token));
		return {
			artist: response.data.data
		};
	} catch (err: any) {
		throw error(404, 'ไม่พบข้อมูลศิลปิน');
	}
};

export const actions: Actions = {
	default: async ({ request, params, cookies }) => {
		const token = cookies.get('token'); // ดึงค่า token จาก cookies

		if (!token) {
			throw redirect(303, '/login');
		}

		const id = params.id;
		const formData = await request.formData();

		const newFormData = new FormData();

		for (const [key, value] of formData.entries()) {

			if (key === 'image') {
				const file = value as File;

				if (!file || file.size === 0) {
					continue;
				}
			}

			newFormData.append(key, value as Blob | string);
		}

		newFormData.append('_method', 'PUT');

		try {
			await apiClient.post(
				`/artists/${id}`,
				newFormData,
				withAuth(token)
			);

		} catch (err: any) {
			if (err.response?.status === 422) {
				const data = Object.fromEntries(formData);
				delete data.image;

				return fail(422, {
					errors: err.response.data.errors,
					data
				});
			}
		}

		throw redirect(303, `/artists/${id}`);
	}
};
