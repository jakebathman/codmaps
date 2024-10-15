<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="/apple-touch-icon.png"
    >
    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="/favicon-32x32.png"
    >
    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="/favicon-16x16.png"
    >
    <link
        rel="manifest"
        href="/site.webmanifest"
    >

    <title>CoD Map Randomizer</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
    ></script>

    @vite('resources/js/app.js')


</head>

<body class="h-dvh w-full bg-white dark:bg-black">
    <div
        class="h-dvh w-full bg-gradient-to-b from-indigo-950/5 via-purple-950/5 to-pink-950/5 dark:text-purple-100 dark:bg-gradient-to-b dark:from-indigo-950/30 dark:via-purple-950/30 dark:to-pink-950/30">

        <div
            x-data="randomizer"
            class="flex flex-col justify-center items-center h-full gap-6 py-6 px-6 md:px-12 lg:px-24"
        >
            <div class="w-full h-10">
                <template
                    x-for="filter in filters"
                    class="inline-block"
                >
                    <button
                        @click="toggleFilter(filter)"
                        x-text="filter"
                        class="rounded-lg font-semibold bg-purple-950/5 text-gray-950/40 dark:bg-white/5 dark:text-purple-300/50 text-md sm:text-xl tracking-wide px-1.5 py-0.5 mr-1 sm:px-4 sm:py-2.5 sm:mr-2"
                        :class="{ 'bg-purple-950 text-gray-50 dark:bg-purple-950 dark:text-white': filterIsActive(filter) }"
                    ></button>
                </template>
            </div>
            <div
                x-show="noPossibleMaps"
                class="text-2xl lg:text-4xl font-bold text-center pb-6 mt-auto"
            >No maps available with the selected filters</div>
            <div
                class="text-4xl lg:text-6xl font-bold text-center pb-6 mt-auto"
                x-text="selected?.name"
            ></div>
            <div
                class="rounded-xl relative h-full sm:h-auto"
                x-show="!noPossibleMaps"
                style="will-change: filter;"
            >
                <img
                    :src="imageSrc"
                    class="object-cover z-10 h-full sm:h-80 w-[640px] md:w-screen md:h-[420px] lg:w-screen lg:h-[520px] mx-auto rounded-xl"
                    alt="Map Image"
                />
                <img
                    :src="imageSrc"
                    x-ref="bgBlurImage"
                    x-show="selected?.image"
                    class="object-cover absolute top-0 -z-10 h-full w-full blur2 rounded-xl"
                    alt="Image ambient blur"
                />
            </div>
            <div class="mt-10 sm:mt-auto flex flex-col justify-center gap-3">
                <button
                    @click="roll"
                    class="mt-10 sm:mt-auto rounded-lg font-semibold bg-purple-950/20 text-purple-950 dark:bg-white/20 dark:text-purple-100 text-xl tracking-wide px-4 py-2.5"
                >Re-roll</button>
                <div class="text-gray-500 dark:text-gray-300"><span x-text="filteredMaps.length"></span> maps possible
                </div>
            </div>
            <div class="text-xs text-gray-200">{{ $commitHash }}</div>
        </div>
    </div>
</body>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('randomizer', () => ({
            maps: @js($maps),
            filters: @js($filters),
            games: @js($games),
            selected: null,
            selectedFilters: [],
            filteredMaps: [],
            noPossibleMaps: false,
            lastSelected: null,
            filtersAreExclusive: true,

            init() {
                console.log(this.maps)
                this.filteredMaps = this.maps
                this.roll()
            },

            roll() {
                if (this.filteredMaps.length === 0) {
                    this.selected = null
                    this.noPossibleMaps = true
                    return
                }
                this.noPossibleMaps = false

                // Try 5 times to get a different map than last time
                let randomIndex;
                for (let tries = 0; tries < 5; tries++) {
                    randomIndex = Math.floor(Math.random() * this.filteredMaps.length)
                    if (this.filteredMaps[randomIndex] !== this.lastSelected) {
                        break;
                    }
                }

                this.selected = this.filteredMaps[randomIndex]
                this.lastSelected = this.selected
                console.debug('selected', this.selected)

                // Force background image to re-paint (fix a safari issue with blur clipping)
                this.$nextTick(() => {
                    this.forceRepaint()
                })
            },

            forceRepaint() {
                // wait before re-enabling the blur and paint
                let img = this.$refs.bgBlurImage;

                img.style.display = 'none';
                setTimeout(() => {
                    img.offsetHeight;
                    // img.style.filter = 'blur(40px)';
                    img.style.display = '';
                }, 75);
            },


            filterIsActive(value) {
                return this.selectedFilters.includes(value)
            },

            toggleFilter(value) {

                if (this.selectedFilters.includes(value)) {
                    this.selectedFilters = this.selectedFilters.filter(filter => filter !==
                        value)
                } else {
                    if (this.filtersAreExclusive === true) {
                        // Only one filter active at a time
                        this.selectedFilters = [value]
                    } else {
                        // Add the filter to the list
                        this.selectedFilters.push(value)
                    }
                }

                console.debug('selectedFilters', this.selectedFilters)

                // No filters are selected, so use all maps
                if (this.selectedFilters.length === 0) {
                    this.filteredMaps = this.maps
                } else {

                    console.log('filter', value)
                    this.filteredMaps = this.maps.filter(map => {
                        let intersection = map.filters.filter(x => this.selectedFilters
                            .includes(x));
                        return intersection.length == this.selectedFilters.length
                    })
                }
                console.log('filtered', this.filteredMaps)

                this.roll()
            },

            imageSrc() {
                return '/images/' + this.selected?.image
            }
        }))
    })
</script>

</html>
