@extends('layouts.app')

@section('content')
    <div
        class="p-6 sm:p-10 md:p-16 flex flex-col gap-10 max-w-5xl mx-auto"
        :class="{ 'cursor-wait': processing }"
        x-data="processHtml"
        x-cloak
    >
        <div class="flex flex-col gap-2">
            <h1 class="text-3xl text-amber-950 font-bold font-mono">Process Your Call of Duty HTML File</h1>
            <div class="text-amber-800">
                This will merge your data from your file into one big CSV, with unified headers. All
                processing is done in your browser, and no data is stored. You can provide <strong>multiple HTML files</strong>
                at one time and they'll be combined and deduplicated (if you have that setting selected below).
            </div>
        </div>
        <div class="flex flex-col md:flex-row justify-between gap-10">
            <div class="w-full flex flex-col justify-between gap-10 ">
                <div>
                    <input
                        type="file"
                        id="fileInput"
                        multiple
                        accept=".html"
                        class="w-full file:cursor-pointer flex-1 file:text-amber-900 file:font-semibold file:bg-amber-100 file:border-none file:py-2.5 file:px-5 file:rounded-full file:mr-5"
                        @change="processFile"
                    >
                </div>

                <div x-show="processing || percentage > 0">
                    <div class="text-amber-900/80 font-bold font-mono text-5xl text-center flex justify-center"><pre x-text="`${percentage}`.padStart(3)">0</pre>%</div>
                </div>

                <div>
                    <button
                        id="downloadBtn"
                        :disabled="downloadButtonDisabled"
                        class="text-amber-900 font-semibold bg-amber-100 border-none rounded-full py-2.5 px-5"
                        :class="{ 'opacity-30 cursor-not-allowed': downloadButtonDisabled }"
                        @click="downloadCsv"
                    >
                        Download CSV Result
                    </button>
                </div>
            </div>

            {{-- Options --}}
            <div class="flex flex-col gap-7">
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between items-baseline py-1">
                        <div class="text-xl text-amber-950 font-bold font-mono">Options</div>
                        <div
                            x-show="optionsAreAtDefaults() == false"
                            x-transition.duration.300ms
                            class="text-sm text-amber-950/50 hover:bg-amber-50/50 hover:text-amber-950/70 px-2 py-1 -mr-2 -mb-1 transition cursor-pointer"
                            @click="restoreDefaultOptions"
                        >restore defaults</div>
                    </div>
                    <div class="text-amber-800 text-justify">
                        There's a few sections that are omitted by default,
                        but feel free to change what's included in the output:
                    </div>
                </div>

                <div class="flex justify-start">
                    <div class="flex flex-col gap-2 w-full">

                        <div
                            class="flex items-center justify-center gap-14 mb-5"
                            @click="shouldDeDuplicate = !shouldDeDuplicate"
                        >
                            <span class="flex grow flex-col ">
                                <span
                                    class="text-sm/6 text-gray-900 font-bold"
                                    id="availability-label"
                                >Try to de-duplicate</span>
                                <span
                                    class="text-sm text-gray-500"
                                    id="availability-description"
                                >Uses Match ID and other fields</span>
                            </span>

                            <button
                                type="button"
                                :class="{ 'bg-amber-600': shouldDeDuplicate, 'bg-gray-200': !shouldDeDuplicate }"
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-hidden focus:ring-0 focus:ring-amber-600 focus:ring-offset-2"
                                role="switch"
                                aria-checked="false"
                                aria-labelledby="availability-label"
                                aria-describedby="availability-description"
                            >
                                <span
                                    aria-hidden="true"
                                    :class="{ 'translate-x-5': shouldDeDuplicate, 'translate-x-0': !shouldDeDuplicate }"
                                    class="pointer-events-none inline-block size-5 translate-x-0 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                ></span>
                            </button>
                        </div>

                        <div
                            class="flex items-center justify-center gap-14"
                            @click="toggleAllOptions"
                        >
                            <span class="flex grow flex-col ">
                                <span
                                    class="text-sm/6 text-gray-900 font-bold"
                                    id="availability-label"
                                >Include All</span>
                            </span>

                            <button
                                type="button"
                                :class="{ 'bg-amber-600': includeAllOptions, 'bg-gray-200': !includeAllOptions }"
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-hidden focus:ring-0 focus:ring-amber-600 focus:ring-offset-2"
                                role="switch"
                                aria-checked="false"
                                aria-labelledby="availability-label"
                                aria-describedby="availability-description"
                            >
                                <span
                                    aria-hidden="true"
                                    :class="{ 'translate-x-5': includeAllOptions, 'translate-x-0': !includeAllOptions }"
                                    class="pointer-events-none inline-block size-5 translate-x-0 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                ></span>
                            </button>
                        </div>

                        {{-- Auto-generated toggles for individual settings  --}}
                        <template
                            x-for="(option, key) in options"
                            :key="index"
                        >
                            <div
                                class="flex items-center justify-center gap-14"
                                @click="option.value = !option.value"
                            >
                                <span class="flex grow flex-col">
                                    <span
                                        class="text-sm/6 font-medium text-gray-900"
                                        id="availability-label"
                                        x-text="option.label"
                                    ></span>
                                </span>

                                <button
                                    type="button"
                                    :class="{ 'bg-amber-600': option.value, 'bg-gray-200': !option.value }"
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-hidden focus:ring-0 focus:ring-amber-600 focus:ring-offset-2"
                                    role="switch"
                                    aria-checked="false"
                                    aria-labelledby="availability-label"
                                    aria-describedby="availability-description"
                                >
                                    <span
                                        aria-hidden="true"
                                        :class="{ 'translate-x-5': option.value, 'translate-x-0': !option.value }"
                                        class="pointer-events-none inline-block size-5 translate-x-0 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                    ></span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>

        <div
            class="flex flex-col gap-5"
            x-show="showDebug"
        >
            <div class="text-amber-950 font-bold font-mono text-2xl">Row Counts</div>
            <template x-for="section in rowCounts">
                <div
                    class="text-amber-800"
                    x-text="section.rows + ' - ' + section.game + ': ' + section.section"
                ></div>
            </template>
        </div>

        <pre
            x-show="output"
            style="border: 1px solid #ccc; padding: 10px;"
            x-text="output"
        ></pre>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('processHtml', () => ({
                files: null,
                sectionTitles: {},
                csvResult: '',
                newName: '',
                downloadButtonDisabled: true,
                output: '',
                countDuplicates: 0,
                processing: false,
                percentage: 0,
                rowCounts: [],
                shouldDeDuplicate: Alpine.$persist(true).as('should_de_duplicate'),
                includeAllOptions: Alpine.$persist(false).as('include_all_options'),
                showDebug: false,
                options: {
                    includeMultiplayerData: {
                        label: 'Include Multiplayer Match Data',
                        value: Alpine.$persist(true).as('include_multiplayer_data'),
                        default: true,
                    },
                    includeZombies: {
                        label: 'Include Zombies',
                        value: Alpine.$persist(true).as('include_zombies'),
                        default: true,
                    },
                    includeCampaigns: {
                        label: 'Include Campaigns',
                        value: Alpine.$persist(false).as('include_campaigns'),
                        default: false,
                    },
                    includeSessionData: {
                        label: 'Include Session Data',
                        value: Alpine.$persist(false).as('include_session_data'),
                        default: false,
                    },
                    includeGamertagData: {
                        label: 'Include Gamertag Data',
                        value: Alpine.$persist(false).as('include_gamertag_data'),
                        default: false,
                    },
                    includeCoOpMatchData: {
                        label: 'Include CoOp Match Data',
                        value: Alpine.$persist(false).as('include_co_op_match_data'),
                        default: false,
                    },
                    includePromoCodes: {
                        label: 'Include Promo Codes',
                        value: Alpine.$persist(false).as('include_promo_codes'),
                        default: false,
                    },
                },

                init() {
                    // watch options for changes
                    this.$watch('options, shouldDeDuplicate', (newVal) => {
                        // If any options are false, make includeAllOptions false
                        this.includeAllOptions = Object.values(this.options).every(
                            (option) => option.value
                        );

                        if (this.files && !this.processing) {
                            this.$nextTick(() => {
                                this.processFile({
                                    target: {
                                        files: this.files
                                    }
                                });

                            });
                        }
                    });
                },

                optionsAreAtDefaults() {
                    let basicOptions = Object.values(this.options).every((option) => option.value === option.default);
                    return basicOptions && this.shouldDeDuplicate === true;
                },

                restoreDefaultOptions() {
                    Object.entries(this.options).forEach(([key, option]) => {
                        option.value = option.default;
                    });

                    this.shouldDeDuplicate = true;
                },

                toggleAllOptions() {
                    this.includeAllOptions = !this.includeAllOptions;

                    Object.entries(this.options).forEach(([key, option]) => {
                        option.value = this.includeAllOptions;
                    });
                },

                processFile(e) {
                    this.output = 'Processing...';
                    this.percentage = 0;
                    this.rowCounts = [];
                    this.files = e.target.files;
                    if (!this.files) {
                        console.debug('No file selected.');
                        this.output = 'No file selected.';
                        return;
                    }

                    let fileNoExtension = this.files[0].name.replace(/\.[^/.]+$/, "")

                    // Add datetime after old file name
                    let date = new Date();
                    this.newName = fileNoExtension + '_' + (new Date()).toISOString()
                        .replaceAll('-', '')
                        .replaceAll('T', '')
                        .replaceAll(':', '')
                        .slice(0, 14) + '.csv';

                    let that = this;
                    const reader = new FileReader();
                    reader.onload = async function(e) {
                        const htmlContent = e.target.result;

                        // Run your HTML-to-CSV script
                        const csvData = await that.processHtmlToCsv(htmlContent);
                        that.csvResult = csvData;
                        let rowCount = Number(csvData.split('\n').length - 1).toLocaleString();

                        that.output = "Complete! You can now download the CSV file.\n\nRows: " +
                            (rowCount) + "\n\n";

                        that.output += (that.shouldDeDuplicate ?
                                'Duplicates removed: ' :
                                'Duplicates found (but not removed): ') +
                            Number(that.countDuplicates).toLocaleString() +
                            '\n\n----------------\n\n';

                        that.output += 'Sections:\n\n';

                        Object.entries(that.sectionTitles).forEach(([game, sections]) => {
                            that.output += game + '\n';
                            sections.forEach((section) => {
                                that.output += '  ' + section + '\n';
                            });
                        });

                        // document.getElementById('output').textContent = csvData;
                        that.downloadButtonDisabled = false;
                        that.processing = false;
                    };

                    this.processing = true;

                    this.combineFilesIntoBlob(this.files)
                        .then((blob) => {
                            console.log("Blob created:", blob);
                            reader.readAsText(blob);
                        })
                        .catch((error) => {
                            console.error("Error combining files:", error);
                        });


                },

                combineFilesIntoBlob(files) {
                    return new Promise((resolve, reject) => {
                        const readerForHtml = new FileReader();
                        let concatenatedContent = "";
                        let index = 0;

                        function readNextFile() {
                            if (index >= files.length) {
                                // All files are read, resolve the promise
                                const blob = new Blob([concatenatedContent], {
                                    type: "text/html"
                                });
                                resolve(blob);
                                return;
                            }

                            readerForHtml.onload = () => {
                                concatenatedContent += readerForHtml.result + "\n";
                                index++;
                                readNextFile(); // Move to the next file
                            };

                            readerForHtml.onerror = (error) => {
                                reject(error); // Reject the promise on any error
                            };

                            readerForHtml.readAsText(files[index]); // Read the current file
                        }

                        if (files.length === 0) {
                            resolve(new Blob([""], {
                                type: "text/html"
                            })); // Empty Blob if no files
                        } else {
                            readNextFile(); // Start reading the first file
                        }
                    });
                },

                downloadCsv() {
                    if (!this.csvResult) {
                        console.debug('No CSV data to download.');
                        return;
                    }

                    // Create a blob for the CSV content
                    const blob = new Blob([this.csvResult], {
                        type: 'text/csv'
                    });
                    const url = URL.createObjectURL(blob);

                    // Create a link to download the file
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = this.newName;
                    a.click();

                    // Clean up the URL object
                    URL.revokeObjectURL(url);
                },

                hashString(input) {
                    const combinedString = input.join('');
                    let hash = 0x811c9dc5; // FNV-1a 32-bit offset basis

                    for (let i = 0; i < combinedString.length; i++) {
                        hash ^= combinedString.charCodeAt(i);
                        // Multiply by FNV prime and ensure 32-bit overflow
                        hash = (hash * 0x01000193) >>> 0;
                    }

                    // Convert hash to hexadecimal
                    return hash.toString(16).padStart(8, '0');
                },

                async processHtmlToCsv(html) {
                    this.output = 'Reading headers...';
                    let includeMultiplayerData = this.options.includeMultiplayerData.value ?? false;
                    let includeZombies = this.options.includeZombies.value ?? false;
                    let includePromoCodes = this.options.includePromoCodes.value ?? false;
                    let includeCampaigns = this.options.includeCampaigns.value ?? false;
                    let includeSessionData = this.options.includeSessionData.value ?? false;
                    let includeGamertagData = this.options.includeGamertagData.value ?? false;
                    let includeCoOpMatchData = this.options.includeCoOpMatchData.value ?? false;

                    let matchIds = [];
                    let matchIdsToGame = new Map([]);
                    let hashesToSkip = new Set();
                    this.countDuplicates = 0;

                    let slugify = (text) => {
                        return text
                            .toLowerCase() // Convert to lowercase
                            .replace(/[^a-z0-9\s-]/g, '') // Remove non-alphanumeric characters (except spaces and hyphens)
                            .trim() // Remove leading/trailing whitespace
                            .replace(/\s+/g, '-') // Replace spaces with hyphens
                            .replace(/-+/g, '-'); // Collapse multiple hyphens
                    };


                    let headers = ['Game', 'Section', 'hash'];

                    let skipSection = (sectionTitle) => {
                        if (!includePromoCodes && sectionTitle.toLowerCase().includes('promocodes')) {
                            return true;
                        }

                        if (!includeCampaigns && sectionTitle.toLowerCase().includes('campaign')) {
                            return true;
                        }

                        if (
                            !includeSessionData &&
                            (sectionTitle.toLowerCase().includes('sessions data') ||
                                sectionTitle.toLowerCase().includes('mobile hardware data'))
                        ) {
                            return true;
                        }

                        if (!includeZombies && sectionTitle.toLowerCase().includes('zombies')) {
                            return true;
                        }

                        if (!includeMultiplayerData && sectionTitle.toLowerCase().includes('multiplayer')) {
                            return true;
                        }

                        if (!includeGamertagData && sectionTitle.toLowerCase().includes('gamertag')) {
                            return true;
                        }

                        if (
                            !includeCoOpMatchData &&
                            sectionTitle.toLowerCase().includes('coop match')
                        ) {
                            return true;
                        }

                        return [
                            'Activision Account',
                            'Activision SAR Report',
                            'How We Use Your Personal Information',
                            'Copy of Your Data',
                            'Activision Account Shared',
                            'Who We Send Your Data To',
                            'Sales and Sharing (for targeted advertising) of Your Data',
                            'Your Rights',
                        ].includes(sectionTitle);
                    };

                    const $ = cheerio.load(html);
                    const elements = $('h1');
                    const totalHeaders = elements.length;

                    // Parse the file only for table headers, so we can get a big unique list of headers
                    let headerCount = 0

                    // Loop through each h1
                    for (let i = 0; i < totalHeaders; i++) {
                        const h1Element = elements[i];

                        headerCount++;
                        await this.updateProgress(headerCount, totalHeaders * 2);

                        const mainTitle = $(h1Element).text().trim();

                        if (skipSection(mainTitle)) {
                            this.sectionTitles[mainTitle + ' (Skipped)'] = [];
                            continue;
                        }

                        this.sectionTitles[mainTitle] = [];

                        // Look for the h2s that are under the current h1
                        let currentH2 = $(h1Element).nextUntil('h1', 'h2');
                        currentH2.each((j, h2Element) => {
                            const sectionTitle = $(h2Element).text().trim();

                            if (skipSection(sectionTitle)) {
                                return;
                            }

                            // Find the table immediately following the h2
                            const table = $(h2Element).next('table');

                            // Extract rows from the table
                            table.find('tr').each((k, trElement) => {
                                $(trElement)
                                    .find('th, td')
                                    .each((l, cellElement) => {
                                        let val = $(cellElement).text().trim();
                                        headers.push(val);
                                    });

                                // stop after one pass
                                return false;
                            });
                        });
                    }

                    console.debug('Headers:', headers);
                    // Make the headers array only unique values
                    headers = [...new Set(headers)];

                    // Loop through everything again for the data
                    const data = {};

                    this.output = 'Processing data...';

                    // Loop through each h1
                    for (let i = 0; i < totalHeaders; i++) {
                        const h1Element = elements[i];

                        headerCount++;
                        await this.updateProgress(headerCount, totalHeaders * 2);

                        const mainTitle = $(h1Element).text().trim();
                        const sections = [];

                        // Skip the preamble sections
                        if (skipSection(mainTitle)) {
                            continue;
                        }

                        // Look for the h2s that are under the current h1
                        let currentH2 = $(h1Element).nextUntil('h1', 'h2');

                        currentH2.each((j, h2Element) => {
                            const sectionTitle = $(h2Element)
                                .text()
                                .replace('(reverse chronological)', '')
                                .trim();

                            if (skipSection(sectionTitle)) {
                                this.sectionTitles[mainTitle].push(sectionTitle + ' (Skipped)');
                                return;
                            }

                            this.sectionTitles[mainTitle].push(sectionTitle);

                            const sectionData = {
                                section: sectionTitle,
                                rows: [],
                                csv: ''
                            };

                            // Find the table immediately following the h2
                            const table = $(h2Element).next('table');

                            let orderedHeaders = [];

                            // Extract rows from the table
                            table.find('tr').each((k, trElement) => {
                                // This row is an object with keys as headers and values as the cell values, if any
                                let rowData = headers.reduce((acc, key) => {
                                    acc[key] = ''; // Assign empty string as the value
                                    return acc;
                                }, {});

                                rowData['Game'] = mainTitle;
                                rowData['Section'] = sectionTitle;
                                rowData['hash'] = '';

                                let matchId = null;
                                let isDuplicate = false;
                                let deDupStrings = [];

                                $(trElement)
                                    .find('th, td')
                                    .each((l, cellElement) => {
                                        let val = $(cellElement).text().trim();

                                        if (k === 0) {
                                            // console.log('header:', val);
                                            orderedHeaders.push(val);
                                        } else {
                                            let field = orderedHeaders[l];
                                            if (field === 'Match ID') {
                                                matchId = val;

                                                if (matchId.length > 0) {
                                                    if (matchIds.includes(val) && k > 0) {
                                                        isDuplicate = true;
                                                        this.countDuplicates++;
                                                    } else {
                                                        matchIds.push(val);
                                                    }
                                                }
                                            }

                                            // Gather strings to hash for deduplication
                                            if ([
                                                    'UTC Timestamp',
                                                    'Match ID',
                                                    'Map',
                                                    'Game Type',
                                                    'Game Type Screen Name',
                                                    'redeemDate',
                                                    'Checkpoint',
                                                ].indexOf(field) > -1) {
                                                deDupStrings.push(val);
                                            }

                                            rowData[field] = val;
                                        }
                                    });

                                let hashString = 'hash::' + this.hashString(deDupStrings);
                                rowData['hash'] = hashString;

                                // If the hash matches one before, also mark as duplicate
                                if (matchIds.includes(hashString) && k > 0) {
                                    if (hashString === 'hash::a1d52a34') {
                                        console.debug('hash::a1d52a34', 'repeat');
                                    }
                                    if (!isDuplicate) {
                                        this.countDuplicates++;
                                        isDuplicate = true;
                                        // console.log('Duplicate hash:', hashString);
                                    }
                                } else {
                                    matchIds.push(hashString);

                                    if (hashString === 'hash::a1d52a34') {
                                        console.debug('hash::a1d52a34', 'first time');
                                    }
                                }

                                // Only push if not the first (header) row
                                if (k > 0) {
                                    if (this.shouldDeDuplicate && isDuplicate) {
                                        // console.log('Skipping duplicate:', matchId);

                                        /**
                                         * They put duplicate matches under MW, MW II, and MW III,
                                         * so if it's a MW II game it'll show up twice. We want to
                                         * keep only the latest one (the game with the longest name).
                                         *
                                         * This is hard. The one we keep could be this one, it could be
                                         * one we've logged already in another section (not in this loop),
                                         * or it could be a future one.
                                         *
                                         * The matchIdsToGame map will keep track of the hash (as key),
                                         * where we've seen it before (to find it again in the
                                         * sections array), and the game name (as value).
                                         *
                                         * matchIdsToGame: {
                                         *  'hash::12345678': [{
                                         *     gameName: 'Modern Warfare II',
                                         *     section: 'Multiplayer',
                                         *     key: 123,
                                         *  }]
                                         * }
                                         *
                                         * So if we see a duplicate hash, we look in this object to see
                                         * if the games' already been logged.
                                         *
                                         *  - If the game name in matchIdsToGame is longer than
                                         *      the one we're looking at, ignore the current row.
                                         *      No change to matchIdsToGame.
                                         *  - If the game name in matchIdsToGame is shorter,
                                         *      update the game name in the matchIdsToGame object
                                         *      and remove the old row from the sections array,
                                         *      using the key.
                                         */


                                        let gameName = mainTitle;
                                        // Existing game name by hash ID
                                        if (matchIdsToGame.has(hashString)) {
                                            gameName = matchIdsToGame.get(hashString).gameName;
                                        }

                                        if (mainTitle.length > gameName.length) {
                                            // This game is longer than the one logged before,
                                            // so we want to keep this one and nuke the other one
                                            gameName = mainTitle;
                                            rowData['Game'] = gameName;

                                            // Remove the old row from the sections array
                                            let oldRow = matchIdsToGame.get(hashString);

                                            hashesToSkip.add(oldRow.gameName + ' - ' + hashString);

                                            // Add this current row to the section
                                            sectionData.rows.push(rowData);

                                            matchIdsToGame.set(hashString, {
                                                gameName: rowData['Game'],
                                                section: sections.length,
                                                key: k,
                                            });
                                        }
                                    } else {
                                        sectionData.rows.push(rowData);

                                        matchIdsToGame.set(hashString, {
                                            gameName: rowData['Game'],
                                            section: sections.length,
                                            key: k,
                                        });
                                    }


                                }
                            });

                            sections.push(sectionData);
                        });

                        if (sections.length === 0) {
                            // console.log('No sections found for:', mainTitle);
                        } else {
                            if (!data[mainTitle]) {
                                data[mainTitle] = [];
                            }
                            data[mainTitle].push(...sections); // Add each `h1` grouped section to the main data
                        }
                    }
                    // end of header loop

                    console.debug(data)
                    console.debug(matchIdsToGame);

                    // Write a big single csv
                    let headersString = headers.map((val) => `"${val}"`).join(',') + '\n';
                    let csv = '';

                    // Add the headers first
                    csv += headersString;

                    // Add the data
                    Object.entries(data).forEach(([game, sections]) => {
                        sections.forEach((section) => {
                            this.rowCounts.push({
                                game: game,
                                section: section.section,
                                rows: section.rows.length,
                            });

                            section.rows.forEach((row) => {
                                if (this.shouldDeDuplicate && hashesToSkip.has(game + ' - ' + row['hash'])) {
                                    // console.debug('skipping: ', game + ' - ' + row['hash']);
                                    return;
                                }

                                csv +=
                                    Object.values(row)
                                    .map((val) => {
                                        return `"${val}"`;
                                    })
                                    .join(',') + '\n';
                            });

                        });
                    });

                    // hash::8245859c
                    console.log('debug match')
                    console.debug(matchIdsToGame.get('hash::a1d52a34'));

                    return csv;
                },

                async updateProgress(current, total) {
                    this.percentage = Math.round((current / total) * 100);

                    // Small delay to allow repaint
                    await this.sleep(10);
                },

                sleep(ms) {
                    return new Promise(resolve => setTimeout(resolve, ms));
                },

            }));
        });
    </script>
@endsection
