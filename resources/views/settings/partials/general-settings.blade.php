<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
    <h2 class="text-lg font-semibold text-gray-900">
        General
    </h2>

    <div class="mt-6 space-y-6">

        <label class="flex items-start justify-between gap-4">
            <div>
                <p class="font-medium text-gray-900">
                    Enable ShopAssist
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Enable the AI assistant across your storefront.
                </p>
            </div>

            <input
                type="checkbox"
                name="enabled"
                value="1"
                @checked(old('enabled', data_get($settings, 'enabled')))
                class="mt-1 h-5 w-5 rounded border-gray-300 text-black focus:ring-black"
            >
        </label>

        <label class="flex items-start justify-between gap-4">
            <div>
                <p class="font-medium text-gray-900">
                    Allow Guest Users
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Allow guests to interact with ShopAssist.
                </p>
            </div>

            <input
                type="checkbox"
                name="guest[enabled]"
                value="1"
                @checked(old('guest.enabled', data_get($settings, 'guest.enabled')))
                class="mt-1 h-5 w-5 rounded border-gray-300 text-black focus:ring-black"
            >
        </label>

    </div>
</div>
