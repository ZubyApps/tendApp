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
                <Label for="firstname">First Name</Label>
                <Input
                    id="firstname"
                    type="text"
                    required
                    autocomplete="given-name"
                    name="firstname"
                    placeholder="First name"
                />
                <InputError message={errors.firstname} />
            </div>

            <!-- Middle Name -->
            <div class="grid gap-2">
                <Label for="middlename">Middle Name</Label>
                <Input
                    id="middlename"
                    type="text"
                    autocomplete="additional-name"
                    name="middlename"
                    placeholder="Middle name"
                />
                <InputError message={errors.middlename} />
            </div>

            <!-- Last Name -->
            <div class="grid gap-2">
                <Label for="lastname">Last Name</Label>
                <Input
                    id="lastname"
                    type="text"
                    required
                    autocomplete="family-name"
                    name="lastname"
                    placeholder="Last name"
                />
                <InputError message={errors.lastname} />
            </div>

            <!-- Company -->
            <div class="grid gap-2">
                <Label for="company">Company</Label>
                <Input
                    id="company"
                    type="text"
                    required
                    autocomplete="organization"
                    name="company"
                    placeholder="Company name"
                />
                <InputError message={errors.company} />
            </div>

            <!-- Sex -->
             <Label for="sex">Sex</Label>
            <select
                id="sex"
                name="sex"
                class="border-input flex h-9 w-full rounded-md border px-3 py-1 text-sm shadow-sm"
            >
                <option value="" class="bg-white text-black dark:bg-neutral-900 dark:text-white">Select Sex...</option>
                <option value="male" class="bg-white text-black dark:bg-neutral-900 dark:text-white">Male</option>
                <option value="female" class="bg-white text-black dark:bg-neutral-900 dark:text-white">Female</option>
            </select>
                <InputError message={errors.sex} />

            <!-- <div class="grid gap-2">
                <Label for="sex">Sex</Label>
                <Input
                    id="sex"
                    type="text"
                    required
                    autocomplete="sex"
                    name="sex"
                    placeholder="Sex"
                />
                <InputError message={errors.sex} />
            </div> -->

            <!-- Address -->
            <div class="grid gap-2">
                <Label for="address">Address</Label>
                <Input
                    id="address"
                    type="text"
                    required
                    autocomplete="street-address"
                    name="address"
                    placeholder="Address"
                />
                <InputError message={errors.address} />
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
