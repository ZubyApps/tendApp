
<script lang="ts">
    import { router } from '@inertiajs/svelte';

    type AssessmentType = 'late' | 'early' | 'perfect';
    type ViewType = 'table' | 'cards';
    type ModalStep = 'assessment' | 'shifts';

    type AttendanceFile = {
        id: number;
        name: string;
        type: string;
        size: string;
        importedAt: string;
        employees: number;
        status: 'Ready' | 'Analyzed';
    };

    type Shift = {
        id: number;
        name: string;
        startTime: string;
        endTime: string;
        gracePeriod: number;
        earlyWindow: number;
    };

    let view: ViewType = 'table';
    let search = '';

    let files: AttendanceFile[] = [
        {
            id: 1,
            name: 'September_Attendance.csv',
            type: 'CSV',
            size: '248 KB',
            importedAt: 'Oct 02, 2026',
            employees: 126,
            status: 'Ready',
        },
        {
            id: 2,
            name: 'August_Attendance.csv',
            type: 'CSV',
            size: '192 KB',
            importedAt: 'Sep 01, 2026',
            employees: 118,
            status: 'Analyzed',
        },
        {
            id: 3,
            name: 'July_Attendance.csv',
            type: 'CSV',
            size: '215 KB',
            importedAt: 'Aug 03, 2026',
            employees: 121,
            status: 'Analyzed',
        },
    ];

    // Assessment modal
    let showAssessmentModal = false;
    let selectedFile: AttendanceFile | null = null;
    let assessmentType: AssessmentType = 'late';
    let modalStep: ModalStep = 'assessment';
    let shiftCount = 1;
    let shifts: Shift[] = [];
    let assessmentError = '';

    // Delete confirmation
    let showDeleteModal = false;
    let fileToDelete: AttendanceFile | null = null;

    $: filteredFiles = files.filter((file) =>
        file.name.toLowerCase().includes(search.toLowerCase())
    );

    $: totalEmployees = files.reduce(
        (total, file) => total + file.employees,
        0
    );

    $: pendingFiles = files.filter(
        (file) => file.status === 'Ready'
    ).length;

    function formatNumber(value: number) {
        return value.toLocaleString();
    }

    function openAssessment(file: AttendanceFile) {
        selectedFile = file;
        assessmentType = 'late';
        modalStep = 'assessment';
        shiftCount = 1;
        shifts = [];
        assessmentError = '';
        showAssessmentModal = true;
    }

    function closeAssessment() {
        showAssessmentModal = false;
        selectedFile = null;
        shifts = [];
        assessmentError = '';
    }

    function createShift(index: number): Shift {
        return {
            id: index + 1,
            name: `Shift ${index + 1}`,
            startTime: '08:00',
            endTime: '16:00',
            gracePeriod: 10,
            earlyWindow: 30,
        };
    }

    function configureShifts() {
        assessmentError = '';

        if (!shiftCount || shiftCount < 1 || shiftCount > 10) {
            assessmentError = 'Choose between 1 and 10 shifts.';
            return;
        }

        shifts = Array.from(
            { length: shiftCount },
            (_, index) => shifts[index] ?? createShift(index)
        );

        modalStep = 'shifts';
    }

    function updateShiftCount(value: number) {
        shiftCount = value;

        if (shiftCount >= 1 && shiftCount <= 10) {
            shifts = Array.from(
                { length: shiftCount },
                (_, index) => shifts[index] ?? createShift(index)
            );
        }
    }

    function addShift() {
        if (shifts.length >= 10) {
            assessmentError = 'You can configure up to 10 shifts.';
            return;
        }

        shifts = [...shifts, createShift(shifts.length)];
        shiftCount = shifts.length;
        assessmentError = '';
    }

    function removeShift(id: number) {
        if (shifts.length <= 1) {
            assessmentError = 'At least one shift is required.';
            return;
        }

        shifts = shifts
            .filter((shift) => shift.id !== id)
            .map((shift, index) => ({
                ...shift,
                id: index + 1,
                name: `Shift ${index + 1}`,
            }));

        shiftCount = shifts.length;
        assessmentError = '';
    }

    function updateShift(
        id: number,
        field: keyof Shift,
        value: string | number
    ) {
        shifts = shifts.map((shift) =>
            shift.id === id
                ? { ...shift, [field]: value }
                : shift
        );
    }

    function runAssessment() {
        assessmentError = '';

        if (!selectedFile) {
            assessmentError = 'Select an attendance file first.';
            return;
        }

        if (shifts.length === 0) {
            assessmentError = 'Configure at least one shift.';
            return;
        }

        for (const shift of shifts) {
            if (!shift.name.trim() || !shift.startTime || !shift.endTime) {
                assessmentError =
                    'Complete the name, start time, and end time for every shift.';
                return;
            }

            if (
                shift.startTime >= shift.endTime
            ) {
                assessmentError =
                    `${shift.name}: the end time must be later than the start time. Overnight shifts are not supported in this prototype.`;
                return;
            }

            if (
                shift.gracePeriod < 0 ||
                shift.earlyWindow < 0
            ) {
                assessmentError =
                    'Time allowances cannot be negative.';
                return;
            }
        }

        // Frontend prototype: pass configuration to the report page.
        // Actual analysis will be connected to imported records later.
        router.visit('/reports', {
            data: {
                file_id: selectedFile.id,
                file_name: selectedFile.name,
                assessment: assessmentType,
                shifts: JSON.stringify(shifts),
            },
        });

        closeAssessment();
    }

    function confirmDelete(file: AttendanceFile) {
        fileToDelete = file;
        showDeleteModal = true;
    }

    function deleteFile() {
        if (!fileToDelete) return;

        // Frontend preview only. No server-side deletion yet.
        files = files.filter((file) => file.id !== fileToDelete?.id);
        showDeleteModal = false;
        fileToDelete = null;
    }

    function cancelDelete() {
        showDeleteModal = false;
        fileToDelete = null;
    }
