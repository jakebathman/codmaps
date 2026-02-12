<div class="bg-white p-6 sm:p-10 dark:bg-gray-900">

    <div class="mx-auto max-w-6xl">
        <select
            wire:model.live="operation"
            class="mb-4 w-full rounded border p-2"
        >
            <option value="subtract">Base 34 Subtraction (A - B)</option>
            <option value="add">Base 34 Addition (A + B)</option>
        </select>
        <div>
            <label
                class="mb-2 block text-sm font-medium"
                for="inputA"
            >Base 34 Input A</label>

            <input
                wire:model.live="inputA"
                type="text"
                placeholder="XYZ321"
                class="mb-4 w-full rounded border p-2 uppercase"
            />
        </div>

        <div>
            <label
                class="mb-2 block text-sm font-medium"
                for="inputB"
            >Base 34 Input B</label>
            <input
                wire:model.live="inputB"
                type="text"
                placeholder="ABC123"
                class="mb-4 w-full rounded border p-2 uppercase"
            />
        </div>
        <div>
            <h5 class="mb-2 text-lg font-medium capitalize">{{ $operation }} A from B:</h5>
            <div class="rounded border bg-gray-50 p-4 dark:bg-gray-800">
                {{ $resultBase10 }}
            </div>
            <div class="rounded border bg-gray-50 p-4 dark:bg-gray-800">
                {{ $resultBase34 }}
            </div>
        </div>
    </div>
</div>
