@extends('layouts.app')

@section('content')
    <div class="h-dvh w-full bg-gradient-to-b from-indigo-950/5 via-purple-950/5 to-pink-950/5 dark:text-purple-100 dark:bg-gradient-to-b dark:from-indigo-950/30 dark:via-purple-950/30 dark:to-pink-950/30">

        <div
            x-data="randomizer"
            class="flex flex-col justify-center items-center h-full gap-6 py-6 px-6 md:px-12 lg:px-24"
        >
            <div class="w-full flex flex-col-reverse gap-4 sm:flex-row sm:justify-between">
                <div class="w-full flex-row h-10 gap-y-2 flex justify-center sm:justify-start sm:w-1/2 flex-wrap">
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
                <div class="w-full h-10 flex justify-center sm:justify-end flex-wrap sm:w-1/2">
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

            <div class="flex w-full justify-between">
                <div class="mt-10 sm:mt-auto flex flex-col justify-center items-center gap-3">
                    <button
                        @click="hideMap"
                        class="mt-10 sm:mt-auto rounded-lg font-semibold bg-pink-950/10 text-pink-950 dark:bg-white/10 dark:text-pink-100 text-xl tracking-wide px-4 py-2.5"
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
                <div class="mt-10 sm:mt-auto flex flex-col justify-center items-center gap-3">
                    <button
                        @click="roll"
                        class="mt-10 sm:mt-auto rounded-lg font-semibold bg-purple-950/20 text-purple-950 dark:bg-white/20 dark:text-purple-100 text-xl tracking-wide px-4 py-2.5"
                    >Re-roll</button>
                    <div class="text-gray-500 dark:text-gray-300"><span x-text="filteredMaps.length"></span> maps possible
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
                filters: @js($filters),
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

                init() {

                    // filter this.maps to only include maps that are in the selected games
                    this.filterMapsForGame(this.selectedGames[0])

                    this.filteredMaps = this.maps

                    this.filterMaps()
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
                                let intersection = map.filters.filter(x => this.selectedFilters
                                    .includes(x));
                                return intersection.length == this.selectedFilters.length && map
                                    .games.some(g => this.selectedGames.includes(g))
                            })
                            .filter(map => !this.hiddenMaps.includes(map.name))

                    }

                },

                imageSrc() {
                    return '/images/' + this.selected?.image
                }
            }))
        })
    </script>
@endsection
