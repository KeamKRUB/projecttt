<script lang="ts">
    import { enhance } from '$app/forms';
    import type { SubmitFunction } from '@sveltejs/kit';

    // รับค่า props เพื่อให้ปรับแต่ง CSS จากภายนอกได้ (มีค่าเริ่มต้นเป็น string ว่าง)
    let { class: className = '' } = $props();

    let isLoggingOut = $state(false);

    const handleLogout: SubmitFunction = () => {
        isLoggingOut = true;

        return async ({ update }) => {
            await update();
            isLoggingOut = false;
        };
    };
</script>

<form action="/logout" method="POST" use:enhance={handleLogout} class="inline-block m-0">
    <button
        type="submit"
        disabled={isLoggingOut}
        class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed {className}"
    >
        {#if isLoggingOut}
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            กำลังออก...
        {:else}
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
            ออกจากระบบ
        {/if}
    </button>
</form>