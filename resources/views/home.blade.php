<x-layout>
    <div class="h-full w-full">
        @if (session()->has('github_user'))
            <a
                href="{{ route('maps') }}"
                title="Admin"
                aria-label="Admin"
                class="fixed top-3 right-3 z-50 inline-flex items-center justify-center rounded-full bg-white/70 text-gray-700 shadow-sm backdrop-blur hover:bg-white dark:bg-white/10 dark:text-gray-100 dark:hover:bg-white/20"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    class="h-9 w-9 p-2"
                >
                    <path
                        fill-rule="evenodd"
                        d="M11.079 2.25h1.842a.75.75 0 0 1 .746.683l.167 1.998a7.47 7.47 0 0 1 1.9.79l1.64-1.126a.75.75 0 0 1 .983.13l1.302 1.302a.75.75 0 0 1 .13.983l-1.126 1.64c.347.58.607 1.202.79 1.9l1.998.167a.75.75 0 0 1 .683.746v1.842a.75.75 0 0 1-.683.746l-1.998.167a7.47 7.47 0 0 1-.79 1.9l1.126 1.64a.75.75 0 0 1-.13.983l-1.302 1.302a.75.75 0 0 1-.983.13l-1.64-1.126a7.47 7.47 0 0 1-1.9.79l-.167 1.998a.75.75 0 0 1-.746.683h-1.842a.75.75 0 0 1-.746-.683l-.167-1.998a7.47 7.47 0 0 1-1.9-.79l-1.64 1.126a.75.75 0 0 1-.983-.13l-1.302-1.302a.75.75 0 0 1-.13-.983l1.126-1.64a7.47 7.47 0 0 1-.79-1.9l-1.998-.167A.75.75 0 0 1 2.25 12.921v-1.842a.75.75 0 0 1 .683-.746l1.998-.167a7.47 7.47 0 0 1 .79-1.9l-1.126-1.64a.75.75 0 0 1 .13-.983l1.302-1.302a.75.75 0 0 1 .983-.13l1.64 1.126a7.47 7.47 0 0 1 1.9-.79l.167-1.998a.75.75 0 0 1 .746-.683ZM12 8.25a3.75 3.75 0 1 0 0 7.5 3.75 3.75 0 0 0 0-7.5Z"
                        clip-rule="evenodd"
                    />
                </svg>
            </a>
        @endif

        <div
            x-cloak
            x-data="randomizer"
            class="flex flex-col justify-between items-center h-full gap-6 py-6 px-6 md:px-12 lg:px-24"
        >
            @if (session('error'))
                <div class="w-full sm:w-2/3 text-center rounded-lg bg-red-100 text-red-900 px-4 py-2 font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Top filter buttons --}}
            <div class="w-full flex flex-col-reverse gap-4 sm:flex-row sm:justify-between z-20">
                <div class="w-full flex-row h-10 gap-y-2 flex justify-center sm:justify-start flex-wrap">
                    <template
                        x-for="filter in filters"
                        class="inline-block"
                    >
                        <button
                            @click="toggleFilter(filter)"
                            {{-- x-text="filter" --}}
                            class="rounded-lg font-semibold bg-purple-100 text-gray-600 dark:bg-white/5 dark:text-purple-300/50 text-md sm:text-lg tracking-wide px-1.5 py-0.5 sm:px-3 sm:py-2 mr-2 border border-transparent"
                            :class="{ 'bg-purple-950 text-white dark:bg-purple-950 dark:text-white dark:border dark:border-white/50': filterIsActive(filter) }"
                        >
                            <!-- SVG for favorites filter -->
                            <template x-if="filter === 'favs'">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    :fill="favIconFill"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    :stroke="favIconStroke"
                                    class="size-6"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"
                                    />
                                </svg>
                            </template>

                            <!-- Text for other filters -->
                            <template x-if="filter !== 'favs'">
                                <span x-text="filter"></span>
                            </template>
                        </button>
                    </template>
                </div>
                <div class="w-full h-10 flex justify-center sm:justify-end flex-wrap">
                    <template
                        x-for="(game, gameId) in games"
                        class="inline-block"
                    >
                        <button
                            @click="filterMapsForGame(gameId)"
                            x-text="game.name"
                            class="rounded-lg font-semibold text-md sm:text-lg tracking-wide px-1.5 py-0.5 mr-1 sm:px-4 sm:py-2.5 sm:mr-2"
                            :class="{
                                'bg-purple-100 text-gray-600 dark:bg-white/5 dark:text-purple-300/50': !gameIsActive(
                                    gameId),
                                'bg-bo6 text-gray-900 dark:bg-bo6/10 dark:text-bo6 dark:border dark:border-bo6': gameIsActive(gameId) &&
                                    gameId == 'bo6',
                                'bg-mwiii text-white dark:bg-mwiii-800/50 dark:text-white/90 dark:border dark:border-mwiii-700': gameIsActive(gameId) &&
                                    gameId == 'mwiii',
                                'bg-bo7 text-white dark:bg-bo7/10 dark:text-bo7 dark:border dark:border-bo7': gameIsActive(gameId) &&
                                    gameId == 'bo7',
                            }"
                        ></button>
                    </template>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-start z-10">
                {{-- Map name/error text --}}
                <div class="flex flex-1 flex-col justify-center items-center gap-2 sm:gap-6 w-full">
                    <div
                        x-show="noPossibleMaps"
                        class="text-4xl lg:text-6xl font-bold text-center rotate-90"
                        :style="`color: ${activeGameColor()}`"
                    >:(</div>
                    <div
                        x-show="!noPossibleMaps"
                        class="text-4xl lg:text-6xl font-bold text-center"
                        x-text="selected?.name"
                    ></div>

                    {{-- Map image --}}
                    <template x-if="noPossibleMaps">
                        <div class="rounded-xl relative sm:h-auto w-full">
                            <!-- Invisible image for sizing purposes -->
                            <img
                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 9'%3E%3C/svg%3E"
                                class="object-cover z-10 h-full aspect-video sm:h-80 md:h-[360px] lg:h-[420px] mx-auto rounded-xl opacity-0"
                                alt=""
                            />

                            <div
                                class="absolute inset-0 flex flex-col justify-center items-center text-4xl text-transparent bg-clip-text text-center"
                                style="background-image: radial-gradient(at 87.93103448275862% 9.375%, #d70000 0px, transparent 50%), radial-gradient(at 32.543103448275865% 21.041666666666668%, #2b9bff 0px, transparent 50%), radial-gradient(at 22.844827586206897% 89.16666666666667%, #e38535 0px, transparent 50%), radial-gradient(at 68.75% 86.66666666666667%, #2b9bff 0px, transparent 50%), radial-gradient(at 5.711206896551724% 34.791666666666664%, #e38535 0px, transparent 50%), radial-gradient(at 99.13793103448276% 56.875%, #e38535 0px, transparent 50%), radial-gradient(at 51.400862068965516% 52.916666666666664%, #121523 0px, transparent 50%);"
                            >
                                No maps available
                                <wb />with selected filters
                            </div>
                        </div>

                    </template>
                    <template x-if="!noPossibleMaps">

                        <div class="rounded-xl relative h-full sm:h-auto">
                            <img
                                :src="imageSrc"
                                class="object-cover z-10 h-full aspect-video sm:h-80 md:h-[360px] lg:h-[420px] mx-auto rounded-xl"
                                alt="Map Image"
                            />
                            <img
                                :src="imageSrc"
                                x-ref="bgBlurImage"
                                x-show="selected?.image"
                                class="object-cover absolute top-0 -z-10 h-full w-full blur-xs rounded-xl"
                                alt="Image ambient blur-sm"
                            />
                        </div>
                    </template>
                </div>

                {{-- Buttons below map image --}}
                <div class="flex gap-2 sm:gap-6 w-full h-full mt-auto sm:w-auto sm:flex-col-reverse justify-between relative self-end">
                    {{-- Hide map button --}}
                    <div class="flex flex-col justify-center items-center gap-3 min-w-24 sm:min-w-32 text-sm sm:text-base">
                        <button
                            @click="hideMap"
                            class="rounded-lg font-semibold bg-purple-100 text-gray-600 dark:bg-white/5 dark:text-purple-300/50 sm:text-lg tracking-wide px-4 py-2.5 cursor-pointer"
                            :class="{ 'opacity-30 cursor-not-allowed': noPossibleMaps }"
                            :disabled="noPossibleMaps"
                        >Hide Map</button>
                        <div class="text-gray-500 dark:text-gray-300 text-center relative">
                            <div>
                                <span x-text="hiddenMaps.length"></span> hidden
                            </div>
                            <div
                                x-show="hiddenMaps.length > 0"
                                class="w-full text-xs text-gray-500 dark:text-gray-300 cursor-pointer absolute -bottom-4 left-0 sm:relative sm:bottom-0"
                                @click="unhideAllMaps"
                            >Unhide all</div>
                        </div>
                    </div>

                    {{-- fav button --}}
                    <div class="group flex flex-col justify-center items-center gap-1 flex-1">
                        <button
                            @click="fav"
                            x-cloak
                            class="relative rounded-lg font-semibold bg-none text-pink-500/20 dark:bg-none dark:text-pink-100 sm:text-lg tracking-wide px-4 py-2.5 cursor-pointer"
                            :class="{
                                'text-pink-500 bg-pink-500/10': !noPossibleMaps && isFavorite(),
                                'text-pink-500/20': !noPossibleMaps && !isFavorite(),
                                'text-gray-400 opacity-30 cursor-not-allowed': noPossibleMaps,
                            }"
                            :disabled="noPossibleMaps"
                        >
                            <span
                                class="absolute -top-1 right-0 p-0.5 text-base"
                                x-text="favoriteMapsForGame.length"
                            ></span>

                            {{-- Heart SVG --}}
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                :fill="isFavorite() ? activeGameColor() : `none`"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                :stroke="isFavorite() ? `none` : activeGameColor()"
                                class="size-8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"
                                />
                            </svg>
                        </button>
                        <div class="text-pink-500/35 dark:text-pink-300 text-center text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-100 min-w-24 flex flex-col justify-center items-center">
                            <span x-show="noPossibleMaps"><span class="flex">&nbsp;</span></span>
                            <span x-show="isFavorite() && !noPossibleMaps"><span class="flex sm:hidden">Unfav</span><span class="hidden sm:flex">Remove Fav</span></span>
                            <span x-show="!isFavorite() && !noPossibleMaps"><span class="flex sm:hidden">Fav</span><span class="hidden sm:flex">Add Favorite</span></span>
                        </div>
                    </div>

                    {{-- roll button --}}
                    <div class="flex flex-col justify-center items-center gap-3 min-w-24 sm:min-w-32 text-sm sm:text-base">
                        <button
                            @click="roll"
                            class="rounded-lg font-semibold  sm:text-lg tracking-wide px-4 py-2.5 cursor-pointer"
                            :class="{
                                'bg-bo6 text-gray-900 dark:bg-bo6/10 dark:text-bo6 dark:border dark:border-bo6': gameIsActive('bo6'),
                                'bg-mwiii text-white dark:bg-mwiii-800/50 dark:text-white/90 dark:border dark:border-mwiii-700': gameIsActive('mwiii'),
                                'bg-bo7 text-white dark:bg-bo7/10 dark:text-bo7 dark:border dark:border-bo7': gameIsActive('bo7'),
                            }"
                        >Re-roll</button>
                        <div class="text-gray-500 dark:text-gray-300 text-center"><span x-text="filteredMaps.length"></span> <span x-text="filteredMaps.length == 1 ? 'map' : 'maps'"></span><span class="hidden sm:block"> possible</span>
                        </div>
                    </div>

                    {{-- Add the same top padding (flex-col-reverse, so at the end) as the image gets with its title, to center the buttons correctly on the image --}}
                    <div class="hidden sm:block text-4xl lg:text-6xl font-bold text-center">&nbsp;</div>

                </div>
                <div class="absolute right-0 bottom-0 p-1.5 text-xs text-gray-100 dark:text-gray-200/5">{{ $commitHash }}
                </div>
            </div>

            {{-- Footer --}}
            <div class="p-0 flex flex-col sm:flex-row justify-center items-center gap-3 sm:gap-7 text-sm text-gray-500 dark:text-gray-400">
                <div class="flex flex-col items-center gap-0.5">
                    <div>Made with 💜 (and no AI) by <a
                            href="https://twitter.com/JakeBathman"
                            target="_blank"
                            class="text-purple-500 dark:text-purple-400"
                        >JakeBathman</a></div>
                    <div class="text-xs">Not affiliated with Call of Duty or Activision</div>
                </div>
                <script
                    type='text/javascript'
                    src='https://storage.ko-fi.com/cdn/widget/Widget_2.js'
                ></script>
                <script type='text/javascript'>
                    kofiwidget2.init('Buy Me a Red Bull', '#9a69c2', 'L4L2DC39I');
                    kofiwidget2.draw();
                </script>
            </div>
        </div>

        {{-- Dark mode toggle --}}
        <div class="fixed bottom-3 left-3 z-50">

            <flux:dropdown
                x-data
                align="end"
            >
                <flux:button
                    variant="subtle"
                    square
                    class="group"
                    aria-label="Preferred color scheme"
                >
                    <flux:icon.sun
                        x-show="$flux.appearance === 'light'"
                        variant="mini"
                        class="text-zinc-500 dark:text-white"
                    />
                    <flux:icon.moon
                        x-show="$flux.appearance === 'dark'"
                        variant="mini"
                        class="text-zinc-500 dark:text-white"
                    />
                    <flux:icon.moon
                        x-show="$flux.appearance === 'system' && $flux.dark"
                        variant="mini"
                    />
                    <flux:icon.sun
                        x-show="$flux.appearance === 'system' && ! $flux.dark"
                        variant="mini"
                    />
                </flux:button>

                <flux:menu>
                    <flux:menu.item
                        icon="sun"
                        x-on:click="$flux.appearance = 'light'"
                    >Light</flux:menu.item>
                    <flux:menu.item
                        icon="moon"
                        x-on:click="$flux.appearance = 'dark'"
                    >Dark</flux:menu.item>
                    <flux:menu.item
                        icon="computer-desktop"
                        x-on:click="$flux.appearance = 'system'"
                    >System</flux:menu.item>
                </flux:menu>
            </flux:dropdown>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('randomizer', () => ({
                allMaps: @js($maps),
                maps: @js($maps),
                allFilters: @js($filters),
                games: @js($games),
                selected: null,
                selectedFilters: Alpine.$persist([]).as('selectedFilters'),
                selectedGame: Alpine.$persist('bo6').as('selectedGame'),
                filteredMaps: [],
                noPossibleMaps: false,
                lastSelected: null,
                filtersAreExclusive: true,
                defaultGame: 'bo6',
                hiddenMaps: Alpine.$persist([]).as('hiddenMaps'),
                favoriteMaps: Alpine.$persist([]).as('favoriteMaps'),

                init() {
                    // filter this.maps to only include maps that are in the selected games
                    this.filterMapsForGame(this.selectedGame)

                    this.filteredMaps = this.maps

                    this.filterMaps()
                    this.roll()
                },

                get filters() {
                    return this.allFilters[this.selectedGame || this.defaultGame]
                },

                fav() {
                    if (this.selected) {
                        if (this.isFavorite()) {
                            this.favoriteMaps = this.favoriteMaps.filter(map => map !== this.selected.name)
                        } else {
                            this.favoriteMaps.push(this.selected.name)
                        }
                    }

                    this.filterMaps()
                },

                isFavorite() {
                    return this.favoriteMaps.includes(this.selected?.name)
                },

                get favoriteMapsForGame() {
                    return this.favoriteMaps.filter(fav => {
                        return this.maps.some(map => map.name === fav && map.game === this.selectedGame)
                    })
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

                    // Force background image to re-paint (fix a safari issue with blur clipping)
                    this.$nextTick(() => {
                        this.forceRepaint()
                    })
                },

                hideMap() {
                    if (this.selected) {
                        this.hiddenMaps.push(this.selected.name)

                        this.filterMaps()
                        this.roll()
                    }
                },

                unhideAllMaps() {
                    this.hiddenMaps = []
                    this.filterMaps()
                    this.roll()
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

                gameIsActive(value) {
                    return this.selectedGame === value
                },

                favIconFill() {
                    // If inactive, heart is not filled
                    if (!this.filterIsActive('favs')) {
                        return 'none';
                    }

                    return this.activeGameColor()
                },

                favIconStroke() {
                    // If inactive, heart is outlined
                    if (this.filterIsActive('favs')) {
                        return 'none';
                    }

                    return this.activeGameColor()
                },

                activeGameColor() {
                    return {
                        'bo6': 'var(--color-bo6)',
                        'mwiii': 'var(--color-mwiii)',
                        'bo7': 'var(--color-bo7)',
                    } [this.selectedGame] || 'currentColor'
                },

                filterMapsForGame(value) {
                    this.selectedGame = value

                    // Check if the current filter is valid for this game
                    const validFilters = this.filters.filter(filter => {
                        return this.maps.some(map => map.game === value && map.filters.includes(filter))
                    })

                    // Remove any items in selectedFilters that aren't in validFilters
                    this.selectedFilters = this.selectedFilters.filter(filter => validFilters.includes(filter) || filter === 'favs')

                    this.filterMaps()
                    this.roll()
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

                    this.filterMaps()

                    this.roll()
                },

                filterMaps() {
                    // No filters are selected, so use all maps
                    if (this.selectedFilters.length === 0) {
                        this.filteredMaps = this.maps.filter(map => map.game === this.selectedGame)
                            .filter(map => !this.hiddenMaps.includes(map.name))
                    } else {
                        this.filteredMaps = this.maps.filter(map => {
                                // If the filter is for fav maps, filter that separately
                                if (this.selectedFilters.includes('favs')) {
                                    return this.favoriteMaps.includes(map.name) && map.game === this.selectedGame
                                }

                                let intersection = map.filters.filter(x => this.selectedFilters
                                    .includes(x));
                                return intersection.length == this.selectedFilters.length && map
                                    .game === this.selectedGame
                            })
                            .filter(map => !this.hiddenMaps.includes(map.name))
                    }

                    // If no maps are available, set selected to null
                    if (this.filteredMaps.length === 0) {
                        this.selected = null
                        this.noPossibleMaps = true
                    } else {
                        this.noPossibleMaps = false
                    }

                },

                imageSrc() {
                    let updatedAt = this.selected?.updated_at ? '?v=' + new Date(this.selected.updated_at).getTime() : ''
                    return 'https://images.randomcod.com/' + this.selected?.image + '?t=' + updatedAt
                }
            }))
        })
    </script>
</x-layout>
