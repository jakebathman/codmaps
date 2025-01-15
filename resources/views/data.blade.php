@extends('layouts.app')

@section('content')
    <div
        class="p-6 sm:p-10 md:p-16 flex flex-col gap-10 max-w-5xl mx-auto"
        x-data="processHtml"
    >
        <div class="flex flex-col gap-2">
            <h1 class="text-3xl text-amber-950 font-bold font-mono">Process Your Call of Duty HTML File</h1>
            <div class="text-amber-800">
                This will merge your data from your file into one big CSV, with unified headers. All
                processing is done in your browser, and no data is stored.
            </div>
        </div>
        <div class="flex flex-col md:flex-row justify-between gap-10">
            <div class="w-full flex flex-col justify-between gap-10 ">
                <div>
                    <input
                        type="file"
                        id="fileInput"
                        accept=".html"
                        class="w-full file:cursor-pointer flex-1 file:text-amber-900 file:font-semibold file:bg-amber-100 file:border-none file:py-2.5 file:px-5 file:rounded-full file:mr-5"
                        @change="processFile"
                    >
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
                    <div class="text-xl text-amber-950 font-bold font-mono">Options</div>
                    <div class="text-amber-800">
                        There's a few sections that are omitted by default, but feel free to change what's included in
                        the
                        output:
                    </div>
                </div>

                <div class="flex justify-start">
                    <div class="flex flex-col gap-2">
                        <div
                            class="flex items-center justify-center gap-10"
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
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-0 focus:ring-amber-600 focus:ring-offset-2"
                                role="switch"
                                aria-checked="false"
                                aria-labelledby="availability-label"
                                aria-describedby="availability-description"
                            >
                                <span
                                    aria-hidden="true"
                                    :class="{ 'translate-x-5': includeAllOptions, 'translate-x-0': !includeAllOptions }"
                                    class="pointer-events-none inline-block size-5 translate-x-0 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                ></span>
                            </button>
                        </div>

                        {{-- Auto-generated toggles for individual settings  --}}
                        <template
                            x-for="(option, key) in options"
                            :key="index"
                        >
                            <div
                                class="flex items-center justify-center gap-10"
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
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-0 focus:ring-amber-600 focus:ring-offset-2"
                                    role="switch"
                                    aria-checked="false"
                                    aria-labelledby="availability-label"
                                    aria-describedby="availability-description"
                                >
                                    <span
                                        aria-hidden="true"
                                        :class="{ 'translate-x-5': option.value, 'translate-x-0': !option.value }"
                                        class="pointer-events-none inline-block size-5 translate-x-0 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                    ></span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

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
                file: null,
                sectionTitles: {},
                csvResult: '',
                downloadButtonDisabled: true,
                output: '',
                includeAllOptions: false,
                options: {
                    includeMultiplayerData: {
                        label: 'Include Multiplayer Match Data',
                        value: true
                    },
                    includeZombies: {
                        label: 'Include Zombies',
                        value: true
                    },
                    includeCampaigns: {
                        label: 'Include Campaigns',
                        value: false
                    },
                    includeSessionData: {
                        label: 'Include Session Data',
                        value: false
                    },
                    includeGamertagData: {
                        label: 'Include Gamertag Data',
                        value: false
                    },
                    includeCoOpMatchData: {
                        label: 'Include CoOp Match Data',
                        value: false
                    },
                    includePromoCodes: {
                        label: 'Include Promo Codes',
                        value: false
                    },
                },

                init() {
                    // watch options for changes
                    this.$watch('options', (newVal) => {
                        // If any options are false, make includeAllOptions false
                        this.includeAllOptions = Object.values(this.options).every(
                            (option) => option.value
                        );

                        if (this.file) {
                            this.$nextTick(() => {
                                setTimeout(() => {
                                    this.processFile({
                                        target: {
                                            files: [this.file]
                                        }
                                    });

                                }, 100);
                            });
                        }
                    });
                },

                toggleAllOptions() {
                    this.includeAllOptions = !this.includeAllOptions;

                    Object.entries(this.options).forEach(([key, option]) => {
                        option.value = this.includeAllOptions;
                    });
                },

                processFile(e) {
                    this.output = 'Processing...';
                    this.file = e.target.files[0];
                    if (!this.file) {
                        console.debug('No file selected.');
                        this.output = 'No file selected.';
                        return;
                    }

                    let that = this;
                    const reader = new FileReader();
                    reader.onload = async function(e) {
                        const htmlContent = e.target.result;

                        // Run your HTML-to-CSV script
                        const csvData = that.processHtmlToCsv(htmlContent);
                        that.csvResult = csvData;
                        let rowCount = Number(csvData.split('\n').length - 1).toLocaleString();

                        that.output = "Complete! You can now download the CSV file.\n\nRows: " +
                            (rowCount) + "\n\nSections: " + "\n\n";


                        Object.entries(that.sectionTitles).forEach(([game, sections]) => {
                            that.output += game + '\n';
                            sections.forEach((section) => {
                                that.output += '  ' + section + '\n';
                            });
                        });

                        // document.getElementById('output').textContent = csvData;
                        that.downloadButtonDisabled = false;
                    };
                    reader.readAsText(this.file);
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
                    a.download = 'processed-result.csv'; // Default filename
                    a.click();

                    // Clean up the URL object
                    URL.revokeObjectURL(url);
                },

                processHtmlToCsv(html) {
                    let includeMultiplayerData = this.options.includeMultiplayerData.value ?? false;
                    let includeZombies = this.options.includeZombies.value ?? false;
                    let includePromoCodes = this.options.includePromoCodes.value ?? false;
                    let includeCampaigns = this.options.includeCampaigns.value ?? false;
                    let includeSessionData = this.options.includeSessionData.value ?? false;
                    let includeGamertagData = this.options.includeGamertagData.value ?? false;
                    let includeCoOpMatchData = this.options.includeCoOpMatchData.value ?? false;

                    let slugify = (text) => {
                        return text
                            .toLowerCase() // Convert to lowercase
                            .replace(/[^a-z0-9\s-]/g,
                                ''
                            ) // Remove non-alphanumeric characters (except spaces and hyphens)
                            .trim() // Remove leading/trailing whitespace
                            .replace(/\s+/g, '-') // Replace spaces with hyphens
                            .replace(/-+/g, '-'); // Collapse multiple hyphens
                    };

                    let headers = ['Game', 'Section'];

                    let skipSection = (sectionTitle) => {
                        if (!includePromoCodes && sectionTitle.toLowerCase().includes(
                                'promocodes')) {
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

                        if (!includeMultiplayerData && sectionTitle.toLowerCase().includes(
                                'multiplayer')) {
                            return true;
                        }

                        if (!includeGamertagData && sectionTitle.toLowerCase().includes(
                                'gamertag')) {
                            return true;
                        }

                        if (
                            !includeCoOpMatchData &&
                            sectionTitle.toLowerCase().includes('coop match')
                        ) {
                            return true;
                        }

                        return [
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

                    // Parse the file only for table headers, so we can get a big unique list of headers

                    // Loop through each h1
                    $('h1').each((i, h1Element) => {
                        const mainTitle = $(h1Element).text().trim();

                        if (skipSection(mainTitle)) {
                            this.sectionTitles[mainTitle + ' (Skipped)'] = [];
                            return;
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
                                        let val = $(cellElement).text()
                                            .trim();
                                        headers.push(val);
                                    });

                                // stop after one pass
                                return false;
                            });
                        });
                    });

                    // Make the headers array only unique values
                    headers = [...new Set(headers)];

                    // Loop through everything again for the data
                    const data = {};

                    // Loop through each h1
                    $('h1').each((i, h1Element) => {
                        const mainTitle = $(h1Element).text().trim();
                        const sections = [];

                        // Skip the preamble sections
                        if (skipSection(mainTitle)) {
                            return;
                        }

                        // Look for the h2s that are under the current h1
                        let currentH2 = $(h1Element).nextUntil('h1', 'h2');

                        currentH2.each((j, h2Element) => {
                            const sectionTitle = $(h2Element)
                                .text()
                                .replace('(reverse chronological)', '')
                                .trim();

                            if (skipSection(sectionTitle)) {
                                this.sectionTitles[mainTitle].push(sectionTitle +
                                    ' (Skipped)');
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
                                    acc[key] =
                                        ''; // Assign empty string as the value
                                    return acc;
                                }, {});

                                rowData['Game'] = mainTitle;
                                rowData['Section'] = sectionTitle;

                                $(trElement)
                                    .find('th, td')
                                    .each((l, cellElement) => {
                                        let val = $(cellElement).text()
                                            .trim();

                                        if (k === 0) {
                                            // console.log('header:', val);
                                            orderedHeaders.push(val);
                                        } else {
                                            // console.log({h: orderedHeaders[l], v: val});
                                            rowData[orderedHeaders[l]] =
                                                val;
                                        }
                                    });

                                // Only push if not the first (header) row
                                if (k > 0) {
                                    sectionData.rows.push(rowData);
                                }
                            });

                            sections.push(sectionData);
                        });

                        if (sections.length === 0) {
                            console.log('No sections found for:', mainTitle);
                        } else {
                            if (!data[mainTitle]) {
                                data[mainTitle] = [];
                            }
                            data[mainTitle].push(...
                                sections); // Add each `h1` grouped section to the main data
                        }
                    });

                    // Write a big single csv
                    let headersString = headers.map((val) => `"${val}"`).join(',') + '\n';
                    let csv = '';

                    // Add the headers first
                    csv += headersString;

                    // Add the data
                    Object.entries(data).forEach(([game, sections]) => {
                        sections.forEach((section) => {
                            section.rows.forEach((row) => {
                                csv +=
                                    Object.values(row)
                                    .map((val) => {
                                        return `"${val}"`;
                                    })
                                    .join(',') + '\n';
                            });

                        });
                    });

                    return csv;
                },
            }));
        });
    </script>
@endsection
