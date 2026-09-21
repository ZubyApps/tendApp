<script lang="ts">
    import { Link } from '@inertiajs/svelte';
    import type { Snippet } from 'svelte';
    import AppLogoIcon from '@/components/AppLogoIcon.svelte';
    import { home } from '@/routes';

    let {
        title = '',
        description = '',
        children,
    }: {
        title?: string;
        description?: string;
        children?: Snippet;
    } = $props();
</script>

<div
    class="relative flex min-h-svh flex-col items-center justify-center gap-6 overflow-hidden bg-[#030712] p-6 md:p-10"
>
    <!-- Background glow effects -->
    <div
        class="pointer-events-none absolute -left-48 top-1/4 h-[600px] w-[600px] rounded-full bg-blue-700/20 blur-[160px]"
    ></div>

    <div
        class="pointer-events-none absolute -right-48 bottom-1/4 h-[600px] w-[600px] rounded-full bg-blue-600/20 blur-[160px]"
    ></div>

    <div
        class="pointer-events-none absolute left-1/2 top-[-200px] h-[400px] w-[700px] -translate-x-1/2 rounded-full bg-blue-900/15 blur-[150px]"
    ></div>
    <div class="w-full max-w-sm">
        <div class="flex flex-col gap-8">
            <div class="flex flex-col items-center gap-4">
                <Link
                    href={home()}
                    class="flex flex-col items-center gap-2 font-medium"
                >
                    <div
                        class="mb-1 flex h-9 w-9 items-center justify-center rounded-md"
                    >
                        <AppLogoIcon
                            class="size-9 fill-current text-(--foreground) dark:text-white"
                        />
                    </div>
                    <span class="sr-only">{title}</span>
                </Link>
                <div class="space-y-2 text-center">
                    <h1 class="text-xl font-medium">{title}</h1>
                    <p class="text-center text-sm text-muted-foreground">
                        {description}
                    </p>
                </div>
            </div>
            {@render children?.()}
        </div>
    </div>
</div>
