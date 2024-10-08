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

<body class="h-dvh w-full bg-white dark:bg-black">
    <div
        class="h-dvh w-full bg-gradient-to-b from-indigo-950/20 via-purple-950/20 to-pink-950/20 dark:text-purple-100 dark:bg-gradient-to-b dark:from-indigo-950/50 dark:via-purple-950/50 dark:to-pink-950/50">

        <div
            x-data="randomizer"
            class="flex flex-col justify-center items-center h-full p-6"
        >
            <div
                class="text-4xl lg:text-6xl font-bold text-center pb-6 mt-auto"
                x-text="selected?.name"
            ></div>
            <div class="rounded-xl overflow-hidden">
                <img
                    :src="imageSrc"
                    class="object-cover h-screen sm:h-80 w-[640px] md:w-screen md:h-[420px] lg:w-screen lg:h-[520px] mx-auto"
                    alt="Map Image"
                />
            </div>
            <button
                @click="roll"
                class="mt-10 sm:mt-auto rounded-lg font-semibold bg-purple-950/20 text-purple-950 dark:bg-white/20 dark:text-purple-100 text-xl tracking-wide px-4 py-2.5 "
            >Re-roll</button>
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
