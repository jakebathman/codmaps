<x-layout>
    <div class="h-full w-full">
        @if (session()->has('github_user'))
            <a
                href="{{ route('maps') }}"
                title="Admin"
                aria-label="Admin"
                class="fixed right-3 top-3 z-50 inline-flex items-center justify-center rounded-full bg-white/70 text-gray-700 shadow-sm backdrop-blur hover:bg-white dark:bg-white/10 dark:text-gray-100 dark:hover:bg-white/20"
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
            class="flex h-full flex-col items-center justify-between gap-6 px-6 py-6 md:px-12 lg:px-24"
        >
            @if (session('error'))
                <div class="w-full rounded-lg bg-red-100 px-4 py-2 text-center font-semibold text-red-900 sm:w-2/3">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Top filter buttons --}}
            <div class="z-20 flex w-full flex-col-reverse gap-4 sm:flex-row sm:justify-between">
                <div class="flex h-10 w-full flex-row flex-wrap justify-center gap-y-2 sm:justify-start">
                    <template
                        x-for="filter in filters"
                        class="inline-block"
                    >
                        <button
                            @click="toggleFilter(filter)"
                            {{-- x-text="filter" --}}
                            class="text-md mr-2 rounded-lg border border-transparent bg-purple-100 px-1.5 py-0.5 font-semibold tracking-wide text-gray-600 sm:px-3 sm:py-2 sm:text-lg dark:bg-white/5 dark:text-purple-300/50"
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
                <div class="flex h-10 w-full flex-wrap justify-center sm:justify-end">
                    <template
                        x-for="(game, gameId) in games"
                        class="inline-block"
                    >
                        <button
                            @click="filterMapsForGame(gameId)"
                            x-text="game.name"
                            class="text-md mr-1 rounded-lg px-1.5 py-0.5 font-semibold tracking-wide sm:mr-2 sm:px-4 sm:py-2.5 sm:text-lg"
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

            <div class="z-10 flex flex-col items-start justify-center gap-4 sm:flex-row">
                {{-- Map name/error text --}}
                <div class="flex w-full flex-1 flex-col items-center justify-center gap-2 sm:gap-6">
                    <div
                        class="rotate-90 text-center text-4xl font-bold lg:text-6xl"
                        :class="{
                            'hidden': noPossibleMaps === false,
                        }"
                        :style="`color: ${activeGameColor()}`"
                    >:(</div>
                    <div
                        x-show="noPossibleMaps === false"
                        class="text-center text-4xl font-bold lg:text-6xl"
                        x-text="selected?.name"
                    ></div>

                    {{-- Map image --}}
                    <template x-if="noPossibleMaps === true">
                        <div class="relative w-full rounded-xl sm:h-auto">
                            <!-- Invisible image for sizing purposes -->
                            <img
                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 9'%3E%3C/svg%3E"
                                class="z-10 mx-auto aspect-video h-full rounded-xl object-cover opacity-0 sm:h-80 md:h-[360px] lg:h-[420px]"
                                alt=""
                            />

                            <div
                                class="absolute inset-0 flex flex-col items-center justify-center bg-clip-text text-center text-4xl text-transparent"
                                style="background-image: radial-gradient(at 87.93103448275862% 9.375%, #d70000 0px, transparent 50%), radial-gradient(at 32.543103448275865% 21.041666666666668%, #2b9bff 0px, transparent 50%), radial-gradient(at 22.844827586206897% 89.16666666666667%, #e38535 0px, transparent 50%), radial-gradient(at 68.75% 86.66666666666667%, #2b9bff 0px, transparent 50%), radial-gradient(at 5.711206896551724% 34.791666666666664%, #e38535 0px, transparent 50%), radial-gradient(at 99.13793103448276% 56.875%, #e38535 0px, transparent 50%), radial-gradient(at 51.400862068965516% 52.916666666666664%, #121523 0px, transparent 50%);"
                            >
                                No maps available
                                <wb />with selected filters
                            </div>
                        </div>

                    </template>
                    <template x-if="noPossibleMaps === false">

                        <div class="relative h-full rounded-xl sm:h-auto">
                            <img
                                :src="imageSrc"
                                class="z-10 mx-auto aspect-video h-full w-full rounded-xl object-cover sm:h-80 md:h-[360px] lg:h-[420px]"
                                alt="Map Image"
                            />
                            <img
                                :src="imageSrc"
                                x-ref="bgBlurImage"
                                x-show="selected?.image"
                                class="blur-xs absolute top-0 -z-10 h-full w-full rounded-xl object-cover"
                                alt="Image ambient blur-sm"
                            />
                        </div>
                    </template>
                </div>

                {{-- Buttons below map image --}}
                <div class="relative mt-auto flex h-full w-full justify-between gap-2 self-end sm:w-auto sm:flex-col-reverse sm:gap-6">
                    {{-- Hide map button --}}
                    <div class="flex min-w-24 flex-col items-center justify-center gap-1 text-sm sm:min-w-32 sm:text-base">
                        <button
                            @click="hideMap"
                            class="cursor-pointer rounded-lg bg-purple-100 px-4 py-2.5 font-semibold tracking-wide text-gray-600 sm:text-lg dark:bg-white/5 dark:text-purple-300/50"
                            :class="{ 'opacity-30 cursor-not-allowed': noPossibleMaps === true }"
                            :disabled="noPossibleMaps === true"
                        >Hide Map</button>
                        <div class="relative text-center text-gray-500 dark:text-gray-300">
                            <div>
                                <span x-text="hiddenMaps[selectedGame]?.length || 0"></span> hidden
                            </div>
                            <div
                                x-show="hiddenMaps[selectedGame]?.length > 0"
                                class="absolute -bottom-4 left-0 w-full cursor-pointer text-xs text-gray-500 sm:relative sm:bottom-0 dark:text-gray-300"
                                @click="unhideAllMaps"
                            >Unhide all</div>
                        </div>
                    </div>

                    {{-- fav button --}}
                    <div class="group flex flex-1 flex-col items-center justify-center gap-1">
                        <button
                            @click="fav"
                            x-cloak
                            class="relative cursor-pointer rounded-lg bg-none px-4 py-2.5 font-semibold tracking-wide text-pink-500/20 sm:text-lg dark:bg-none dark:text-pink-100"
                            :class="{
                                'text-pink-500 bg-pink-500/10': noPossibleMaps === false && isFavorite(),
                                'text-pink-500/20': noPossibleMaps === false && !isFavorite(),
                                'text-gray-400 opacity-30 cursor-not-allowed': noPossibleMaps === true,
                            }"
                            :disabled="noPossibleMaps === true"
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
                        <div class="flex min-w-24 flex-col items-center justify-center text-center text-sm text-pink-500/35 opacity-0 transition-opacity duration-100 group-hover:opacity-100 dark:text-pink-300">
                            <span x-show="noPossibleMaps === true"><span class="flex">&nbsp;</span></span>
                            <span x-show="isFavorite() && noPossibleMaps === false"><span class="flex sm:hidden">Unfav</span><span class="hidden sm:flex">Remove Fav</span></span>
                            <span x-show="!isFavorite() && noPossibleMaps === false"><span class="flex sm:hidden">Fav</span><span class="hidden sm:flex">Add Favorite</span></span>
                        </div>
                    </div>

                    {{-- roll button --}}
                    <div class="flex min-w-24 flex-col items-center justify-center gap-1 text-sm sm:min-w-32 sm:text-base">
                        <button
                            @click="roll"
                            class="cursor-pointer rounded-lg px-4 py-2.5 font-semibold tracking-wide sm:text-lg"
                            :class="{
                                'bg-bo6 text-gray-900 dark:bg-bo6/10 dark:text-bo6 dark:border dark:border-bo6': gameIsActive('bo6'),
                                'bg-mwiii text-white dark:bg-mwiii-800/50 dark:text-white/90 dark:border dark:border-mwiii-700': gameIsActive('mwiii'),
                                'bg-bo7 text-white dark:bg-bo7/10 dark:text-bo7 dark:border dark:border-bo7': gameIsActive('bo7'),
                            }"
                        >Re-roll</button>
                        <div class="text-center text-gray-500 dark:text-gray-300"><span x-text="filteredMaps.length"></span> <span x-text="filteredMaps.length == 1 ? 'map' : 'maps'"></span><span class="hidden sm:block"> possible</span>
                        </div>
                    </div>

                    {{-- Add the same top padding (flex-col-reverse, so at the end) as the image gets with its title, to center the buttons correctly on the image --}}
                    <div class="hidden text-center text-4xl font-bold sm:block lg:text-6xl">&nbsp;</div>

                </div>
                <div class="absolute bottom-0 right-0 p-1.5 text-xs text-gray-100 dark:text-gray-200/5">{{ $commitHash }}
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex flex-col items-center justify-center gap-3 p-0 text-sm text-gray-500 sm:flex-row sm:gap-7 dark:text-gray-400">
                <div class="flex flex-col items-center gap-0.5">
                    <div class="text-sm">
                        Made with 💜 (and no AI) by <a
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
                selectedGame: Alpine.$persist('bo7').as('selectedGame'),
                lastSelected: null,
                filtersAreExclusive: true,
                defaultGame: 'bo7',
                hiddenMaps: Alpine.$persist({}).as('hiddenMaps'),
                favoriteMaps: Alpine.$persist([]).as('favoriteMaps'),

                init() {
                    // filter this.maps to only include maps that are in the selected games
                    this.filterMapsForGame(this.selectedGame)
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
                },

                isFavorite() {
                    return this.favoriteMaps.includes(this.selected?.name)
                },

                get favoriteMapsForGame() {
                    return this.favoriteMaps.filter(fav => {
                        return this.maps.some(map => map.name === fav && map.game === this.selectedGame)
                    })
                },

                get noPossibleMaps() {
                    return this.filteredMaps.length === 0
                },

                roll() {
                    if (this.noPossibleMaps) {
                        this.selected = null
                        return
                    }

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
                        if (!this.hiddenMaps[this.selectedGame]) {
                            this.hiddenMaps[this.selectedGame] = []
                        }
                        this.hiddenMaps[this.selectedGame].push(this.selected.name)

                        this.roll()
                    }
                },

                unhideAllMaps() {
                    this.hiddenMaps[this.selectedGame] = []
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

                    this.roll()
                },

                get filteredMaps() {
                    let maps;

                    // No filters are selected, so use all maps
                    if (this.selectedFilters.length === 0) {
                        maps = this.maps.filter(map => map.game === this.selectedGame)
                            .filter(map => !this.hiddenMaps[this.selectedGame]?.includes(map.name))
                    } else {
                        maps = this.maps.filter(map => {
                                // If the filter is for fav maps, filter that separately
                                if (this.selectedFilters.includes('favs')) {
                                    return this.favoriteMaps.includes(map.name) && map.game === this.selectedGame
                                }

                                let intersection = map.filters.filter(x => this.selectedFilters
                                    .includes(x));
                                return intersection.length == this.selectedFilters.length && map
                                    .game === this.selectedGame
                            })
                            .filter(map => !this.hiddenMaps[this.selectedGame]?.includes(map.name))
                    }

                    // If no maps are available, set selected to null
                    if (maps.length === 0) {
                        this.selected = null
                    }

                    return maps;
                },

                imageSrc() {
                    let updatedAt = this.selected?.updated_at ? '?v=' + new Date(this.selected.updated_at).getTime() : ''
                    return 'https://images.randomcod.com/' + this.selected?.image + '?t=' + updatedAt
                }
            }))
        })
    </script>
</x-layout>
