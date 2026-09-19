<x-action-section>
    <x-slot name="title">
        <span id="export-data">Export Data</span>
    </x-slot>

    <x-slot name="description">
        Export all the data that stored for your profile by {{ config('app.name') }}.
    </x-slot>

    <x-slot name="content">
        @if($exportStatus === true)
            <div class="max-w-xl text-green-600 mb-b">You will recieve an email with a download link once your export is ready.</div>
        @elseif($exportStatus === false)
            <div class="max-w-xl text-red-600 mb-4">You may only request an export once every 24 hours</div>
        @endif
        <div class="max-w-xl text-sm text-gray-600">
            We will send you a zip file with a <abbr title="Comma Seperated Values">CSV</abbr> for each different section of the site with your data.<br />
            Depending on how much data you have, this may take a while to complete. You will recieve an email when it's finished.
        </div>

        <div class="flex items-center mt-5">
            <x-button wire:click="exportData" wire:loading.attr="disabled">
                Export Data
            </x-button>
        </div>
    </x-slot>
</x-action-section>
