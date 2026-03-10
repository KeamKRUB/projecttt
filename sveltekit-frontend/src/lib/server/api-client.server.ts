import axios from 'axios';
import { API_URL } from '$env/static/private'

const apiClient = axios.create({
    baseURL: `${API_URL}/api`,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
    timeout: 10000
});

export const withAuth = (token: string | undefined) => {
    if (!token) return {};
    return {
        headers: {
            Authorization: `Bearer ${token}`,
            Accept: 'application/json'
        }
    };
};

export default apiClient;
