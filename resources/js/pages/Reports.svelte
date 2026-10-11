
<script lang="ts">
    import { router } from "@inertiajs/svelte";

    type AssessmentType = 'late' | 'early' | 'perfect';

    type AttendanceDay = {
        date: string;
        shift: string;
        scheduled: string;
        clockIn: string | null;
        status: 'Late' | 'On time' | 'Early' | 'Absent';
        minutes: number;
    };

    type Employee = {
        id: string;
        name: string;
        department: string;
        shift: string;
        days: AttendanceDay[];
    };

    type ShiftSetting = {
        id: number;
        name: string;
        startTime: string;
        endTime: string;
        gracePeriod: number;
        earlyWindow: number;
    };

    let assessment: AssessmentType = 'late';
    let fileName = 'September_Attendance.csv';

    let month = '2026-09';
    let search = '';
    let departmentFilter = 'All departments';
    let shiftFilter = 'All shifts';
    let outcomeFilter = 'All outcomes';

    let expandedEmployees: string[] = [];

    let gracePeriod = 10;
    let earlyWindow = 30;
    let shiftSettings: ShiftSetting[] = [
        {
            id: 1,
            name: 'Morning Shift',
            startTime: '08:00',
            endTime: '16:00',
            gracePeriod: 10,
            earlyWindow: 30,
        },
        {
            id: 2,
            name: 'Afternoon Shift',
            startTime: '14:00',
            endTime: '22:00',
            gracePeriod: 10,
            earlyWindow: 30,
        },
        {
            id: 3,
            name: 'Night Shift',
            startTime: '19:30',
            endTime: '07:30',
            gracePeriod: 10,
            earlyWindow: 30,
        },
    ];

    let appliedAssessment: AssessmentType = 'late';
    let appliedGracePeriod = 10;
    let appliedEarlyWindow = 30;
    let appliedShiftSettings: ShiftSetting[] = shiftSettings.map((shift) => ({ ...shift }));

    let settingsChanged = false;
    let updateMessage = '';

    const departments = [
        'Nursing',
        'Administration',
        'Laboratory',
        'Pharmacy',
        'Radiology',
        'Finance',
    ];

    const shifts = ['Morning Shift', 'Afternoon Shift', 'Night Shift'];

    const rawEmployees: Employee[] = [
        {
            id: 'EMP-001',
            name: 'Adebayo Grace',
            department: 'Nursing',
            shift: 'Morning Shift',
            days: [
                { date: '2026-09-01', shift: 'Morning Shift', scheduled: '08:00', clockIn: '07:52', status: 'Early', minutes: 8 },
                { date: '2026-09-02', shift: 'Morning Shift', scheduled: '08:00', clockIn: '08:14', status: 'Late', minutes: 14 },
                { date: '2026-09-03', shift: 'Morning Shift', scheduled: '08:00', clockIn: '07:58', status: 'On time', minutes: 0 },
                { date: '2026-09-04', shift: 'Morning Shift', scheduled: '08:00', clockIn: '08:21', status: 'Late', minutes: 21 },
                { date: '2026-09-07', shift: 'Morning Shift', scheduled: '08:00', clockIn: '08:05', status: 'On time', minutes: 0 },
            ],
        },
        {
            id: 'EMP-002',
            name: 'Okafor Daniel',
            department: 'Laboratory',
            shift: 'Morning Shift',
            days: [
                { date: '2026-09-01', shift: 'Morning Shift', scheduled: '08:00', clockIn: '07:45', status: 'Early', minutes: 15 },
                { date: '2026-09-02', shift: 'Morning Shift', scheduled: '08:00', clockIn: '07:50', status: 'Early', minutes: 10 },
                { date: '2026-09-03', shift: 'Morning Shift', scheduled: '08:00', clockIn: '07:57', status: 'On time', minutes: 0 },
                { date: '2026-09-04', shift: 'Morning Shift', scheduled: '08:00', clockIn: '08:02', status: 'On time', minutes: 0 },
                { date: '2026-09-07', shift: 'Morning Shift', scheduled: '08:00', clockIn: '08:19', status: 'Late', minutes: 19 },
            ],
        },
        {
            id: 'EMP-003',
            name: 'Ibrahim Fatima',
            department: 'Pharmacy',
            shift: 'Afternoon Shift',
            days: [
                { date: '2026-09-01', shift: 'Afternoon Shift', scheduled: '14:00', clockIn: '13:42', status: 'Early', minutes: 18 },
                { date: '2026-09-02', shift: 'Afternoon Shift', scheduled: '14:00', clockIn: '14:03', status: 'On time', minutes: 0 },
                { date: '2026-09-03', shift: 'Afternoon Shift', scheduled: '14:00', clockIn: '14:17', status: 'Late', minutes: 17 },
                { date: '2026-09-04', shift: 'Afternoon Shift', scheduled: '14:00', clockIn: null, status: 'Absent', minutes: 0 },
                { date: '2026-09-07', shift: 'Afternoon Shift', scheduled: '14:00', clockIn: '13:55', status: 'On time', minutes: 0 },
            ],
        },
        {
            id: 'EMP-004',
            name: 'Bello Samuel',
            department: 'Administration',
            shift: 'Morning Shift',
            days: [
                { date: '2026-09-01', shift: 'Morning Shift', scheduled: '08:00', clockIn: '07:56', status: 'On time', minutes: 0 },
                { date: '2026-09-02', shift: 'Morning Shift', scheduled: '08:00', clockIn: '07:53', status: 'On time', minutes: 0 },
                { date: '2026-09-03', shift: 'Morning Shift', scheduled: '08:00', clockIn: '07:59', status: 'On time', minutes: 0 },
                { date: '2026-09-04', shift: 'Morning Shift', scheduled: '08:00', clockIn: '07:51', status: 'On time', minutes: 0 },
                { date: '2026-09-07', shift: 'Morning Shift', scheduled: '08:00', clockIn: '07:58', status: 'On time', minutes: 0 },
            ],
        },
        {
            id: 'EMP-005',
            name: 'Eze Chiamaka',
            department: 'Radiology',
            shift: 'Night Shift',
            days: [
                { date: '2026-09-01', shift: 'Night Shift', scheduled: '19:30', clockIn: '19:49', status: 'Late', minutes: 19 },
                { date: '2026-09-02', shift: 'Night Shift', scheduled: '19:30', clockIn: '19:25', status: 'On time', minutes: 0 },
                { date: '2026-09-03', shift: 'Night Shift', scheduled: '19:30', clockIn: '19:46', status: 'Late', minutes: 16 },
                { date: '2026-09-04', shift: 'Night Shift', scheduled: '19:30', clockIn: '19:20', status: 'Early', minutes: 10 },
                { date: '2026-09-07', shift: 'Night Shift', scheduled: '19:30', clockIn: '19:32', status: 'On time', minutes: 0 },
            ],
        },
        {
            id: 'EMP-006',
            name: 'Mohammed Yusuf',
            department: 'Finance',
            shift: 'Afternoon Shift',
            days: [
                { date: '2026-09-01', shift: 'Afternoon Shift', scheduled: '14:00', clockIn: '14:00', status: 'On time', minutes: 0 },
                { date: '2026-09-02', shift: 'Afternoon Shift', scheduled: '14:00', clockIn: '13:59', status: 'On time', minutes: 0 },
                { date: '2026-09-03', shift: 'Afternoon Shift', scheduled: '14:00', clockIn: '14:08', status: 'On time', minutes: 0 },
                { date: '2026-09-04', shift: 'Afternoon Shift', scheduled: '14:00', clockIn: '14:01', status: 'On time', minutes: 0 },
                { date: '2026-09-07', shift: 'Afternoon Shift', scheduled: '14:00', clockIn: '14:04', status: 'On time', minutes: 0 },
            ],
        },
    ];

    function parseTime(value: string | null): number | null {
        if (!value) return null;
        const [hours, minutes] = value.split(':').map(Number);
        return hours * 60 + minutes;
    }

    function formatDate(value: string): string {
        return new Date(`${value}T12:00:00`).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        });
    }

    function formatDuration(minutes: number): string {
        return `${minutes} min`;
    }

    function getShiftSetting(name: string, settings = appliedShiftSettings) {
        return settings.find((shift) => shift.name === name) ?? settings[0];
    }

    function getEvaluatedStatus(day: AttendanceDay, type = appliedAssessment): AttendanceDay['status'] {
        if (!day.clockIn) return 'Absent';

        const setting = getShiftSetting(day.shift);
        const clock = parseTime(day.clockIn);
        const scheduled = parseTime(day.scheduled);

        if (clock === null || scheduled === null) return 'Absent';

        if (type === 'late') {
            const grace = setting?.gracePeriod ?? appliedGracePeriod;
            return clock > scheduled + grace ? 'Late' : 'On time';
        }

        if (type === 'early') {
            const earlyLimit = setting?.earlyWindow ?? appliedEarlyWindow;
            return clock < scheduled - earlyLimit ? 'Early' : 'On time';
        }

        return 'On time';
    }

    function getRelevantDays(employee: Employee) {
        return employee.days.filter((day) => day.date.startsWith(month));
    }

    function getEmployeeCount(employee: Employee) {
        const days = getRelevantDays(employee);

        if (appliedAssessment === 'late') {
            return days.filter((day) => getEvaluatedStatus(day) === 'Late').length;
        }

        if (appliedAssessment === 'early') {
            return days.filter((day) => getEvaluatedStatus(day) === 'Early').length;
        }

        return days.filter((day) => getEvaluatedStatus(day) !== 'Absent').length;
    }

    function getEmployeeMinutes(employee: Employee) {
        return getRelevantDays(employee).reduce((total, day) => {
            if (appliedAssessment === 'late' && getEvaluatedStatus(day) === 'Late') {
                const clock = parseTime(day.clockIn);
                const setting = getShiftSetting(day.shift);
                const scheduled = parseTime(day.scheduled);
                const grace = setting?.gracePeriod ?? appliedGracePeriod;

                if (clock !== null && scheduled !== null) {
                    return total + Math.max(0, clock - scheduled - grace);
                }
            }

            if (appliedAssessment === 'early' && getEvaluatedStatus(day) === 'Early') {
                const clock = parseTime(day.clockIn);
                const scheduled = parseTime(day.scheduled);

                if (clock !== null && scheduled !== null) {
                    return total + Math.max(0, scheduled - clock);
                }
            }

            return total;
        }, 0);
    }

    function getEmployeeOutcome(employee: Employee) {
        const days = getRelevantDays(employee);

        if (appliedAssessment === 'late') {
            return days.some((day) => getEvaluatedStatus(day) === 'Late') ? 'Flagged' : 'No late arrivals';
        }

        if (appliedAssessment === 'early') {
            return days.some((day) => getEvaluatedStatus(day) === 'Early') ? 'Flagged' : 'No early arrivals';
        }

        return days.length > 0 && days.every((day) => getEvaluatedStatus(day) !== 'Absent')
            ? 'Perfect attendance'
            : 'Review required';
    }

    function getStatusClass(status: string) {
        if (status === 'Late' || status === 'Flagged' || status === 'Review required') {
            return 'bg-red-50 text-red-700 dark:bg-red-950 dark:text-red-300';
        }

        if (status === 'Early') {
            return 'bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300';
        }

        if (status === 'Absent') {
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300';
        }

        if (status === 'Perfect attendance' || status === 'No late arrivals' || status === 'No early arrivals') {
            return 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300';
        }

        return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300';
    }

    $: employeeRows = rawEmployees
        .filter((employee) => {
            const matchesSearch =
                employee.name.toLowerCase().includes(search.toLowerCase()) ||
                employee.id.toLowerCase().includes(search.toLowerCase());

            const matchesDepartment =
                departmentFilter === 'All departments' ||
                employee.department === departmentFilter;

            const matchesShift =
                shiftFilter === 'All shifts' ||
                employee.shift === shiftFilter;

            const outcome = getEmployeeOutcome(employee);

            const matchesOutcome =
                outcomeFilter === 'All outcomes' ||
                (outcomeFilter === 'Flagged' && outcome === 'Flagged') ||
                (outcomeFilter === 'No issues' &&
                    (outcome === 'No late arrivals' ||
                     outcome === 'No early arrivals' ||
                     outcome === 'Perfect attendance'));

            return matchesSearch && matchesDepartment && matchesShift && matchesOutcome;
        })
        .map((employee) => ({
            ...employee,
            days: getRelevantDays(employee),
            count: getEmployeeCount(employee),
            minutes: getEmployeeMinutes(employee),
            outcome: getEmployeeOutcome(employee),
        }))
        .sort((a, b) => b.count - a.count);

    $: totalOccurrences = rawEmployees.reduce((total, employee) => total + getEmployeeCount(employee), 0);

    $: flaggedEmployees = rawEmployees.filter((employee) => {
        if (appliedAssessment === 'perfect') {
            return getEmployeeOutcome(employee) === 'Review required';
        }
        return getEmployeeCount(employee) > 0;
    }).length;

    $: totalDays = rawEmployees.reduce((total, employee) => total + getRelevantDays(employee).length, 0);

    $: issueRate = totalDays
        ? Math.round(
            (rawEmployees.reduce((total, employee) => {
                if (appliedAssessment === 'perfect') {
                    return total + getRelevantDays(employee).filter((day) => getEvaluatedStatus(day) === 'Absent').length;
                }
                return total + getRelevantDays(employee).filter((day) => {
                    const status = getEvaluatedStatus(day);
                    return appliedAssessment === 'late' ? status === 'Late' : status === 'Early';
                }).length;
            }, 0) / totalDays) * 100
        )
        : 0;

    function toggleExpanded(id: string) {
        expandedEmployees = expandedEmployees.includes(id)
            ? expandedEmployees.filter((item) => item !== id)
            : [...expandedEmployees, id];
    }

    function markSettingsChanged() {
        settingsChanged = true;
        updateMessage = '';
    }

    function updateResults() {
        appliedAssessment = assessment;
        appliedGracePeriod = gracePeriod;
        appliedEarlyWindow = earlyWindow;
        appliedShiftSettings = shiftSettings.map((shift) => ({ ...shift }));
        settingsChanged = false;
        updateMessage = 'Results updated using your current settings.';
    }

    function exportCsv() {
        const headers = [
            'Employee ID',
            'Employee Name',
            'Department',
            'Shift',
            'Occurrences',
            'Total Minutes',
            'Outcome',
            'Date',
            'Clock In',
            'Scheduled Start',
            'Daily Status',
        ];

        const rows: string[][] = [];

        for (const employee of employeeRows) {
            const days = getRelevantDays(employee);

            if (!days.length) {
                rows.push([
                    employee.id,
                    employee.name,
                    employee.department,
                    employee.shift,
                    String(getEmployeeCount(employee)),
                    String(getEmployeeMinutes(employee)),
                    getEmployeeOutcome(employee),
                    '',
                    '',
                    '',
                    '',
                ]);
                continue;
            }

            for (const day of days) {
                rows.push([
                    employee.id,
                    employee.name,
                    employee.department,
                    day.shift,
                    String(getEmployeeCount(employee)),
                    String(getEmployeeMinutes(employee)),
                    getEmployeeOutcome(employee),
                    day.date,
                    day.clockIn ?? 'No record',
                    day.scheduled,
                    getEvaluatedStatus(day),
                ]);
            }
        }

        const escapeCsv = (value: string) =>
            `"${value.replace(/"/g, '""')}"`;

        const csv = [headers, ...rows]
            .map((row) => row.map(escapeCsv).join(','))
            .join('\r\n');

        const blob = new Blob(['\uFEFF' + csv], {
            type: 'text/csv;charset=utf-8;',
        });

        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');

        link.href = url;
        link.download = `${month}-${appliedAssessment}-attendance.csv`;
        link.click();

        URL.revokeObjectURL(url);
    }

    function exportExcel() {
        // Excel can open CSV files directly.
        // A true .xlsx workbook can be added later with a spreadsheet library.
        exportCsv();
    }

    function resetFilters() {
        search = '';
        departmentFilter = 'All departments';
        shiftFilter = 'All shifts';
        outcomeFilter = 'All outcomes';
    }

    function assessmentLabel(type: AssessmentType) {
        if (type === 'late') return 'Late Arrivals';
        if (type === 'early') return 'Early Arrivals';
        return 'Perfect Attendance';
    }

    function handleAssessmentChange(value: AssessmentType) {
        assessment = value;
        markSettingsChanged();
    }
