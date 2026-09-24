<script lang="ts">
    import { Link, page } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import { toUrl } from '@/lib/utils';
    import { dashboard, login } from '@/routes';
    import { register } from '@/routes';

    const auth = $derived(page.props.auth);
</script>

<AppHead title="Hospital management system">
    <link rel="preconnect" href="https://rsms.me/" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" /> </AppHead>

<div
    class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
>
    <header
        class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl"
    >
        <nav class="flex items-center justify-end gap-4">
            {#if auth.user}
                <Link
                    href={toUrl(dashboard())}
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
            {:else}
                <Link
                    href={toUrl(login())}
                    class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"

                    //  class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition"
                >
                    Log in
                </Link>
                <Link
                    href={toUrl(register())}
                    // class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"

                     class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition"
                >
                    Register
                </Link>
            {/if}
        </nav>
    </header>
    <div
        class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0"
    >
        <main
            class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row"
        >
            <div class="min-h-screen bg-slate-50 text-gray-900">

    <!-- NAVBAR -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-4">

            <div class="flex items-center justify-between">

                <!-- LOGO -->
                <div class="flex items-center gap-3">

                    <div class="w-25 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-lg">
                            T<small>END</small>APP
                        </span>
                    </div>

                    <div>
                        <h1 class="font-bold text-lg">
                            Hospital Attendance
                        </h1>

                        <p class="text-xs text-gray-500">
                            Attendance Management System
                        </p> 
                    </div>

                </div>


                <!-- NAVIGATION -->
                <div class="hidden md:flex items-center gap-8">

                    <a
                        href="#home"
                        class="text-gray-700 hover:text-blue-600 transition"
                    >
                        Home
                    </a>

                    <a
                        href="#features"
                        class="text-gray-700 hover:text-blue-600 transition"
                    >
                        Features
                    </a>

                    <a
                        href="#about"
                        class="text-gray-700 hover:text-blue-600 transition"
                    >
                        About
                    </a>

                    <!-- <a
                        href="/login"
                        class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition"
                    >
                        login
                    </a> -->

                </div>

            </div>

        </div>
    </nav>


    <!-- HERO SECTION -->
    <section id="home">

        <div class="max-w-7xl mx-auto px-6 py-20">

            <div class="grid lg:grid-cols-2 gap-12 items-center">

                <!-- LEFT SIDE -->
                <div>

                    <div class="inline-flex items-center bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-medium mb-6">
                        🏥 Smart Hospital Management
                    </div>

                    <h2 class="text-4xl md:text-6xl font-bold leading-tight">
                        Simplify
                        <span class="text-blue-600">
                            Hospital Attendance
                        </span>
                        Management.
                    </h2>

                    <p class="mt-6 text-lg text-gray-600 leading-relaxed max-w-xl">
                        A modern attendance management system designed to help
                        hospitals efficiently record, monitor, and manage
                        staff attendance.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">

                        <a
                            href="/login"
                            class="bg-blue-600 text-white px-7 py-3.5 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg"
                        >
                            Get Started
                        </a>

                        <a
                            href="#features"
                            class="border border-gray-300 bg-white text-gray-700 px-7 py-3.5 rounded-lg font-semibold hover:bg-gray-100 transition"
                        >
                            Learn More
                        </a>

                    </div>

                </div>


                <!-- RIGHT SIDE -->
                <div class="relative">

                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm text-gray-500">
                                    Today's Attendance
                                </p>

                                <h3 class="text-3xl font-bold mt-1">
                                    87%
                                </h3>
                            </div>

                            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-2xl">
                                    ✓
                                </span>
                            </div>

                        </div>


                        <!-- PROGRESS BAR -->
                        <div class="mt-6">

                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-500">
                                    Attendance Rate
                                </span>

                                <span class="font-semibold">
                                    87%
                                </span>
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-3">

                                <div
                                    class="bg-blue-600 h-3 rounded-full"
                                    style="width: 87%"
                                ></div>

                            </div>

                        </div>


                        <!-- STATISTICS -->
                        <div class="grid grid-cols-2 gap-4 mt-8">

                            <div class="bg-green-50 rounded-xl p-5">
                                <p class="text-3xl font-bold text-green-600">
                                    245
                                </p>

                                <p class="text-sm text-gray-600 mt-1">
                                    Present
                                </p>
                            </div>


                            <div class="bg-red-50 rounded-xl p-5">
                                <p class="text-3xl font-bold text-red-600">
                                    38
                                </p>

                                <p class="text-sm text-gray-600 mt-1">
                                    Absent
                                </p>
                            </div>

                        </div>


                        <!-- RECENT ACTIVITY -->
                        <div class="mt-8">

                            <h4 class="font-semibold mb-4">
                                Recent Activity
                            </h4>

                            <div class="flex items-center justify-between py-3 border-b">

                                <div>
                                    <p class="font-medium text-sm">
                                        Staff Attendance
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        Attendance recorded
                                    </p>
                                </div>

                                <span class="text-green-600 text-sm font-medium">
                                    Present
                                </span>

                            </div>


                            <div class="flex items-center justify-between py-3">

                                <div>
                                    <p class="font-medium text-sm">
                                        Daily Report
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        Updated today
                                    </p>
                                </div>

                                <span class="text-blue-600 text-sm font-medium">
                                    Updated
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- FEATURES -->
    <section id="features" class="bg-white py-20">

        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center max-w-2xl mx-auto">

                <p class="text-blue-600 font-semibold">
                    FEATURES
                </p>

                <h2 class="text-3xl md:text-4xl font-bold mt-2">
                    Everything You Need
                </h2>

                <p class="text-gray-600 mt-4">
                    Manage hospital attendance quickly, securely,
                    and efficiently.
                </p>

            </div>


            <div class="grid md:grid-cols-3 gap-6 mt-12">

                <!-- FEATURE 1 -->
                <div class="p-6 rounded-2xl border border-gray-200 hover:shadow-lg transition">

                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-5">
                        📋
                    </div>

                    <h3 class="text-xl font-bold">
                        Easy Attendance Tracking
                    </h3>

                    <p class="text-gray-600 mt-3">
                        Record staff attendance quickly and keep
                        accurate attendance records.
                    </p>

                </div>


                <!-- FEATURE 2 -->
                <div class="p-6 rounded-2xl border border-gray-200 hover:shadow-lg transition">

                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-5">
                        📊
                    </div>

                    <h3 class="text-xl font-bold">
                        Attendance Reports
                    </h3>

                    <p class="text-gray-600 mt-3">
                        Monitor attendance statistics and generate
                        useful reports.
                    </p>

                </div>


                <!-- FEATURE 3 -->
                <div class="p-6 rounded-2xl border border-gray-200 hover:shadow-lg transition">

                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-5">
                        🔒
                    </div>

                    <h3 class="text-xl font-bold">
                        Secure Records
                    </h3>

                    <p class="text-gray-600 mt-3">
                        Keep hospital attendance information organized
                        and secure.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- ABOUT / CTA -->
    <section id="about" class="bg-blue-600 py-20">

        <div class="max-w-5xl mx-auto px-6 text-center">

            <h2 class="text-3xl md:text-4xl font-bold text-white">
                Ready to Manage Attendance Better?
            </h2>

            <p class="text-blue-100 mt-4 text-lg">
                Access your hospital attendance dashboard and
                manage attendance with ease.
            </p>

            <a
                href="/login"
                class="inline-block mt-8 bg-white text-blue-600 px-7 py-3.5 rounded-lg font-semibold hover:bg-gray-100 transition"
            >
                Login to Dashboard
            </a>

        </div>

    </section>


    <!-- FOOTER -->
    <footer class="bg-gray-900 text-white py-8">

        <div class="max-w-7xl mx-auto px-6">

            <div class="flex flex-col md:flex-row items-center justify-between gap-4">

                <div>
                    <p class="font-bold">
                        Hospital Attendance System
                    </p>

                    <p class="text-gray-400 text-sm mt-1">
                        Modern attendance management for hospitals.
                    </p>
                </div>

                <p class="text-gray-400 text-sm">
                    © 2026 Hospital Attendance System
                </p>

            </div>

        </div>

    </footer>

</div>
        </main>
    </div>
    <div class="hidden h-14.5 lg:block"></div>
</div>
