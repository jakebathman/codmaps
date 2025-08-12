@extends('layouts.app')

@section('content')
    <div class="h-dvh w-full bg-gradient-to-b from-indigo-950/5 via-purple-950/5 to-pink-950/5 dark:text-purple-100 dark:bg-gradient-to-b dark:from-indigo-950/30 dark:via-purple-950/30 dark:to-pink-950/30">

        <div
            x-cloak
            x-data="randomizer"
            class="flex flex-col justify-center items-center h-full gap-6 py-6 px-6 md:px-12 lg:px-24"
        >
            {{-- Top filter buttons --}}
            <div class="w-full flex flex-col-reverse gap-4 sm:flex-row sm:justify-between z-20">
                <div class="w-full flex-row h-10 gap-y-2 flex justify-center sm:justify-start sm:w-2/3 flex-wrap">
                    <template
                        x-for="filter in filters"
                        class="inline-block"
                    >
                        <button
                            @click="toggleFilter(filter)"
                            x-text="filter"
                            class="rounded-lg font-semibold bg-purple-100 text-gray-600 dark:bg-white/5 dark:text-purple-300/50 text-md sm:text-lg tracking-wide px-1.5 py-0.5 sm:px-3 sm:py-2 mr-2"
                            :class="{ 'bg-purple-950 text-white dark:bg-purple-950 dark:text-white': filterIsActive(filter) }"
                        ></button>
                    </template>
                </div>
                <div class="w-full h-10 flex justify-center sm:justify-end flex-wrap sm:w-1/3">
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
                                'bg-bo6 text-gray-900 dark:bg-gray-900 dark:text-bo6': gameIsActive(gameId) &&
                                    gameId == 'bo6',
                                'bg-mwiii text-white dark:bg-gray-900 dark:text-mwiii': gameIsActive(gameId) &&
                                    gameId == 'mwiii',
                            }"
                        ></button>
                    </template>
                </div>
            </div>

            {{-- Map name/error text --}}
            <div class="flex flex-1 flex-col justify-center items-center gap-6">
                <div
                    x-show="noPossibleMaps"
                    class="text-2xl lg:text-4xl font-bold text-center pb-6"
                >No maps available with the selected filters</div>
                <div
                    class="text-4xl lg:text-6xl font-bold text-center pb-6"
                    x-text="selected?.name"
                ></div>

                {{-- Map image --}}
                <div
                    class="rounded-xl relative h-full sm:h-auto"
                    x-show="!noPossibleMaps"
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
                        class="object-cover absolute top-0 -z-10 h-full w-full blur-sm rounded-xl"
                        alt="Image ambient blur"
                    />
                </div>
            </div>

            {{-- Buttons below map image --}}
            <div class="flex w-full justify-between relative pb-6">

                {{-- Hide map button --}}
                <div class="mt-10 sm:mt-auto flex flex-col justify-center items-center gap-3 min-w-36">
                    <button
                        @click="hideMap"
                        class="mt-10 sm:mt-auto rounded-lg font-semibold bg-pink-950/10 text-pink-950 dark:bg-white/10 dark:text-pink-100 text-xl tracking-wide px-4 py-2.5"
                        :class="{ 'opacity-30 cursor-not-allowed': noPossibleMaps }"
                        :disabled="noPossibleMaps"
                    >Hide Map</button>
                    <div class="text-gray-500 dark:text-gray-300 text-center relative">
                        <div>
                            <span x-text="hiddenMaps.length"></span> maps hidden
                        </div>
                        <div
                            x-show="hiddenMaps.length > 0"
                            class="w-full text-xs text-gray-500 dark:text-gray-300 cursor-pointer absolute -bottom-4 left-0"
                            @click="unhideAllMaps"
                        >Unhide all</div>
                    </div>
                </div>

                {{-- fav button --}}
                <div class="group mt-10 sm:mt-none flex flex-col justify-center items-center gap-3 flex-1">
                    <button
                        @click="fav"
                        x-cloak
                        class="relative mt-10 sm:mt-none rounded-lg font-semibold bg-none text-pink-500/20 dark:bg-none dark:text-pink-100 text-xl tracking-wide px-4 py-2.5"
                        :class="{
                            'text-pink-500 bg-pink-500/10': !noPossibleMaps && isFavorite(),
                            'text-pink-500/20': !noPossibleMaps && !isFavorite(),
                            'text-gray-400 opacity-30 cursor-not-allowed': noPossibleMaps,
                        }"
                        :disabled="noPossibleMaps"
                    >
                        <span
                            class="absolute -top-1 -right-0 p-0.5 text-base"
                            x-text="favoriteMaps.length"
                        ></span>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            :fill="isFavorite() ? `currentColor` : `none`"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            :stroke="isFavorite() ? `none` : `currentColor`"
                            class="size-8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"
                            />
                        </svg>
                    </button>
                    <div class="text-pink-500/35 dark:text-pink-300 text-center text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-[100ms] min-w-24 flex flex-col justify-center items-center">
                        <span x-show="noPossibleMaps"><span class="flex">&nbsp;</span></span>
                        <span x-show="isFavorite() && !noPossibleMaps"><span class="flex sm:hidden">Unfav</span><span class="hidden sm:flex">Remove Fav</span></span>
                        <span x-show="!isFavorite() && !noPossibleMaps"><span class="flex sm:hidden">Fav</span><span class="hidden sm:flex">Add Favorite</span></span>
                    </div>
                </div>

                {{-- roll button --}}
                <div class="mt-10 sm:mt-auto flex flex-col justify-center items-center gap-3 min-w-36">
                    <button
                        @click="roll"
                        class="mt-10 sm:mt-auto rounded-lg font-semibold bg-purple-950/20 text-purple-950 dark:bg-white/20 dark:text-purple-100 text-xl tracking-wide px-4 py-2.5"
                    >Re-roll</button>
                    <div class="text-gray-500 dark:text-gray-300"><span x-text="filteredMaps.length"></span> <span x-text="filteredMaps.length == 1 ? 'map' : 'maps'"></span> possible
                    </div>
                </div>
            </div>
            <div class="absolute right-0 bottom-0 p-1.5 text-xs text-gray-100 dark:text-gray-200/5">{{ $commitHash }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('randomizer', () => ({
                allMaps: @js($maps),
                maps: @js($maps),
                allFilters: @js($filters),
                games: @js($games),
                selected: null,
                selectedFilters: Alpine.$persist([]).as('selectedFilters'),
                selectedGames: Alpine.$persist(['bo6']).as('selectedGames'),
                filteredMaps: [],
                noPossibleMaps: false,
                lastSelected: null,
                filtersAreExclusive: true,
                defaultGame: 'bo6',
                hiddenMaps: Alpine.$persist([]).as('hiddenMaps'),
                favoriteMaps: Alpine.$persist([]).as('favoriteMaps'),

                init() {
                    // filter this.maps to only include maps that are in the selected games
                    this.filterMapsForGame(this.selectedGames[0])

                    this.filteredMaps = this.maps

                    this.filterMaps()
                    this.roll()
                },

                get filters() {
                    return this.allFilters[this.selectedGames[0] || this.defaultGame]
                },

                fav() {
                    if (this.selected) {
                        if (this.favoriteMaps.includes(this.selected.name)) {
                            this.favoriteMaps = this.favoriteMaps.filter(map => map !== this.selected.name)
                        } else {
                            this.favoriteMaps.push(this.selected.name)
                        }
                    }

                    this.filterMaps()
                },

                isFavorite() {
                    return this.favoriteMaps.includes(this.selected.name)
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
                        console.log('Hidden map:', this.selected)
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
                    return this.selectedGames.includes(value)
                },

                filterMapsForGame(value) {
                    this.selectedGames = [value]

                    // Check if the current filter is valid for this game
                    const validFilters = this.filters.filter(filter => {
                        return this.maps.some(map => map.games.includes(value) && map.filters.includes(filter))
                    })

                    // Remove any items in selectedFilters that aren't in validFilters
                    this.selectedFilters = this.selectedFilters.filter(filter => validFilters.includes(filter) || filter === '❤️')

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
                        this.filteredMaps = this.maps.filter(map => map.games.some(g => this
                                .selectedGames
                                .includes(g)))
                            .filter(map => !this.hiddenMaps.includes(map.name))
                    } else {
                        this.filteredMaps = this.maps.filter(map => {
                                // If the filter is for fav maps, filter that separately
                                if (this.selectedFilters.includes('❤️')) {
                                    return this.favoriteMaps.includes(map.name)
                                }

                                let intersection = map.filters.filter(x => this.selectedFilters
                                    .includes(x));
                                return intersection.length == this.selectedFilters.length && map
                                    .games.some(g => this.selectedGames.includes(g))
                            })
                            .filter(map => !this.hiddenMaps.includes(map.name))
                    }

                    // If no maps are available, set selected to null
                    if (this.filteredMaps.length === 0) {
                        console.log('No maps available')
                        this.selected = null
                        this.noPossibleMaps = true
                    } else {
                        console.log('Maps available')
                        this.noPossibleMaps = false
                    }

                },

                imageSrc() {
                    return '/images/' + this.selected?.image
                }
            }))
        })
    </script>
@endsection