</script>

<svelte:head>
    <title>Attendance Reports | TENDAPP</title>
    <meta name="description" content="Review monthly employee attendance assessment results." />
</svelte:head>

<div class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <header class="border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-5 sm:px-8">
            <div>
                <button
                    onclick={() => router.visit('/imported-files')}
                    class="mb-2 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400"
                >
                    ← Imported files
                </button>
                <h1 class="text-2xl font-semibold tracking-tight">Attendance reports</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Monthly assessment results for {fileName}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button
                    onclick={exportCsv}
                    class="rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:hover:bg-slate-800"
                >
                    Export CSV
                </button>
                <button
                    onclick={exportExcel}
                    class="rounded-lg bg-blue-600 px-3.5 py-2.5 text-sm font-medium text-white hover:bg-blue-700"
                >
                    Export Excel
                </button>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-5 py-8 sm:px-8">
        <!-- Report settings -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900 sm:p-6">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start">
                <div>
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                        Report configuration
                    </p>
                    <h2 class="mt-1 text-lg font-semibold">Assessment settings</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Edit criteria, then update the results when you're ready.
                    </p>
                </div>

                {#if settingsChanged}
                    <span class="inline-flex w-fit items-center gap-2 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700 dark:bg-amber-950 dark:text-amber-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                        Changes not applied
                    </span>
                {:else}
                    <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Results up to date
                    </span>
                {/if}
            </div>

            <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <div>
                    <label for="assessment-type" class="mb-1.5 block text-sm font-medium">
                        Assessment type
                    </label>
                    <select
                        id="assessment-type"
                        value={assessment}
                        onchange={(event) => handleAssessmentChange(event.currentTarget.value as AssessmentType)}
                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-950"
                    >
                        <option value="late">Late Arrivals</option>
                        <option value="early">Early Arrivals</option>
                        <option value="perfect">Perfect Attendance</option>
                    </select>
                </div>

                <div>
                    <label for="report-month" class="mb-1.5 block text-sm font-medium">
                        Reporting month
                    </label>
                    <input
                        id="report-month"
                        type="month"
                        bind:value={month}
                        onchange={markSettingsChanged}
                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-950"
                    />
                </div>

                {#if assessment === 'late'}
                    <div>
                        <label for="grace-period" class="mb-1.5 block text-sm font-medium">
                            Default grace period (minutes)
                        </label>
                        <input
                            id="grace-period"
                            type="number"
                            min="0"
                            bind:value={gracePeriod}
                            oninput={markSettingsChanged}
                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-950"
                        />
                    </div>
                {:else if assessment === 'early'}
                    <div>
                        <label for="early-window" class="mb-1.5 block text-sm font-medium">
                            Early window (minutes)
                        </label>
                        <input
                            id="early-window"
                            type="number"
                            min="0"
                            bind:value={earlyWindow}
                            oninput={markSettingsChanged}
                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-950"
                        />
                    </div>
                {:else}
                    <div>
                        <!-- svelte-ignore a11y_label_has_associated_control -->
                        <label class="mb-1.5 block text-sm font-medium">
                            Attendance rule
                        </label>
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300">
                            No absent days
                        </div>
                    </div>
                {/if}

                <div>
                    <label for="shift-count-display" class="mb-1.5 block text-sm font-medium">
                        Configured shifts
                    </label>
                    <div id="shift-count-display" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300">
                        {shiftSettings.length} shifts
                    </div>
                </div>
            </div>

            <div class="mt-6 border-t border-slate-100 pt-5 dark:border-slate-800">
                <h3 class="text-sm font-semibold">Shift schedules</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    These sample shift settings can be edited before updating the results.
                </p>

                <div class="mt-4 grid gap-4 lg:grid-cols-3">
                    {#each shiftSettings as shift (shift.id)}
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                            <label for={`report-shift-name-${shift.id}`} class="mb-1.5 block text-sm font-medium">
                                Shift name
                            </label>
                            <input
                                id={`report-shift-name-${shift.id}`}
                                bind:value={shift.name}
                                oninput={markSettingsChanged}
                                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950"
                            />

                            <div class="mt-3 grid grid-cols-2 gap-3">
                                <div>
                                    <label for={`report-shift-start-${shift.id}`} class="mb-1.5 block text-xs font-medium text-slate-500">
                                        Start
                                    </label>
                                    <input
                                        id={`report-shift-start-${shift.id}`}
                                        type="time"
                                        bind:value={shift.startTime}
                                        oninput={markSettingsChanged}
                                        class="w-full rounded-lg border border-slate-200 bg-white px-2 py-2 text-sm dark:border-slate-700 dark:bg-slate-950"
                                    />
                                </div>
                                <div>
                                    <label for={`report-shift-end-${shift.id}`} class="mb-1.5 block text-xs font-medium text-slate-500">
                                        End
                                    </label>
                                    <input
                                        id={`report-shift-end-${shift.id}`}
                                        type="time"
                                        bind:value={shift.endTime}
                                        oninput={markSettingsChanged}
                                        class="w-full rounded-lg border border-slate-200 bg-white px-2 py-2 text-sm dark:border-slate-700 dark:bg-slate-950"
                                    />
                                </div>
                            </div>

                            {#if assessment === 'late'}
                                <label for={`report-grace-${shift.id}`} class="mb-1.5 mt-3 block text-xs font-medium text-slate-500">
                                    Grace period (minutes)
                                </label>
                                <input
                                    id={`report-grace-${shift.id}`}
                                    type="number"
                                    min="0"
                                    bind:value={shift.gracePeriod}
                                    oninput={markSettingsChanged}
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950"
                                />
                            {:else if assessment === 'early'}
                                <label for={`report-early-${shift.id}`} class="mb-1.5 mt-3 block text-xs font-medium text-slate-500">
                                    Early window (minutes)
                                </label>
                                <input
                                    id={`report-early-${shift.id}`}
                                    type="number"
                                    min="0"
                                    bind:value={shift.earlyWindow}
                                    oninput={markSettingsChanged}
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950"
                                />
                            {/if}
                        </div>
                    {/each}
                </div>
            </div>

            <div class="mt-6 flex flex-col justify-between gap-3 border-t border-slate-100 pt-5 dark:border-slate-800 sm:flex-row sm:items-center">
                <p class="max-w-xl text-xs leading-5 text-slate-500 dark:text-slate-400">
                    Changes won't affect the displayed results until you click Update results.
                </p>

                <button
                    onclick={updateResults}
                    class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700"
                >
                    Update results
                </button>
            </div>

            {#if updateMessage}
                <p role="status" class="mt-3 text-sm text-emerald-700 dark:text-emerald-400">
                    {updateMessage}
                </p>
            {/if}
        </section>

        <!-- Monthly overview -->
        <section class="mt-8">
            <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-end">
                <div>
                    <h2 class="text-lg font-semibold">Monthly overview</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {assessmentLabel(appliedAssessment)} · {month}
                    </p>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Sample report data
                </p>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Employees assessed</p>
                    <p class="mt-2 text-3xl font-semibold">{rawEmployees.length}</p>
                    <p class="mt-1 text-xs text-slate-500">In this sample dataset</p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {appliedAssessment === 'perfect' ? 'Needs review' : 'Employees flagged'}
                    </p>
                    <p class="mt-2 text-3xl font-semibold">{flaggedEmployees}</p>
                    <p class="mt-1 text-xs text-slate-500">
                        Employees with assessment findings
                    </p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {appliedAssessment === 'perfect' ? 'Absent records' : 'Total occurrences'}
                    </p>
                    <p class="mt-2 text-3xl font-semibold">
                        {appliedAssessment === 'perfect'
                            ? rawEmployees.reduce((total, employee) =>
                                total + getRelevantDays(employee).filter((day) => getEvaluatedStatus(day) === 'Absent').length, 0)
                            : totalOccurrences}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">
                        Across recorded attendance days
                    </p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {appliedAssessment === 'perfect' ? 'Attendance issue rate' : 'Employees flagged'}
                    </p>
                    <p class="mt-2 text-3xl font-semibold">
                        {appliedAssessment === 'perfect' ? `${issueRate}%` : `${flaggedEmployees}/${rawEmployees.length}`}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">
                        Based on sample records
                    </p>
                </div>
            </div>
        </section>

        <!-- Employee results -->
        <section class="mt-8">
            <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
                <div>
                    <h2 class="text-lg font-semibold">Employee results</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Select an employee row to view daily attendance details.
                    </p>
                </div>

                <button
                    onclick={resetFilters}
                    class="w-fit text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400"
                >
                    Reset filters
                </button>
            </div>

            <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <input
                    bind:value={search}
                    type="search"
                    placeholder="Search employee or ID..."
                    aria-label="Search employees"
                    class="rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900"
                />

                <select
                    bind:value={departmentFilter}
                    aria-label="Filter by department"
                    class="rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-900"
                >
                    <option>All departments</option>
                    {#each departments as department}
                        <option value={department}>{department}</option>
                    {/each}
                </select>

                <select
                    bind:value={shiftFilter}
                    aria-label="Filter by shift"
                    class="rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-900"
                >
                    <option>All shifts</option>
                    {#each shifts as shift}
                        <option value={shift}>{shift}</option>
                    {/each}
                </select>

                <select
                    bind:value={outcomeFilter}
                    aria-label="Filter by outcome"
                    class="rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-900"
                >
                    <option>All outcomes</option>
                    <option value="Flagged">Flagged</option>
                    <option value="No issues">No issues</option>
                </select>
            </div>

            <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400">
                        <tr>
                            <th class="w-10 px-4 py-4"></th>
                            <th class="px-4 py-4 font-medium">Employee</th>
                            <th class="px-4 py-4 font-medium">Department</th>
                            <th class="px-4 py-4 font-medium">Shift</th>
                            <th class="px-4 py-4 font-medium">
                                {appliedAssessment === 'late'
                                    ? 'Late occurrences'
                                    : appliedAssessment === 'early'
                                        ? 'Early occurrences'
                                        : 'Days present'}
                            </th>
                            <th class="px-4 py-4 font-medium">
                                {appliedAssessment === 'perfect' ? 'Days absent' : 'Minutes'}
                            </th>
                            <th class="px-4 py-4 font-medium">Outcome</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        {#each employeeRows as employee (employee.id)}
                            <tr>
                                <td colspan="7" class="p-0">
                                    <button
                                        onclick={() => toggleExpanded(employee.id)}
                                        aria-expanded={expandedEmployees.includes(employee.id)}
                                        class="grid w-full grid-cols-[40px_minmax(170px,1.4fr)_minmax(130px,1fr)_minmax(130px,1fr)_minmax(120px,1fr)_minmax(100px,0.7fr)_minmax(160px,1fr)] items-center text-left transition hover:bg-slate-50 dark:hover:bg-slate-800/50"
                                    >
                                        <span class="px-4 py-4 text-slate-400">
                                            {expandedEmployees.includes(employee.id) ? '⌄' : '›'}
                                        </span>

                                        <span class="px-4 py-4">
                                            <span class="block font-medium">{employee.name}</span>
                                            <span class="mt-1 block text-xs text-slate-500">{employee.id}</span>
                                        </span>

                                        <span class="px-4 py-4 text-slate-600 dark:text-slate-300">
                                            {employee.department}
                                        </span>

                                        <span class="px-4 py-4 text-slate-600 dark:text-slate-300">
                                            {employee.shift}
                                        </span>

                                        <span class="px-4 py-4 font-medium">
                                            {appliedAssessment === 'perfect'
                                                ? getRelevantDays(employee).filter((day) => getEvaluatedStatus(day) !== 'Absent').length
                                                : employee.count}
                                        </span>

                                        <span class="px-4 py-4 text-slate-600 dark:text-slate-300">
                                            {appliedAssessment === 'perfect'
                                                ? getRelevantDays(employee).filter((day) => getEvaluatedStatus(day) === 'Absent').length
                                                : formatDuration(employee.minutes)}
                                        </span>

                                        <span class="px-4 py-4">
                                            <span class={`inline-flex rounded-full px-2.5 py-1 text-xs font-medium ${getStatusClass(employee.outcome)}`}>
                                                {employee.outcome}
                                            </span>
                                        </span>
                                    </button>

                                    {#if expandedEmployees.includes(employee.id)}
                                        <div class="border-t border-slate-100 bg-slate-50/70 px-5 py-5 dark:border-slate-800 dark:bg-slate-950/50 sm:px-8">
                                            <div class="mb-4">
                                                <h3 class="font-semibold">Daily attendance details</h3>
                                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                                    Raw clock-in values are read-only sample records.
                                                </p>
                                            </div>

                                            <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                                <table class="w-full min-w-[700px] text-left text-sm">
                                                    <thead class="border-b border-slate-200 text-xs text-slate-500 dark:border-slate-800">
                                                        <tr>
                                                            <th class="px-4 py-3 font-medium">Date</th>
                                                            <th class="px-4 py-3 font-medium">Shift</th>
                                                            <th class="px-4 py-3 font-medium">Expected start</th>
                                                            <th class="px-4 py-3 font-medium">Clock-in</th>
                                                            <th class="px-4 py-3 font-medium">Assessment</th>
                                                            <th class="px-4 py-3 font-medium">Difference</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                                        {#each employee.days as day, i (`${employee.id}-${day.date}-${i}`)}
                                                            <tr>
                                                                <td class="px-4 py-3">{formatDate(day.date)}</td>
                                                                <td class="px-4 py-3">{day.shift}</td>
                                                                <td class="px-4 py-3">{day.scheduled}</td>
                                                                <td class="px-4 py-3 font-medium">
                                                                    {day.clockIn ?? 'No record'}
                                                                </td>
                                                                <td class="px-4 py-3">
                                                                    <span class={`inline-flex rounded-full px-2 py-1 text-xs font-medium ${getStatusClass(getEvaluatedStatus(day))}`}>
                                                                        {getEvaluatedStatus(day)}
                                                                    </span>
                                                                </td>
                                                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                                                                    
                                                                        {#if !day.clockIn}
                                                                            —
                                                                        {:else if appliedAssessment === 'late' && getEvaluatedStatus(day) === 'Late'}
                                                                            {formatDuration(
                                                                                Math.max(
                                                                                    0,
                                                                                    (parseTime(day.clockIn) ?? 0) -
                                                                                    (parseTime(day.scheduled) ?? 0) -
                                                                                    (getShiftSetting(day.shift)?.gracePeriod ?? appliedGracePeriod)
                                                                                )
                                                                            )}
                                                                        {:else if appliedAssessment === 'early' && getEvaluatedStatus(day) === 'Early'}
                                                                            {formatDuration(
                                                                                Math.max(
                                                                                    0,
                                                                                    (parseTime(day.scheduled) ?? 0) -
                                                                                    (parseTime(day.clockIn) ?? 0)
                                                                                )
                                                                            )}
                                                                        {:else}
                                                                            —
                                                                        {/if}

                                                                </td>
                                                            </tr>
                                                        {/each}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    {/if}
                                </td>
                            </tr>
                        {/each}
                    </tbody>
                </table>

                {#if employeeRows.length === 0}
                    <div class="px-6 py-12 text-center">
                        <h3 class="font-semibold">No matching employees</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Adjust your search or filters to see more results.
                        </p>
                    </div>
                {/if}
            </div>

            <div class="mt-4 flex flex-col justify-between gap-2 text-sm text-slate-500 dark:text-slate-400 sm:flex-row sm:items-center">
                <p>
                    Showing {employeeRows.length} of {rawEmployees.length} employees
                </p>
                <p>Sample data · {fileName}</p>
            </div>
        </section>
    </main>
</div>
