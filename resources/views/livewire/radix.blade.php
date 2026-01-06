<div>
    <h2>Weapon Code Testing</h2>
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row gap-2 items-center py-3">
            <div>
                <label
                    for="code"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >Build Code (base 35):</label>
                <input
                    type="text"
                    id="code"
                    wire:model.live="inputCode"
                    class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 uppercase"
                />
            </div>
            <div class="text-xl font-bold">
                <span class="sm:hidden">↓</span>
                <span class="hidden sm:block">→</span>
            </div>

            <div class="p-4 flex flex-col">
                <div class="text-sm text-gray-600 mb-2">Decoded (base 10):</div>
                <div class="font-semibold text-gray-900 min-h-10">{{ $this->decoded }}</div>
            </div>
            <div class="text-xl font-bold">
                <span class="sm:hidden">↓</span>
                <span class="hidden sm:block">→</span>
            </div>
            <div class="p-4 flex flex-col">
                <div class="text-sm text-gray-600 mb-2">Re-encoded (base 35):</div>
                <div class="font-semibold text-gray-900 min-h-10">{{ $this->encoded }}</div>
            </div>
        </div>
    </div>
    <div class="space-y-4">
        <div>
            <label
                for="codes"
                class="block text-sm font-medium text-gray-700 mb-2"
            >Codes for letters:</label>
            <textarea
                id="codes"
                rows="4"
                wire:model.live="codes"
                class="w-xl px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            ></textarea>
        </div>

        <div class="p-4 bg-gray-50 rounded-md border border-gray-200">
            <p class="text-sm text-gray-600">Letters ({{ strlen($this->letters) }}): <span class="font-semibold text-gray-900">{{ $this->letters }}</span></p>
        </div>
    </div>
</div>
