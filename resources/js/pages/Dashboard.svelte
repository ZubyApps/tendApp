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

<script>
    let selectedFile = null;

    function handleFile(event) {
        const files = event.currentTarget.files;

        if (files && files.length > 0) {
            selectedFile = files[0];
        }
    }

    function handleDrop(event) {
        event.preventDefault();

        const files = event.dataTransfer.files;

        if (files && files.length > 0) {
            selectedFile = files[0];
        }
    }

    function removeFile() {
        selectedFile = null;
    }

    function formatFileSize(bytes) {
        if (!bytes) return "0 KB";

        const kb = bytes / 1024;

        if (kb < 1024) {
            return `${kb.toFixed(1)} KB`;
        }

        return `${(kb / 1024).toFixed(1)} MB`;
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
                        accept=".xlsx,.xls,.csv"
                        class="hidden"
                        onchange={handleFile}
                    />

                </div>


            

            </div>

        </div>

    </section>

</main>