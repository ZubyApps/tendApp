<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Import Attendance Data</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        surface: "#0D0D0D",
                        panel: "#111111",
                        border: "#252525",
                        blue: "#3B82F6"
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-black text-white">

    <!-- PAGE -->
    <main class="min-h-screen p-3 sm:p-4">

        <!-- WORKSPACE -->
        <section
            class="relative flex min-h-[calc(100vh-24px)] flex-col overflow-hidden rounded-xl border border-[#242424] bg-[#0D0D0D]"
        >

            <!-- HEADER -->
            <header
                class="flex shrink-0 items-center justify-between border-b border-[#242424] px-6 py-5 sm:px-8"
            >

                <div>
                    <h1 class="text-lg font-semibold tracking-tight text-white">
                        Import Attendance Data
                    </h1>

                    <p class="mt-1 text-sm text-[#737373]">
                        Upload attendance sheets to begin processing employee records.
                    </p>
                </div>

            </header>


            <!-- CONTENT -->
            <div class="flex flex-1 items-center justify-center px-5 py-8 sm:px-10 lg:px-16">

                <div class="w-full max-w-5xl">

                    <!-- IMPORT AREA -->
                    <div
                        id="drop-zone"
                        class="group relative flex min-h-[480px] flex-col items-center justify-center rounded-xl border border-dashed border-[#353535] bg-[#090909] px-6 py-12 text-center transition-all duration-200 hover:border-blue-500/60 hover:bg-[#0B0B0B]"
                    >

                        <!-- subtle blue glow -->
                        <div
                            class="pointer-events-none absolute left-1/2 top-1/2 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-blue-500/[0.025] blur-3xl"
                        ></div>


                        <!-- ICON -->
                        <div
                            class="relative mb-7 flex h-16 w-16 items-center justify-center rounded-xl border border-[#292929] bg-[#141414] text-[#737373] transition-colors group-hover:border-blue-500/30 group-hover:text-blue-400"
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
                        <h2 class="relative text-xl font-semibold tracking-tight text-white">
                            Upload attendance file
                        </h2>


                        <!-- DESCRIPTION -->
                        <p class="relative mt-2 max-w-md text-sm leading-6 text-[#707070]">
                            Drag and drop your Excel attendance sheet here,
                            or select a file from your computer.
                        </p>


                        <!-- BUTTON -->
                        <label
                            for="file-upload"
                            class="relative mt-7 inline-flex cursor-pointer items-center gap-2 rounded-md bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-lg shadow-blue-600/10 transition hover:bg-blue-500"
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

                            Add Excel File

                        </label>


                        <!-- FORMAT -->
                        <p class="relative mt-5 text-xs text-[#555555]">
                            Supported formats: .xlsx, .xls, .csv
                        </p>


                        <input
                            id="file-upload"
                            type="file"
                            accept=".xlsx,.xls,.csv"
                            class="hidden"
                        />

                    </div>


                    <!-- FILE PREVIEW -->
                    <div
                        id="file-preview"
                        class="mt-4 hidden"
                    >

                        <div
                            class="flex items-center justify-between rounded-lg border border-[#292929] bg-[#111111] px-5 py-4"
                        >

                            <div class="flex min-w-0 items-center gap-4">

                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-500/10 text-blue-400"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="h-5 w-5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A2.625 2.625 0 0112 5.625v-1.5A3.375 3.375 0 008.625.75H6.75A2.25 2.25 0 004.5 3v18a2.25 2.25 0 002.25 2.25h10.5A2.25 2.25 0 0019.5 21V14.25z"
                                        />
                                    </svg>

                                </div>


                                <div class="min-w-0">

                                    <p
                                        id="file-name"
                                        class="truncate text-sm font-medium text-white"
                                    >
                                        attendance.xlsx
                                    </p>

                                    <p
                                        id="file-size"
                                        class="mt-1 text-xs text-[#606060]"
                                    >
                                        0 KB
                                    </p>

                                </div>

                            </div>


                            <button
                                id="remove-file"
                                type="button"
                                class="ml-4 rounded-md p-2 text-[#666666] transition hover:bg-red-500/10 hover:text-red-400"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="h-5 w-5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>

                            </button>

                        </div>

                    </div>


                    <!-- CONTINUE -->
                    <div
                        id="continue-container"
                        class="mt-5 hidden justify-end"
                    >

                        <button
                            class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-500"
                        >

                            Continue

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="h-4 w-4"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13.5 4.5L19.5 12m0 0l-6 7.5M19.5 12H4.5"
                                />
                            </svg>

                        </button>

                    </div>

                </div>

            </div>

        </section>

    </main>


    <script>

        const fileInput = document.getElementById("file-upload");
        const dropZone = document.getElementById("drop-zone");

        const filePreview = document.getElementById("file-preview");
        const fileName = document.getElementById("file-name");
        const fileSize = document.getElementById("file-size");

        const removeFile = document.getElementById("remove-file");
        const continueContainer =
            document.getElementById("continue-container");


        function displayFile(file) {

            if (!file) return;

            fileName.textContent = file.name;

            const sizeInKB = (file.size / 1024).toFixed(1);

            fileSize.textContent = `${sizeInKB} KB`;

            filePreview.classList.remove("hidden");

            continueContainer.classList.remove("hidden");
            continueContainer.classList.add("flex");
        }


        // File selection
        fileInput.addEventListener("change", function () {

            const file = this.files[0];

            displayFile(file);

        });


        // Drag over
        dropZone.addEventListener("dragover", function (event) {

            event.preventDefault();

            dropZone.classList.add(
                "border-blue-500",
                "bg-blue-500/[0.02]"
            );

        });


        // Drag leave
        dropZone.addEventListener("dragleave", function () {

            dropZone.classList.remove(
                "border-blue-500",
                "bg-blue-500/[0.02]"
            );

        });


        // Drop
        dropZone.addEventListener("drop", function (event) {

            event.preventDefault();

            dropZone.classList.remove(
                "border-blue-500",
                "bg-blue-500/[0.02]"
            );

            const files = event.dataTransfer.files;

            if (files.length > 0) {

                fileInput.files = files;

                displayFile(files[0]);

            }

        });


        // Remove
        removeFile.addEventListener("click", function () {

            fileInput.value = "";

            filePreview.classList.add("hidden");

            continueContainer.classList.add("hidden");
            continueContainer.classList.remove("flex");

        });

    </script>

</body>
</html>