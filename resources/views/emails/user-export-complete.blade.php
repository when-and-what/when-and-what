@component('mail::message')
    Your export is ready to download!

    @component('mail::button', ['url' => route('profile.export')])
        Download
    @endcomponent

    Your export will be available for 2 days. After that would need to request a new export to download your data.
@endcomponent
