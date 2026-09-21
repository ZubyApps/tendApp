<script module lang="ts">
    export const layout = {
        title: 'Create an account',
        description: 'Enter your details below to create your account',
    };
</script>

<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import InputError from '@/components/InputError.svelte';
    import PasswordInput from '@/components/PasswordInput.svelte';
    import TextLink from '@/components/TextLink.svelte';
    import { Button } from '@/components/ui/button';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import { Spinner } from '@/components/ui/spinner';
    import { login } from '@/routes';
    import { store } from '@/routes/register';

    let { passwordRules }: { passwordRules: string } = $props();
</script>

<AppHead title="Register" />

<Form
    {...store.form()}
    resetOnSuccess={['password', 'password_confirmation']}
    class="flex flex-col gap-6"
>
    {#snippet children({ errors, processing })}
        <div class="grid gap-6">

            <!-- First Name -->
            <div class="grid gap-2">
                <Label for="first_name">First Name</Label>
                <Input
                    id="first_name"
                    type="text"
                    required
                    autocomplete="given-name"
                    name="first_name"
                    placeholder="First name"
                />
                <InputError message={errors.first_name} />
            </div>

            <!-- Middle Name -->
            <div class="grid gap-2">
                <Label for="middle_name">Middle Name</Label>
                <Input
                    id="middle_name"
                    type="text"
                    autocomplete="additional-name"
                    name="middle_name"
                    placeholder="Middle name"
                />
                <InputError message={errors.middle_name} />
            </div>

            <!-- Last Name -->
            <div class="grid gap-2">
                <Label for="last_name">Last Name</Label>
                <Input
                    id="last_name"
                    type="text"
                    required
                    autocomplete="family-name"
                    name="last_name"
                    placeholder="Last name"
                />
                <InputError message={errors.last_name} />
            </div>

            <!-- Phone Number -->
            <div class="grid gap-2">
                <Label for="phone">Phone Number</Label>
                <Input
                    id="phone"
                    type="tel"
                    required
                    autocomplete="tel"
                    name="phone"
                    placeholder="08012345678"
                    class="focus-visible:border-red-900 focus-visible:ring-2 focus-visible:ring-red-900/20"
                />
                <InputError message={errors.phone} />
            </div>

            <!-- Email -->
            <div class="grid gap-2">
                <Label for="email">Email Address</Label>
                <Input
                    id="email"
                    type="email"
                    required
                    autocomplete="email"
                    name="email"
                    placeholder="email@example.com"
                />
                <InputError message={errors.email} />
            </div>

            <!-- Password -->
            <div class="grid gap-2">
                <Label for="password">Password</Label>
                <PasswordInput
                    id="password"
                    required
                    autocomplete="new-password"
                    name="password"
                    placeholder="Password"
                    passwordrules={passwordRules}
                />
                <InputError message={errors.password} />
            </div>

            <!-- Confirm Password -->
            <div class="grid gap-2">
                <Label for="password_confirmation">Confirm Password</Label>
                <PasswordInput
                    id="password_confirmation"
                    required
                    autocomplete="new-password"
                    name="password_confirmation"
                    placeholder="Confirm password"
                    passwordrules={passwordRules}
                />
                <InputError message={errors.password_confirmation} />
            </div>

            <!-- Submit Button -->
            <Button
                type="submit"
                class="mt-2 w-full bg-blue-600 text-white shadow-lg shadow-blue-600/20 transition-all duration-200 hover:bg-blue-500 hover:shadow-blue-600/30"
                disabled={processing}
                data-test="register-user-button"
            >
                {#if processing}<Spinner />{/if}
                Create account
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            Already have an account?

            <TextLink href={login()} class="underline underline-offset-4">
                Log in
            </TextLink>
        </div>
    {/snippet}
</Form>
