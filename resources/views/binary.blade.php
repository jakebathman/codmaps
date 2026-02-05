<x-layout-auth>
    <div class="w-fit flex flex-col gap-1 items-center">
        @foreach (App\Models\AttachmentID::all() as $a)
            <div class="self-end">
                <pre><code>{{ $a->binary }}</code></pre>
            </div>
        @endforeach
    </div>
</x-layout-auth>
