<!-- <script module lang="ts">
    import { dashboard } from '@/routes';

    export const layout = {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    };
</script> -->

<!-- <script lang="ts">
    import AppHead from '@/components/AppHead.svelte';
    import PlaceholderPattern from '@/components/PlaceholderPattern.svelte';
</script>

<AppHead title="Dashboard" />

<div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div
            class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
        >
            <PlaceholderPattern />
        </div>
        <div
            class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
        >
            <PlaceholderPattern />
        </div>
        <div
            class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
        >
            <PlaceholderPattern />
        </div>
    </div>
    <div
        class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border"
    >
        <PlaceholderPattern />
    </div>
</div> -->


<script lang="ts">
    import { router } from '@inertiajs/svelte';
    import { store } from '@/actions/App/Http/Controllers/FileController';

    let selectedFile: File | null = null;
    let uploading = false;
    let uploadError = '';

    function handleFile(event: Event) {
        const input = event.currentTarget as HTMLInputElement;
        const file = input.files?.[0];

        if (!file) return;

        uploadError = '';

        if (!file.name.toLowerCase().endsWith('.csv')) {
            uploadError = 'Please select a CSV file.';
            input.value = '';
            selectedFile = null;
            return;
        }

        selectedFile = file;
    }

    function handleDrop(event: DragEvent) {
        event.preventDefault();

        const file = event.dataTransfer?.files[0];

        if (!file) return;

        uploadError = '';

        if (!file.name.toLowerCase().endsWith('.csv')) {
            uploadError = 'Please upload a CSV file.';
            return;
        }

        selectedFile = file;
    }

    function removeFile() {
        selectedFile = null;
        uploadError = '';
    }

    function formatFileSize(bytes: number) {
        if (!bytes) return '0 KB';

        const kb = bytes / 1024;

        return kb < 1024
            ? `${kb.toFixed(1)} KB`
            : `${(kb / 1024).toFixed(1)} MB`;
    }

    // function submitFile() {
    //     if (!selectedFile || uploading) return;

    //     uploadError = '';

    //     const formData = new FormData();
    //     formData.append('file', selectedFile);

    //     const route = store();

    //     router.post(route.url, formData, {
    //         forceFormData: true,
    //         onStart: () => {
    //             uploading = true;
    //         },
    //         onFinish: () => {
    //             uploading = false;
    //         },
    //         onError: (errors) => {
    //             uploadError =
    //                 errors.file ?? 'The file could not be imported. Please check it and try again.';
    //         },
    //     });
    //     window.location.href = window.location.origin+'/reports';
    // }
    function submitFile() {
    if (!selectedFile || uploading) return;

    uploadError = '';

    const formData = new FormData();
    formData.append('file', selectedFile);

    const route = store();

    router.post(route.url, formData, {
        forceFormData: true,

        onStart: () => {
            uploading = true;
        },

        onFinish: () => {
            uploading = false;
        },

        onSuccess: () => {
            window.location.href = '/importedFiles';
        },

        onError: (errors) => {
            uploadError =
                errors.file ??
                'The file could not be imported. Please check it and try again.';
        },
    });
}
</script>


<svelte:head>
    <title>Import Attendance Data</title>
</svelte:head>


<main
    class="min-h-screen bg-gray-50 p-3 text-gray-900 transition-colors
           dark:bg-black dark:text-white
           sm:p-4"
