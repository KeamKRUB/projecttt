<script lang="ts">
    import { enhance } from '$app/forms';
    import type { ActionData, SubmitFunction } from './$types';

    let { form }: { form: ActionData } = $props();

    let isLoading = $state(false);

    const handleLogin: SubmitFunction = () => {
        isLoading = true;

        return async ({ update }) => {
            await update();
            isLoading = false;
        };
    };
</script>

<div class="min-h-[80vh] flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-sm border border-slate-200">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Welcome Back</h1>
            <p class="text-slate-500">กรุณาเข้าสู่ระบบเพื่อดำเนินการต่อ</p>
        </div>

        <form method="POST" use:enhance={handleLogin} class="space-y-5">

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                    Email Address
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    disabled={isLoading}
                    value={form?.email ?? ''} class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                    placeholder="you@example.com"
                />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                    Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    disabled={isLoading}
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                />
            </div>

            {#if form?.message}
                <div class="p-3 bg-red-50 text-red-600 text-sm rounded-lg border border-red-100 flex items-start gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{form.message}</span>
                </div>
            {/if}

            <button
                type="submit"
                disabled={isLoading}
                class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium hover:bg-blue-700 transition-colors disabled:bg-blue-300 flex justify-center items-center gap-2 mt-2"
            >
                {#if isLoading}
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Signing in...
                {:else}
                    Sign In
                {/if}
            </button>

        </form>

    </div>
</div>