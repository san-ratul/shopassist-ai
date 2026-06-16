<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
    <h2 class="text-lg font-semibold text-gray-900">
        AI Provider
    </h2>

    <div class="mt-6">
        <label
                for="provider"
                class="block text-sm font-medium text-gray-700"
        >
            Active Provider
        </label>

        <div class="relative mt-2">
            <select
                    id="provider"
                    name="provider"
                    x-model="provider"
                    class="
                    block w-full appearance-none rounded-lg border border-gray-300
                    bg-white px-4 py-2.5 pr-10 text-sm text-gray-900 shadow-sm
                    focus:border-black focus:outline-none focus:ring-2 focus:ring-black/10
                "
            >
                @foreach ($providers as $key => $config)
                    <option value="{{ $key }}">
                        {{ $config['label'] }}
                    </option>
                @endforeach
            </select>

            <div
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-500"
            >
                <svg
                        class="h-5 w-5"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                >
                    <path
                            fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd"
                    />
                </svg>
            </div>
        </div>

        <p class="mt-2 text-sm text-gray-500">
            Choose which AI provider ShopAssist should use.
        </p>
    </div>
</div>