>

    <section
        class="flex min-h-[calc(100vh-24px)] flex-col overflow-hidden rounded-xl
               border border-gray-200 bg-white
               dark:border-[#242424] dark:bg-[#0D0D0D]"
    >

        <!-- HEADER -->
        <header
            class="shrink-0 border-b border-gray-200 px-6 py-5
                   dark:border-[#242424]
                   sm:px-8"
        >

            <h1
                class="text-lg font-semibold tracking-tight
                       text-gray-900 dark:text-white"
            >
                Import Attendance Data
            </h1>

            <p
                class="mt-1 text-sm text-gray-500
                       dark:text-[#737373]"
            >
                Upload attendance sheets to begin processing employee records.
            </p>

        </header>


        <!-- CONTENT -->
        <div
            class="flex flex-1 items-center justify-center
                   px-5 py-8 sm:px-10 lg:px-16"
        >

            <div class="w-full max-w-5xl">
                
        <!-- IMPORT GUIDELINES -->
        <div
            class="mb-5 rounded-xl border border-blue-200
                bg-blue-50/70 p-5
                dark:border-blue-900/50
                dark:bg-blue-950/20"
        >
            <div class="flex items-start gap-3">
                <div
                    class="flex h-9 w-9 shrink-0 items-center
                        justify-center rounded-lg
                        bg-blue-100 text-blue-600
                        dark:bg-blue-500/10
                        dark:text-blue-400"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.7"
                        stroke="currentColor"
                        class="h-5 w-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v3.75m0 3h.008M10.3 3.86
                            1.82 18.1a1.9 1.9 0 0 0
                            1.63 2.85h17.1a1.9 1.9 0
                            0 0 1.63-2.85L13.7
                            3.86a1.96 1.96 0 0 0-3.4 0Z"
                        />
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
                    <h2
                        class="text-sm font-semibold
                            text-gray-900 dark:text-white"
                    >
                        Before you import
                    </h2>

                    <p
                        class="mt-1 text-sm leading-6
                            text-gray-600 dark:text-gray-400"
                    >
                        Follow these instructions to ensure your
                        attendance records are processed correctly.
                    </p>

                    <ul
                        class="mt-3 space-y-2 text-sm
                            text-gray-700 dark:text-gray-300"
                    >
                        <li class="flex items-start gap-2">
                            <span class="font-semibold text-blue-600">01.</span>
                            <span>
                                <strong>Remove the header row.</strong>
                                Delete the column headings from the CSV.
                                Import only the attendance records.
                            </span>
                        </li>

                        <li class="flex items-start gap-2">
                            <span class="font-semibold text-blue-600">02.</span>
                            <span>
                                <strong>Keep the columns in order.</strong>
                                Do not rearrange employee IDs, names,
                                departments or attendance times.
                            </span>
                        </li>

                        <li class="flex items-start gap-2">
                            <span class="font-semibold text-blue-600">03.</span>
                            <span>
                                <strong>Check the attendance data.</strong>
                                Make sure employee details and recorded
                                times are readable and correctly formatted.
                            </span>
                        </li>

                        <li class="flex items-start gap-2">
                            <span class="font-semibold text-blue-600">04.</span>
                            <span>
                                <strong>Upload a CSV file.</strong>
                                Save or export your attendance sheet
                                in .csv format before uploading.
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
                <!-- DROP ZONE -->
                <!-- svelte-ignore a11y_no_static_element_interactions -->
                <div
                    class="group relative flex min-h-[480px] flex-col
                           items-center justify-center rounded-xl
                           border border-dashed
                           border-gray-300 bg-gray-50
                           px-6 py-12 text-center
                           transition-all duration-200

                           hover:border-blue-400
                           hover:bg-blue-50/30

                           dark:border-[#353535]
                           dark:bg-[#090909]
                           dark:hover:border-blue-500/60
                           dark:hover:bg-[#0B0B0B]"
                    ondragover={(event) => event.preventDefault()}
                    ondrop={handleDrop}
                >

                    <!-- SUBTLE BLUE GLOW -->
                    <div
                        class="pointer-events-none absolute left-1/2 top-1/2
                               h-72 w-72 -translate-x-1/2 -translate-y-1/2
                               rounded-full bg-blue-500/[0.025] blur-3xl
                               dark:block hidden"
                    ></div>


                    <!-- ICON -->
                    <div
                        class="relative mb-7 flex h-16 w-16 items-center
                               justify-center rounded-xl
                               border border-gray-200 bg-white
                               text-gray-500
                               transition-colors

                               group-hover:border-blue-300
                               group-hover:text-blue-500

                               dark:border-[#292929]
                               dark:bg-[#141414]
                               dark:text-[#737373]
                               dark:group-hover:border-blue-500/30
                               dark:group-hover:text-blue-400"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-7 w-7"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 16.5V3.75m0 0L7.5 8.25M12 3.75l4.5 4.5M6.75 20.25h10.5a2.25 2.25 0 002.25-2.25v-1.5a2.25 2.25 0 00-2.25-2.25h-.75M6.75 20.25a2.25 2.25 0 01-2.25-2.25v-1.5a2.25 2.25 0 012.25-2.25h.75"
                            />
                        </svg>

                    </div>


                    <!-- TITLE -->
                    <h2
                        class="relative text-xl font-semibold tracking-tight
                               text-gray-900 dark:text-white"
                    >
                        Upload attendance file
                    </h2>


                    <!-- DESCRIPTION -->
                    <p
                        class="relative mt-2 max-w-md text-sm leading-6
                               text-gray-500 dark:text-[#707070]"
                    >
                        Drag and drop your Excel attendance sheet here,
                        or select a file from your computer.
                    </p>


                    <!-- BUTTON -->
                    <label
                        for="file-upload"
                        class="relative mt-7 inline-flex cursor-pointer
                               items-center gap-2 rounded-md
                               bg-blue-600 px-5 py-2.5 text-sm font-medium
                               text-white shadow-lg shadow-blue-600/10
                               transition hover:bg-blue-500"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-4 w-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 4.5v15m7.5-7.5h-15"
                            />
                        </svg>

                        Add File

                    </label>


                    <!-- FORMAT -->
                    <p
                        class="relative mt-5 text-xs
                               text-gray-400 dark:text-[#555555]"
                    >
                        Supported formats: .xlsx, .xls, .csv
                    </p>

                <input
                    id="file-upload"
                    type="file"
                    accept=".csv,text/csv"
                    class="hidden"
                    onchange={handleFile}
                />
                </div>
                                <!-- FILE PREVIEW -->
                {#if selectedFile}

                    <div class="mt-4">

                        <div
                            class="flex items-center justify-between
                                   rounded-lg border
                                   border-gray-200 bg-white
                                   px-5 py-4
                                   dark:border-[#292929]
                                   dark:bg-[#111111]"
                        >

                            <div class="flex min-w-0 items-center gap-4">

                                <div
                                    class="flex h-10 w-10 shrink-0
                                           items-center justify-center
                                           rounded-lg
                                           bg-blue-500/10 text-blue-500
                                           dark:text-blue-400"
                                >
                                    📊
                                </div>


                                <div class="min-w-0">

                                    <p
                                        class="truncate text-sm font-medium
                                               text-gray-900
                                               dark:text-white"
                                    >
                                        {selectedFile.name}
                                    </p>

                                    <p
                                        class="mt-1 text-xs
                                               text-gray-400
                                               dark:text-[#606060]"
                                    >
                                        {formatFileSize(selectedFile.size)}
                                    </p>

                                </div>

                            </div>


                            <button
                                type="button"
                                onclick={removeFile}
                                class="ml-4 rounded-md p-2
                                       text-gray-400
                                       transition
                                       hover:bg-red-500/10
                                       hover:text-red-500
                                       dark:text-[#666666]"
                            >
                                ✕
                            </button>

                        </div>


                        <!-- CONTINUE -->
                        <button
                            type="button"
                            disabled={!selectedFile || uploading}
                            class="inline-flex items-center gap-2 rounded-md
                                bg-blue-600 px-5 py-2.5 text-sm font-medium
                                text-white transition hover:bg-blue-500
                                disabled:cursor-not-allowed disabled:opacity-50"
                            onclick={submitFile}
                        >
                            {uploading ? 'Importing...' : 'Continue'}
                            <span>→</span>
                        </button>

                    </div>

                {/if}

            

            </div>

        </div>

    </section>

</main>