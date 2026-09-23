<x-mail::message>
# {{ __('teacher.mail.approved_subject') }}

{{ __('teacher.mail.approved_greeting', ['name' => $profile->user->name]) }}

{{ __('teacher.mail.approved_intro') }}

<x-mail::button :url="route('teacher.availability')">
{{ __('teacher.mail.approved_cta') }}
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
