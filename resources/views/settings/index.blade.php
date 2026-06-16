<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<div
    x-data="{
        provider: @js(
            old(
                'provider',
                data_get(
                    $settings,
                    'provider',
                    array_key_first($providers)
                )
            )
        )
    }"
    class="max-w-4xl mx-auto py-8 space-y-6"
>
    @include('shopassist::settings.partials.page-header')

    <form
        method="POST"
        action="{{ route('shopassist.settings.update') }}"
        class="space-y-6"
    >
        @csrf

        @include('shopassist::settings.partials.alerts')

        @include('shopassist::settings.partials.general-settings')

        @include('shopassist::settings.partials.provider-selector')

        @include('shopassist::settings.partials.provider-fields')

        @include('shopassist::settings.partials.save-actions')
    </form>
</div>
