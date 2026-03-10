<script lang="ts">
	let userName = $state('');
	let isSubmitting = $state(false);

	let validationErrors = $state({});
	let errorMessage = $state('');

	const menus = [
		{ id: 1, title: '🍜 กินก๋วยเตี๋ยวหกคน', color: 'bg-orange-500' },
		{ id: 2, title: '🍗 ข้าวมันไก่มานี่มา', color: 'bg-yellow-500' },
		{ id: 3, title: '🥩 บุฟเฟต์ปิ้งย่าง', color: 'bg-red-600' },
		{ id: 4, title: '🍲 ชาบูไม่อั้น', color: 'bg-green-600' }
	];

	async function submitVote(choiceId) {
		validationErrors = {};
		errorMessage = '';
		isSubmitting = true;

		try {
			const res = await fetch('/api/vote', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ name: userName.trim(), choice: choiceId })
			});

			const data = await res.json();

			if (!res.ok) {
				if (res.status === 422 && data.errors) {
					validationErrors = data.errors;
				} else {
					errorMessage = data.message;
				}
				return;
			}

			alert('โหวตสำเร็จ!');
			userName = '';
		} catch (error) {
			errorMessage = 'เกิดข้อผิดพลาดในการเชื่อมต่อ';
		} finally {
			isSubmitting = false;
		}
	}
</script>

<div class="mx-auto flex min-h-screen max-w-md flex-col justify-center p-6 font-sans">
	<div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-lg">
		<h1 class="mb-6 text-center text-2xl font-bold text-gray-800">โหวตมื้อเย็น 🍽️</h1>

		{#if errorMessage}
			<div class="mb-4 rounded-lg bg-red-100 p-3 text-center text-sm text-red-700">
				{errorMessage}
			</div>
		{/if}

		<div class="mb-6">
			<label class="mb-2 block font-medium text-gray-600" for="name">ชื่อของคุณ</label>
			<input
				id="name"
				type="text"
				bind:value={userName}
				placeholder="ใส่ชื่อที่นี่..."
				class="w-full rounded-xl border px-4 py-3 text-center text-lg transition-all outline-none
                       {validationErrors.name
					? 'border-red-500 bg-red-50 focus:ring-red-500'
					: 'border-gray-300 focus:ring-blue-500'}"
			/>
			{#if validationErrors.name}
				<p class="mt-2 text-sm text-red-500">{validationErrors.name[0]}</p>
			{/if}
		</div>

		<div class="grid grid-cols-2 gap-4">
			{#each menus as menu}
				<button
					disabled={isSubmitting}
					onclick={() => submitVote(menu.id)}
					class="{menu.color} flex flex-col items-center justify-center gap-2 rounded-xl py-4 font-bold text-white shadow-sm transition-all hover:scale-105 hover:shadow-md active:scale-95 disabled:opacity-50"
				>
					<span class="text-2xl">{menu.title.split(' ')[0]}</span>
					<span class="text-sm">{menu.title.split(' ')[1]}</span>
				</button>
			{/each}
		</div>

		{#if validationErrors.choice}
			<p class="mt-4 text-center text-sm text-red-500">{validationErrors.choice[0]}</p>
		{/if}
	</div>
</div>