</script>

<svelte:head>
    <title>Imported Files | TENDAPP</title>
    <meta
        name="description"
        content="Manage attendance files and configure employee attendance assessments."
    />
</svelte:head>

<div class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <!-- Header -->
    <header class="border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-5 sm:px-8">
            <div>
                <p class="text-sm font-semibold tracking-wide text-blue-600 dark:text-blue-400">
                    TENDAPP
                </p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight">
                    Imported files
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Manage attendance data and run assessments.
                </p>
            </div>

            <button
                onclick={() => router.visit('/')}
                class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700"
            >
                <span aria-hidden="true">+</span>
                Import file
            </button>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-5 py-8 sm:px-8">
        <!-- Overview -->
        <section class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Total files
                </p>
                <p class="mt-2 text-3xl font-semibold">
                    {files.length}
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Imported attendance datasets
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Employee records
                </p>
                <p class="mt-2 text-3xl font-semibold">
                    {formatNumber(totalEmployees)}
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Across listed files
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Awaiting analysis
                </p>
                <p class="mt-2 text-3xl font-semibold">
                    {pendingFiles}
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Files not yet marked analyzed
                </p>
            </div>
        </section>

        <!-- Files toolbar -->
        <section class="mt-8">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-lg font-semibold">
                        Your attendance files
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {filteredFiles.length}
                        {filteredFiles.length === 1 ? 'file' : 'files'} found
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <input
                        bind:value={search}
                        type="search"
                        placeholder="Search files..."
                        aria-label="Search imported files"
                        class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-900 dark:focus:ring-blue-950 sm:w-64"
                    />

                    <div class="flex rounded-lg border border-slate-200 bg-white p-1 dark:border-slate-700 dark:bg-slate-900">
                        <button
                            onclick={() => (view = 'table')}
                            aria-pressed={view === 'table'}
                            class={`rounded-md px-3 py-1.5 text-sm ${view === 'table' ? 'bg-slate-100 font-medium dark:bg-slate-800' : 'text-slate-500'}`}
                        >
                            Table
                        </button>
                        <button
                            onclick={() => (view = 'cards')}
                            aria-pressed={view === 'cards'}
                            class={`rounded-md px-3 py-1.5 text-sm ${view === 'cards' ? 'bg-slate-100 font-medium dark:bg-slate-800' : 'text-slate-500'}`}
                        >
                            Cards
                        </button>
                    </div>
                </div>
            </div>

            {#if filteredFiles.length === 0}
                <div class="mt-5 rounded-xl border border-dashed border-slate-300 px-6 py-16 text-center dark:border-slate-700">
                    <div class="text-3xl text-slate-400" aria-hidden="true">
                        ▤
                    </div>
                    <h3 class="mt-3 font-semibold">No files found</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Try another search or import an attendance file.
                    </p>
                    {#if search}
                        <button
                            onclick={() => (search = '')}
                            class="mt-4 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400"
                        >
                            Clear search
                        </button>
                    {/if}
                </div>
            {:else if view === 'table'}
                <!-- Table view -->
                <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                    <table class="w-full min-w-[850px] text-left text-sm">
                        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400">
                            <tr>
                                <th class="px-5 py-4 font-medium">File name</th>
                                <th class="px-5 py-4 font-medium">Imported</th>
                                <th class="px-5 py-4 font-medium">Size</th>
                                <th class="px-5 py-4 font-medium">Employees</th>
                                <th class="px-5 py-4 font-medium">Status</th>
                                <th class="px-5 py-4 text-right font-medium">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            {#each filteredFiles as file (file.id)}
                                <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-xs font-bold text-blue-700 dark:bg-blue-950 dark:text-blue-300">
                                                CSV
                                            </div>
                                            <div>
                                                <p class="font-medium">
                                                    {file.name}
                                                </p>
                                                <p class="mt-1 text-xs text-slate-500">
                                                    {file.type} file
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-slate-600 dark:text-slate-300">
                                        {file.importedAt}
                                    </td>

                                    <td class="px-5 py-4 text-slate-600 dark:text-slate-300">
                                        {file.size}
                                    </td>

                                    <td class="px-5 py-4 font-medium">
                                        {formatNumber(file.employees)}
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class={`inline-flex rounded-full px-2.5 py-1 text-xs font-medium ${file.status === 'Analyzed' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300'}`}>
                                            {file.status}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                onclick={() => openAssessment(file)}
                                                class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-medium text-white transition hover:bg-blue-700"
                                            >
                                                Analyze
                                            </button>
                                            <button
                                                onclick={() => confirmDelete(file)}
                                                class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-600 transition hover:border-red-300 hover:text-red-600 dark:border-slate-700 dark:text-slate-300"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            {/each}
                        </tbody>
                    </table>
                </div>
            {:else}
                <!-- Card view -->
                <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    {#each filteredFiles as file (file.id)}
                        <article class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50 text-xs font-bold text-blue-700 dark:bg-blue-950 dark:text-blue-300">
                                    CSV
                                </div>

                                <span class={`rounded-full px-2.5 py-1 text-xs font-medium ${file.status === 'Analyzed' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300'}`}>
                                    {file.status}
                                </span>
                            </div>

                            <h3 class="mt-4 break-words font-semibold">
                                {file.name}
                            </h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Imported {file.importedAt}
                            </p>

                            <div class="mt-5 grid grid-cols-2 gap-3 border-y border-slate-100 py-4 dark:border-slate-800">
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Employee records
                                    </p>
                                    <p class="mt-1 font-semibold">
                                        {formatNumber(file.employees)}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">
                                        File size
                                    </p>
                                    <p class="mt-1 font-semibold">
                                        {file.size}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 flex gap-2">
                                <button
                                    onclick={() => openAssessment(file)}
                                    class="flex-1 rounded-lg bg-blue-600 px-3 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700"
                                >
                                    Analyze
                                </button>
                                <button
                                    onclick={() => confirmDelete(file)}
                                    class="rounded-lg border border-slate-200 px-3 py-2.5 text-sm font-medium text-slate-600 hover:border-red-300 hover:text-red-600 dark:border-slate-700 dark:text-slate-300"
                                >
                                    Delete
                                </button>
                            </div>
                        </article>
                    {/each}
                </div>
            {/if}
        </section>
    </main>
</div>

<!-- Assessment modal -->
{#if showAssessmentModal && selectedFile}
    <div class="fixed inset-0 z-40 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-[2px]">
        <div
            role="dialog"
            aria-modal="true"
            aria-labelledby="assessment-title"
            class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900"
        >
            <!-- Modal header -->
            <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-slate-200 bg-white px-6 py-5 dark:border-slate-800 dark:bg-slate-900 sm:px-7">
                <div>
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                        Attendance assessment
                    </p>
                    <h2 id="assessment-title" class="mt-1 text-xl font-semibold">
                        {modalStep === 'assessment'
                            ? 'Configure assessment'
                            : 'Configure shifts'}
                    </h2>
                    <p class="mt-1 break-all text-sm text-slate-500 dark:text-slate-400">
                        {selectedFile.name}
                    </p>
                </div>

                <button
                    onclick={closeAssessment}
                    aria-label="Close assessment"
                    class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 dark:hover:bg-slate-800"
                >
                    ✕
                </button>
            </div>

            <div class="space-y-6 px-6 py-6 sm:px-7">
                {#if modalStep === 'assessment'}
                    <div>
                        <h3 class="text-sm font-semibold">
                            1. Choose an assessment
                        </h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Select the attendance pattern you want to investigate.
                        </p>
                    </div>

                    <div class="space-y-3">
                        <label class={`flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition ${assessmentType === 'late' ? 'border-blue-500 bg-blue-50 dark:bg-blue-950/40' : 'border-slate-200 hover:border-slate-300 dark:border-slate-700 dark:hover:border-slate-600'}`}>
                            <input
                                bind:group={assessmentType}
                                type="radio"
                                value="late"
                                class="mt-1 accent-blue-600"
                            />
                            <span>
                                <span class="block font-medium">Late Arrivals</span>
                                <span class="mt-1 block text-sm leading-5 text-slate-500 dark:text-slate-400">
                                    Identify employees who clocked in after their scheduled start time and grace period.
                                </span>
                            </span>
                        </label>

                        <label class={`flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition ${assessmentType === 'early' ? 'border-blue-500 bg-blue-50 dark:bg-blue-950/40' : 'border-slate-200 hover:border-slate-300 dark:border-slate-700 dark:hover:border-slate-600'}`}>
                            <input
                                bind:group={assessmentType}
                                type="radio"
                                value="early"
                                class="mt-1 accent-blue-600"
                            />
                            <span>
                                <span class="block font-medium">Early Arrivals</span>
                                <span class="mt-1 block text-sm leading-5 text-slate-500 dark:text-slate-400">
                                    Identify employees who clocked in earlier than the configured arrival window.
                                </span>
                            </span>
                        </label>

                        <label class={`flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition ${assessmentType === 'perfect' ? 'border-blue-500 bg-blue-50 dark:bg-blue-950/40' : 'border-slate-200 hover:border-slate-300 dark:border-slate-700 dark:hover:border-slate-600'}`}>
                            <input
                                bind:group={assessmentType}
                                type="radio"
                                value="perfect"
                                class="mt-1 accent-blue-600"
                            />
                            <span>
                                <span class="block font-medium">Perfect Attendance</span>
                                <span class="mt-1 block text-sm leading-5 text-slate-500 dark:text-slate-400">
                                    Find employees who satisfy the configured attendance requirements.
                                </span>
                            </span>
                        </label>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                        <label for="shift-count" class="block text-sm font-semibold">
                            2. How many shifts should be assessed?
                        </label>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Configure each shift separately in the next step.
                        </p>

                        <div class="mt-4 flex items-center gap-3">
                            <button
                                onclick={() => updateShiftCount(Math.max(1, shiftCount - 1))}
                                class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-lg hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800"
                                aria-label="Decrease number of shifts"
                            >
                                −
                            </button>

                            <input
                                id="shift-count"
                                type="number"
                                min="1"
                                max="10"
                                bind:value={shiftCount}
                                class="h-10 w-24 rounded-lg border border-slate-200 bg-white px-3 text-center font-semibold outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-950"
                            />

                            <button
                                onclick={() => updateShiftCount(Math.min(10, Number(shiftCount) + 1))}
                                class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-lg hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800"
                                aria-label="Increase number of shifts"
                            >
                                +
                            </button>

                            <span class="text-sm text-slate-500 dark:text-slate-400">
                                Maximum 10
                            </span>
                        </div>
                    </div>
                {:else}
                    <div>
                        <h3 class="text-sm font-semibold">
                            Shift criteria
                        </h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Set the schedule and assessment thresholds for each shift.
                        </p>
                    </div>

                    <div class="space-y-4">
                        {#each shifts as shift (shift.id)}
                            <section class="rounded-xl border border-slate-200 p-4 dark:border-slate-700 sm:p-5">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <h4 class="font-semibold">{shift.name}</h4>
                                        <p class="mt-1 text-xs text-slate-500">
                                            Shift {shift.id} of {shifts.length}
                                        </p>
                                    </div>

                                    {#if shifts.length > 1}
                                        <button
                                            onclick={() => removeShift(shift.id)}
                                            class="text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400"
                                        >
                                            Remove
                                        </button>
                                    {/if}
                                </div>

                                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                                        <label for={`shift-name-${shift.id}`} class="mb-1.5 block text-sm font-medium">
                                            Shift name
                                        </label>
                                        <input
                                            id={`shift-name-${shift.id}`}
                                            value={shift.name}
                                            oninput={(event) => updateShift(shift.id, 'name', event.currentTarget.value)}
                                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-950"
                                            placeholder="e.g. Morning Shift"
                                        />
                                    </div>

                                    <div>
                                        <label for={`shift-start-${shift.id}`} class="mb-1.5 block text-sm font-medium">
                                            Scheduled start
                                        </label>
                                        <input
                                            id={`shift-start-${shift.id}`}
                                            type="time"
                                            value={shift.startTime}
                                            oninput={(event) => updateShift(shift.id, 'startTime', event.currentTarget.value)}
                                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-950"
                                        />
                                    </div>

                                    <div>
                                        <label for={`shift-end-${shift.id}`} class="mb-1.5 block text-sm font-medium">
                                            Scheduled end
                                        </label>
                                        <input
                                            id={`shift-end-${shift.id}`}
                                            type="time"
                                            value={shift.endTime}
                                            oninput={(event) => updateShift(shift.id, 'endTime', event.currentTarget.value)}
                                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-950"
                                        />
                                    </div>

                                    {#if assessmentType === 'late'}
                                        <div class="sm:col-span-2">
                                            <label for={`grace-${shift.id}`} class="mb-1.5 block text-sm font-medium">
                                                Grace period (minutes)
                                            </label>
                                            <input
                                                id={`grace-${shift.id}`}
                                                type="number"
                                                min="0"
                                                value={shift.gracePeriod}
                                                oninput={(event) => updateShift(shift.id, 'gracePeriod', Math.max(0, Number(event.currentTarget.value)))}
                                                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-950"
                                            />
                                            <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">
                                                Example: an 08:00 start with 10 minutes' grace means arrivals after 08:10 are late.
                                            </p>
                                        </div>
                                    {:else if assessmentType === 'early'}
                                        <div class="sm:col-span-2">
                                            <label for={`early-window-${shift.id}`} class="mb-1.5 block text-sm font-medium">
                                                Early arrival window (minutes)
                                            </label>
                                            <input
                                                id={`early-window-${shift.id}`}
                                                type="number"
                                                min="0"
                                                value={shift.earlyWindow}
                                                oninput={(event) => updateShift(shift.id, 'earlyWindow', Math.max(0, Number(event.currentTarget.value)))}
                                                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-950"
                                            />
                                            <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">
                                                Example: a 30-minute window for an 08:00 start flags arrivals before 07:30.
                                            </p>
                                        </div>
                                    {:else}
                                        <div class="sm:col-span-2 rounded-lg bg-slate-50 p-3 text-sm text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                            Perfect attendance will use the configured shift schedule to assess attendance. Its exact rules will be finalized in the results workspace.
                                        </div>
                                    {/if}
                                </div>
                            </section>
                        {/each}
                    </div>

                    <button
                        onclick={addShift}
                        disabled={shifts.length >= 10}
                        class="w-full rounded-lg border border-dashed border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-blue-400 hover:text-blue-600 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:text-slate-300"
                    >
                        + Add another shift
                    </button>
                {/if}

                {#if assessmentError}
                    <p role="alert" class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-950/40 dark:text-red-300">
                        {assessmentError}
                    </p>
                {/if}
            </div>

            <!-- Modal footer -->
            <div class="sticky bottom-0 flex flex-col-reverse justify-between gap-3 border-t border-slate-200 bg-white px-6 py-4 dark:border-slate-800 dark:bg-slate-900 sm:flex-row sm:items-center sm:px-7">
                {#if modalStep === 'assessment'}
                    <button
                        onclick={closeAssessment}
                        class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800"
                    >
                        Cancel
                    </button>

                    <button
                        onclick={configureShifts}
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        Configure shifts
                        <span aria-hidden="true">→</span>
                    </button>
                {:else}
                    <button
                        onclick={() => {
                            modalStep = 'assessment';
                            assessmentError = '';
                        }}
                        class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800"
                    >
                        ← Back
                    </button>

                    <button
                        onclick={runAssessment}
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        Continue to results
                        <span aria-hidden="true">→</span>
                    </button>
                {/if}
            </div>
        </div>
    </div>
{/if}

<!-- Delete confirmation -->
{#if showDeleteModal && fileToDelete}
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4">
        <div
            role="dialog"
            aria-modal="true"
            aria-labelledby="delete-title"
            class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-700 dark:bg-slate-900"
        >
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-red-50 text-xl font-semibold text-red-600 dark:bg-red-950 dark:text-red-300">
                !
            </div>

            <h2 id="delete-title" class="mt-4 text-lg font-semibold">
                Delete this file?
            </h2>

            <p class="mt-2 break-words text-sm leading-6 text-slate-600 dark:text-slate-300">
                Remove <strong>{fileToDelete.name}</strong> from this screen? This frontend preview does not delete the original file from the server.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    onclick={cancelDelete}
                    class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800"
                >
                    Cancel
                </button>

                <button
                    onclick={deleteFile}
                    class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-700"
                >
                    Delete file
                </button>
            </div>
        </div>
    </div>
{/if}
