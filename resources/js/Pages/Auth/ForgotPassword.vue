<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2'; // Add this import

const emailFound = ref(false);
const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    token: ''
});

const checkEmail = () => {
    form.post('/check-email', {
        preserveScroll: true,
        onSuccess: (response) => {
            if (response?.props?.flash?.token) {
                form.token = response?.props?.flash?.token;
                emailFound.value = true;
            }
        },
        onError: (errors) => {
            console.error('Email check error:', errors);
            emailFound.value = false;
        }
    });
};

const resetPassword = () => {
    form.post('/reset-password', {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                title: 'Berjaya!',
                text: 'Kata laluan berjaya dikemaskini',
                icon: 'success',
                confirmButtonText: 'Ok',
                confirmButtonColor: '#0f766e'
            }).then(() => {
                window.location.href = route('login');
            });
        }
    });
};

const submit = (e) => {
    e.preventDefault();
    if (!emailFound.value) {
        checkEmail();
    } else {
        resetPassword();
    }
};
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-mint-50">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-2xl font-bold text-center text-gray-900 mb-8">
                Reset Kata Laluan
            </h2>

            <div v-if="!emailFound">
                <form @submit.prevent="checkEmail">
                    <div>
                        <InputLabel for="email" value="Email" class="form-label" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-6">
                        <button
                            type="submit"
                            class="w-full py-2 px-4 bg-mint-700 hover:bg-mint-800 text-white font-semibold rounded-lg"
                            :disabled="form.processing"
                        >
                            Semak Email
                        </button>
                    </div>
                </form>
            </div>

            <div v-else>
                <form @submit.prevent="resetPassword">
                    <div>
                        <InputLabel for="password" value="Kata Laluan Baru" class="form-label" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password_confirmation" value="Sahkan Kata Laluan" class="form-label" />
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password_confirmation"
                            required
                        />
                    </div>

                    <div class="mt-6">
                        <button
                            type="submit"
                            class="w-full py-2 px-4 bg-mint-700 hover:bg-mint-800 text-white font-semibold rounded-lg"
                            :disabled="form.processing"
                        >
                            Reset Kata Laluan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
