<div class="bg-white dark:bg-gray-900 p-6 sm:p-10">

    <div class="max-w-3xl mx-auto">
        <div class="p-8 border-b border-gray-200 flex justify-between">
            <div>
                <h2 class="text-3xl font-medium text-gray-900">Decode</h2>
            </div>
        </div>

        <div class="mt-4 w-1/3 max-w-lg mx-auto">
            <label
                for="codeInput"
                class="block text-sm/6 font-medium text-gray-900 dark:text-white"
            >Weapon Build Code</label>
            <div class="mt-2 w-full max-w-3xs mx-auto">
                <input
                    autofocus
                    id="codeInput"
                    type="text"
                    name="codeInput"
                    placeholder="A01-ABC12-XYZ89-1"
                    wire:model.live="codeInput"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                />
            </div>
        </div>

        <div class="flex justify-around gap-6 w-full max-w-xl mx-auto">
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

        <div class="flex flex-col gap-2 max-w-lg mx-auto">
            <div class="flex items-baseline">
                <div class="w-32">Weapon</div>
                <div class="font-bold text-xl capitalize">{{ $this->weapon?->name }}</div>
            </div>
            <div class="flex items-baseline">
                <div class="w-32">Attachments</div>
                @json($this->buildCode?->attachmentIds)
                <div class="flex flex-col gap-1">
                    @foreach ($this->attachments as $attachment)
                        <div class="font-bold text-xl">{{ $attachment->name }}</div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
</div>
