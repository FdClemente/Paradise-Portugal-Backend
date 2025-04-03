@component('mail::message')
    # We have received your request to reset your account password

    You can use the following code to recover your account:

    {{ $code }}

    The allowed duration of the code is one hour from the time the message was sent.

@endcomponent
