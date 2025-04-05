<script setup>
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <AppLayout>
        <Head title="Pengesahan Email" />

        <div class="mb-4 text-sm text-gray-600">
            Terima kasih kerana mendaftar! Sebelum memulakan, sila sahkan alamat email anda
            dengan mengklik pautan yang telah kami hantar ke email anda. Jika anda
            tidak menerima email tersebut, kami akan menghantar pautan yang baru.
        </div>

        <div
            class="mb-4 text-sm font-medium text-mint-600"
            v-if="verificationLinkSent"
        >
            Pautan pengesahan baru telah dihantar ke alamat email yang anda
            berikan semasa pendaftaran.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing, 'bg-mint-600 hover:bg-mint-700': !form.processing }"
                    :disabled="form.processing"
                >
                    Hantar Semula Email Pengesahan
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    >Log Out</Link
                >
            </div>
        </form>
    </AppLayout>
</template>
