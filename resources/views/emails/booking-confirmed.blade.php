<x-mail::message>
# {{ __('learner.mail.booking_confirmed_subject') }}

{{ __('learner.mail.booking_confirmed_greeting', ['name' => $session->learner->first_name]) }}

{{ __('learner.mail.booking_confirmed_intro', ['teacher' => $session->teacherProfile->user->name]) }}

**{{ __('learner.mail.booking_confirmed_date', [
    'date' => $session->availabilitySlot->starts_at->translatedFormat('l j F Y'),
    'start' => $session->availabilitySlot->starts_at->format('H:i'),
    'end' => $session->availabilitySlot->ends_at->format('H:i'),
]) }}**

@if($session->teacherProfile->meet_link)
<x-mail::button :url="$session->teacherProfile->meet_link">
{{ __('learner.mail.booking_confirmed_cta') }}
</x-mail::button>
@endif

{{ config('app.name') }}
</x-mail::message>
