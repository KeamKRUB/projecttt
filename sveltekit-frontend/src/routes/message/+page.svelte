<script lang="ts">
	import { onMount, onDestroy } from 'svelte';
	import { getEcho } from '$lib/broadcasting/echo';

	let messages = $state([]);
	let echoInstance = null;

	onMount(() => {
		echoInstance = getEcho();

		if (echoInstance) {
			console.log('กำลังเชื่อมต่อ WebSocket...');

			echoInstance.channel('test-channel').listen('TestMessageEvent', (eventData) => {
				console.log('ได้รับข้อมูล:', eventData);
				messages.push(eventData.message);
			});
		}
	});

	onDestroy(() => {
		if (echoInstance) {
			echoInstance.leaveChannel('test-channel');
		}
	});
</script>

<div class="mx-auto max-w-3xl p-6 font-sans md:p-10">
	<h2 class="mb-6 flex items-center gap-3 text-2xl font-bold text-gray-800">
		<span class="text-3xl">📡</span> ทดสอบรับข้อความจาก Laravel Reverb
	</h2>

	<div class="min-h-[250px] rounded-xl border border-gray-200 bg-gray-50 p-6 shadow-inner">
		{#if messages.length === 0}
			<div class="flex h-full min-h-[200px] items-center justify-center text-gray-400 italic">
				กำลังรอรับข้อความ...
			</div>
		{:else}
			<ul class="space-y-4">
				{#each messages as msg, index}
					<li
						class="animate-fade-in flex flex-col rounded-lg border-l-4 border-green-500 bg-white p-4 shadow-sm transition-all duration-300"
					>
						<span class="mb-1 text-xs font-semibold tracking-wider text-gray-400 uppercase">
							ข้อความที่ {index + 1}
						</span>
						<span class="text-lg text-gray-800">
							{msg}
						</span>
					</li>
				{/each}
			</ul>
		{/if}
	</div>
</div>

<style>
	@keyframes fadeIn {
		from {
			opacity: 0;
			transform: translateY(10px);
		}
		to {
			opacity: 1;
			transform: translateY(0);
		}
	}
	.animate-fade-in {
		animation: fadeIn 0.3s ease-out forwards;
	}
</style>