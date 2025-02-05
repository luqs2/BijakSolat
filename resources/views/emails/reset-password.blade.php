<x-mail::message>
# Reset Kata Laluan

Klik butang di bawah untuk reset kata laluan anda.

<x-mail::button :url="$url">
Reset Kata Laluan
</x-mail::button>

Link ini akan tamat dalam {{ config('auth.passwords.users.expire') }} minit.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
