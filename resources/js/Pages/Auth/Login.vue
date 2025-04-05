<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log Masuk" />

        <div class="min-h-screen flex w-full flex-col justify-center items-center pt-6 sm:pt-0 bg-mint-50">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <!-- Status Message -->
                <div v-if="status" class="mb-4 text-sm font-medium text-mint-600">
                    {{ status }}
                </div>

                <h2 class="text-2xl font-bold text-center text-gray-900 mb-8">
                    Log Masuk
                </h2>

                <form @submit.prevent="submit">
                    <!-- Email Field -->
                    <div>
                        <InputLabel for="email" value="Email" class="form-label" />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Password Field -->
                    <div class="mt-4">
                        <InputLabel for="password" value="Kata Laluan" class="form-label" />

                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                        />

                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="mt-4 block">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ms-2 text-sm text-gray-600">
                                Ingat saya
                            </span>
                        </label>
                    </div>

                    <!-- Footer Actions -->
                    <div class="mt-4 flex items-center justify-between">
                        <Link
                            :href="route('password.request')"
                            class="text-sm text-mint-600 hover:text-mint-500 underline"
                        >
                            Lupa kata laluan?
                        </Link>

                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Log Masuk
                        </PrimaryButton>
                    </div>

                    <!-- Register Link -->
                    <div class="mt-6 text-center">
                        <Link
                            href="/register"
                            class="text-sm text-mint-600 hover:text-mint-500"
                        >
                            Belum ada akaun? Daftar di sini
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>

<style>
.form-label {
    @apply text-emerald-700 font-medium;
}
</style>
