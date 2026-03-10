<script lang="ts">
	import { onMount, onDestroy } from 'svelte';
	import { getEcho } from '$lib/broadcasting/echo';

	let { data } = $props();
	let results = $state(data.initialResults);
	let echoInstance = null;

	onMount(() => {
		echoInstance = getEcho();
		if (echoInstance) {
			echoInstance.channel('dinner-poll').listen('DinnerVoteUpdated', (eventData) => {
				results = eventData.results;
			});
		}
	});

	onDestroy(() => {
		if (echoInstance) echoInstance.leaveChannel('dinner-poll');
	});
</script>

<div class="mx-auto min-h-screen max-w-6xl bg-gray-50 p-8 font-sans">
	<div class="mb-12 text-center">
		<h1 class="mb-4 text-5xl font-extrabold tracking-tight text-gray-800">สรุปผลโหวตมื้อเย็น 🏆</h1>
	</div>

	<div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
		{#each results as menu}
			<div
				class="flex min-h-[400px] flex-col rounded-3xl border-2 border-transparent bg-white p-6 shadow-lg transition-all duration-500 hover:border-blue-100"
			>
				<h2 class="mb-2 text-center text-2xl font-bold text-gray-800">{menu.title}</h2>
				<div
					class="mx-10 mb-8 rounded-full bg-blue-50 py-1 text-center text-lg font-bold text-blue-600"
				>
					{menu.voters?.length || 0} โหวต
				</div>

				<div class="flex flex-grow flex-wrap content-start items-start justify-center gap-3">
					{#if menu.voters && menu.voters.length > 0}
						{#each menu.voters as voter}
							<div
								title={voter}
								class="flex h-14 w-14 cursor-help items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-2xl font-bold text-white shadow-md transition-all duration-300 hover:z-10 hover:-translate-y-2 hover:scale-125 hover:shadow-xl"
							>
								{voter.toString().charAt(0).toUpperCase()}
							</div>
						{/each}
					{:else}
						<div class="mt-10 w-full text-center text-sm text-gray-300 italic">
							ยังไม่มีคนโหวตเมนูนี้
						</div>
					{/if}
				</div>
			</div>
		{/each}
	</div>
</div>