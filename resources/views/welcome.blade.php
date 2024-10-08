<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>CoD Map Randomizer</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
    ></script>

</head>

<body class="h-screen w-full">
    <div x-data="randomizer" class="flex flex-col justify-center items-center h-full">
        <div class="text-3xl font-bold" x-text="selected?.name"></div>
    </div>
</body>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('randomizer', () => ({
            maps: @js($maps),
            selected: null,

            init() {
                console.log(this.maps)
                this.roll()
            },

            roll() {
                const randomIndex = Math.floor(Math.random() * this.maps.length)
                this.selected = this.maps[randomIndex]
            }
        }))
    })
</script>

</html>
