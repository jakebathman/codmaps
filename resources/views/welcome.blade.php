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

<body class="h-screen w-full dark:text-white dark:bg-gray-900">
    <div
        x-data="randomizer"
        class="flex flex-col justify-center items-center h-full p-6"
    >
        <div
            class="text-4xl lg:text-6xl font-bold text-center pb-6"
            x-text="selected?.name"
        ></div>
        <div class="rounded-xl overflow-hidden">
            <img
                :src="imageSrc"
                class="object-cover h-screen sm:h-80 w-[640px] lg:w-screen lg:h-[620px] mx-auto"
                alt="Map Image"
            />
        </div>
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
            },

            imageSrc() {
                return '/images/' + this.selected?.image
            }
        }))
    })
</script>

</html>
