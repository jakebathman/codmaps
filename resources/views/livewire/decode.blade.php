<div class="bg-white p-6 sm:p-10 dark:bg-gray-900">

    <div class="mx-auto max-w-3xl">
        <div class="flex justify-between border-b border-gray-200 p-8">
            <div>
                <h2 class="text-3xl font-medium text-gray-900">Decode</h2>
            </div>
        </div>

        <div class="mx-auto mt-4 w-1/3 max-w-lg">
            <label
                for="codeInput"
                class="block text-sm/6 font-medium text-gray-900 dark:text-white"
            >Weapon Build Code</label>
            <div class="max-w-3xs mx-auto mt-2 w-full">
                <input
                    autofocus
                    id="codeInput"
                    type="text"
                    name="codeInput"
                    placeholder="A01-ABC12-XYZ89-1"
                    wire:model.live="codeInput"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base uppercase text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                />
            </div>
        </div>

        <div class="mx-auto flex w-full max-w-xl justify-around gap-6">
            <div class="flex flex-col gap-2 text-center">
                <div class="font-bold">Weapon</div>
                <div>{{ $this->buildCode?->weaponCode }}</div>
            </div>
            <div class="flex flex-col gap-2 text-center">
                <div class="font-bold">Attachments</div>
                <div>{{ $this->buildCode?->attachmentsCode }}</div>
            </div>
            <div class="flex flex-col gap-2 text-center">
                <div class="font-bold">Version</div>
                <div>{{ $this->buildCode?->versionCode }}</div>
            </div>
        </div>

        <div class="mx-auto flex w-full max-w-xl justify-around gap-6">
            <div class="flex flex-col gap-2 text-center">
                <div class="font-bold">Base 10</div>
                <div>{{ $this->buildCode?->base10 }}</div>
            </div>
            <div class="flex flex-col gap-2 text-center">
                <div class="font-bold">Valid?</div>
                <div>{{ $this->buildCode?->isValid ? 'Yes' : 'No' }}</div>
            </div>
        </div>

        <div class="mx-auto flex max-w-lg flex-col gap-2">
            <div class="flex items-baseline">
                <div class="w-32">Weapon</div>
                <div class="text-xl font-bold capitalize">{{ $this->weapon?->name }}</div>
            </div>
            <div class="flex items-baseline">
                <div class="w-32">Attachments</div>
                <div class="flex flex-col gap-1">
                    @foreach ($this->attachments as $attachment)
                        <div class="text-xl font-bold">{{ $attachment->name ?? 'Unknown' }}</div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 rounded-lg bg-gray-50 p-4 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                <pre><code>@json($this->buildCode?->attachmentIds, JSON_PRETTY_PRINT)</code></pre>
            </div>
            <div class="mt-6 rounded-lg bg-gray-50 p-4 text-sm text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                <pre><code>@json($this->attachments(), JSON_PRETTY_PRINT)</code></pre>
            </div>
        </div>

    </div>
</div>
