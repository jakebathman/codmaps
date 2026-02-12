<x-layout-auth>
    <div class="flex w-fit flex-col items-center gap-1">
        @foreach (App\Models\AttachmentID::all() as $a)
            <div class="self-end">
                <pre><code>{{ $a->binary }}</code></pre>
            </div>
        @endforeach
    </div>
</x-layout-auth>
