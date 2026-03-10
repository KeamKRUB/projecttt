<script lang="ts">
  import { page } from '$app/stores';
  import {onMount} from 'svelte';
  import type {PageProps} from './$types';
import { globalCounter } from '$lib/stores/counter.svelte.ts';
import { theme } from '$lib/stores/theme.svelte.ts';  
import ThemeToggle from '$lib/components/theme-toggle.svelte'
import LogoutButton from '$lib/components/logout-button.svelte';
  const { brand } = $props()

  onMount(() => {
    const counter = localStorage.getItem('counter');
    globalCounter.set(counter == null ? 0:Number(counter));
  })
  
	function getLinkClass(path: string) {
		const currentPath = $page.url.pathname;
		const baseClass = 'px-4 py-2 rounded-full text-sm font-medium transition-all duration-200';

		const isActive = path === '/' ? currentPath === '/' : currentPath.startsWith(path);

		const activeClass = isActive
			? 'hover:text-blue-200 transition-colors duration-200'
			: 'text-blue-200';

		return `${baseClass} ${activeClass}`;
	}

		onMount(() => {   // ตรวจสอบว่า document มี class 'dark' หรือไม่ ถ้ามี ให้ set ที่ store/theme เป็น true
		if (document.documentElement.classList.contains('dark')) {
			theme.set(true);
		} else {
		  theme.set(false);  // ถ้าไม่มี ให้ set ที่ store/theme เป็น false
		}
	});

	$effect(() => {    // ตรวจสอบว่า theme มีการเปลี่ยนค่าหรือไม่ ถ้ามีให้แสดงผลเป็น theme ที่เปลี่ยน และเก็บค่าที่ localStorage (สำหรับการ refresh หน้า)
		const html = document.documentElement;

		if (theme.isDark) {
			html.classList.add('dark');
			localStorage.setItem('theme', 'dark');
		} else {
			html.classList.remove('dark');
			localStorage.setItem('theme', 'white');
		}
	});

	let user = $derived($page.data.user);
</script>

<nav class="sticky top-0 bg-blue-600 text-white dark:bg-gray-800">
	<div class="max-w-6xl mx-auto">
		<div class="flex items-center justify-between h-16">

			<a href="/" class="text-2xl font-bold text-blue-200">
				<span class="text-white">Web</span><span class="text-blue-200">Tech</span>
			</a>

			<ul class="flex items-center gap-8 font-medium">
				<li>
					<a href="/" class={getLinkClass('/')}>
						Home
					</a>
				</li>
				<li>
					<a href="/artists" class={getLinkClass('/artists')}>
						Artists
					</a>
				</li>
				<li>
					<a href="/about" class={getLinkClass('/about')}>
						About
					</a>
				</li>
				<li>
					<a href="/shop" class={getLinkClass('/shop')}>
						Shop
					</a>
				</li>
				<li>
					<a href="/emoji-hub" class={getLinkClass('/emoji-hub')}>
						Emoji
					</a>
				</li>
			</ul>

            {globalCounter.count}
					<div>
			<ThemeToggle></ThemeToggle>
		</div>
		            <div class="flex items-center gap-4">
                        {#if user}
                                    <div>{user.name}</div>
                                    <LogoutButton></LogoutButton>
                            {:else}
                                    <a
                                        href="/login"
                                        class="hidden text-sm font-semibold text-slate-500 hover:text-slate-900 md:block"
                                    >
                                            Log in
                                    </a>
                            {/if}
                    </div>
		</div>
	</div>
</nav>
