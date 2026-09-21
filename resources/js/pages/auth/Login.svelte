<script module lang="ts">
    export const layout = {
        title: 'Log in to your account',
        description: 'Enter your phone number and password below to log in',
    };
</script>

<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import InputError from '@/components/InputError.svelte';
    import PasskeyVerify from '@/components/PasskeyVerify.svelte';
    import PasswordInput from '@/components/PasswordInput.svelte';
    import TextLink from '@/components/TextLink.svelte';
    import { Button } from '@/components/ui/button';
    import { Checkbox } from '@/components/ui/checkbox';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import { Spinner } from '@/components/ui/spinner';
    import { register } from '@/routes';
    import { store } from '@/routes/login';
    import { request } from '@/routes/password';

    let {
        status = '',
        canResetPassword,
    }: {
        status?: string;
        canResetPassword: boolean;
    } = $props();
</script>

<AppHead title="Log in" />

{#if status}
    <div class="mb-4 text-center text-sm font-medium text-green-600">
        {status}
    </div>
{/if}

<PasskeyVerify />

<Form
    {...store.form()}
    resetOnSuccess={['password']}
    class="flex flex-col gap-6"
>
    <!-- {#snippet children({ errors, processing })}
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="email">Email address</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autocomplete="email"
                    placeholder="email@example.com"
                />
                <InputError message={errors.email} />
            </div> -->

            {#snippet children({ errors, processing })}
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="phone">Phone Number</Label>
                <Input
                    id="phone"
                    type="tel"
                    name="phone" 
                    required
                    autocomplete="tel"
                    placeholder="080XXXXXXXX"
                    // placeholder="Input phone number"
                    class="focus-visible:border-red-900 focus-visible:ring-2 focus-visible:ring-red-900/20"
                />
                <InputError message={errors.phone} />
            </div>

            <div class="grid gap-2">
                <div class="flex items-center justify-between">
                    <Label for="password">Password</Label>
                     <!-- {#if canResetPassword}
                        <TextLink href={request()} class="text-sm">
                            Forgot your password?
                        </TextLink>
                     {/if}   -->
                     <!-- (MOVED) -->
                </div>

                <PasswordInput
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Password"
                />
                <InputError message={errors.password} />
            </div>

            <!-- <div> 
                    {#if canResetPassword}
                        <TextLink href={request()} class="text-sm align-right text-blue-600 hover:text-blue-500">
                            Forgot your password?
                        </TextLink>
                    {/if}
                </div>

            <div class="flex items-center justify-between">
                <Label for="remember" class="flex items-center space-x-3">
                    <Checkbox id="remember" name="remember" />
                    <span>Remember me</span>
                </Label>
            </div> -->

            <div class="flex items-center justify-between mt-4">
    <!-- Remember Me Checkbox -->
    <label for="remember_me" class="flex items-center">
        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" name="remember">
        <span class="ml-2 text-sm text-gray-600">Remember me</span>
    </label>

                    {#if canResetPassword}
                        <TextLink href={request()} class="text-sm align-right text-blue-600 hover:text-blue-500">
                            Forgot your password?
                        </TextLink>
                    {/if}
                </div>

            <Button

                type="submit"
               class="mt-2 w-full bg-blue-600 text-white shadow-lg shadow-blue-600/20 transition-all duration-200 hover:bg-blue-500 hover:shadow-blue-600/30"
                disabled={processing}
                data-test="login-button"
            >

            {#if processing}<Spinner /> Processing...
                {:else}
                Log in
            {/if}

              <!-- {#if processing}<Spinner />{/if}
                Log in -->

            </Button>
        </div>

        <!-- <div class="text-center text-sm text-muted-foreground"> -->
        <div class="text-center text-sm text-muted-foreground font-bold">
        <!-- <div class="text-center text-sm text-muted-foreground fw-bold"> -->
            Don't have an account?
            <TextLink href={register()}>Sign up</TextLink>
        </div>
    {/snippet}
</Form>
