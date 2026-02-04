<div class="bg-white dark:bg-gray-900 p-6 sm:p-10">

    <div class="max-w-6xl mx-auto">
        <select
            wire:model.live="operation"
            class="border p-2 rounded w-full mb-4"
        >
            <option value="subtract">Base 34 Subtraction (A - B)</option>
            <option value="add">Base 34 Addition (A + B)</option>
        </select>
        <div>
            <label
                class="block text-sm font-medium mb-2"
                for="inputA"
            >Base 34 Input A</label>

            <input
                wire:model.live="inputA"
                type="text"
                placeholder="XYZ321"
                class="border p-2 rounded w-full mb-4 uppercase"
            />
        </div>

        <div>
            <label
                class="block text-sm font-medium mb-2"
                for="inputB"
            >Base 34 Input B</label>
            <input
                wire:model.live="inputB"
                type="text"
                placeholder="ABC123"
                class="border p-2 rounded w-full mb-4 uppercase"
            />
        </div>
        <div>
            <h5 class="text-lg font-medium mb-2 capitalize">{{ $operation }} A from B:</h5>
            <div class="p-4 border rounded bg-gray-50 dark:bg-gray-800">
                {{ $resultBase10 }}
            </div>
            <div class="p-4 border rounded bg-gray-50 dark:bg-gray-800">
                {{ $resultBase34 }}
            </div>
        </div>
    </div>
</div>
