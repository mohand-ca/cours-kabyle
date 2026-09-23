<x-mail::message>
# {{ __('teacher.mail.booking_confirmed_subject') }}

{{ __('teacher.mail.booking_confirmed_greeting', ['name' => $session->teacherProfile->user->name]) }}

{{ __('teacher.mail.booking_confirmed_intro', ['learner' => $session->learner->first_name]) }}

**{{ __('teacher.mail.booking_confirmed_date', [
    'date' => $session->availabilitySlot->starts_at->translatedFormat('l j F Y'),
    'start' => $session->availabilitySlot->starts_at->format('H:i'),
    'end' => $session->availabilitySlot->ends_at->format('H:i'),
]) }}**

{{ config('app.name') }}
</x-mail::message>
