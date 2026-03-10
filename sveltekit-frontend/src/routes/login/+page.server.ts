import { fail, redirect } from '@sveltejs/kit';
import apiClient from '$lib/server/api-client.server';
import type { Actions } from './$types';

export const actions: Actions = {
	default: async ({ request, cookies }) => {
		const formData = await request.formData();
		const email = formData.get('email');
		const password = formData.get('password');

		try {
			const response = await apiClient.post('/login', { email, password });

			const token = response.data.token;
			const user = response.data.user;

			cookies.set('token', token, {
				path: '/',
				httpOnly: true,
				sameSite: 'lax',
				secure: process.env.NODE_ENV === 'production',
				maxAge: 60 * 60 * 24 * 7 // 7 days
			});

			const userBase64 = Buffer.from(JSON.stringify(user)).toString('base64');
			cookies.set('user_info', userBase64, { path: '/', maxAge: 60 * 60 * 24 * 7 });

		} catch (err: any) {
			return fail(401, { message: 'อีเมลหรือรหัสผ่านไม่ถูกต้อง' });
		}

		throw redirect(303, '/artists');
	}
};