@foreach ($providers as $providerKey => $providerConfig)
    <div
        x-show="provider === '{{ $providerKey }}'"
        x-transition.opacity
        x-cloak
            class="bg-white rounded-xl border border-gray-200 shadow-sm p-6"
    >
        <h2 class="text-lg font-semibold text-gray-900">
            {{ $providerConfig['label'] }} Settings
        </h2>

        <div class="mt-6 space-y-6">

            @foreach (($providerConfig['settings'] ?? []) as $field => $fieldConfig)

                @php
                    $type = $fieldConfig['type'] ?? 'text';
                    $errorKey = "providers.$providerKey.$field";
                @endphp

                @if ($type === 'password')

                    <x-shopassist::password-field
                        :id="$providerKey . '_' . $field"
                        :name="'providers[' . $providerKey . '][' . $field . ']'"
                        :label="$fieldConfig['label']"
                        :value="old($errorKey)"
                        :required="$fieldConfig['required'] ?? false"
                        :helper="($fieldConfig['encrypted'] ?? false)
                            ? ''
                            : ($fieldConfig['helper'] ?? null)"
                        :error="$errors->first($errorKey)"
                        :configured="filled(data_get($settings, $errorKey))"
                    />

                @else

                    <div>
                        <label
                            for="{{ $providerKey }}_{{ $field }}"
                            class="block text-sm font-medium text-gray-700"
                        >
                            {{ $fieldConfig['label'] }}

                            @if ($fieldConfig['required'] ?? false)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        <input
                                type="{{ $type }}"
                                id="{{ $providerKey }}_{{ $field }}"
                                name="providers[{{ $providerKey }}][{{ $field }}]"
                                value="{{ old(
                                    $errorKey,
                                    data_get(
                                        $settings,
                                        "providers.$providerKey.$field"
                                    )
                                ) }}"
                                placeholder="{{ $fieldConfig['placeholder'] ?? '' }}"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-black focus:outline-none focus:ring-2 focus:ring-black/10"
                        >

                        @if ($fieldConfig['helper'] ?? false)
                            <p class="mt-2 text-sm text-gray-500">
                                {{ $fieldConfig['helper'] }}
                            </p>
                        @endif

                        @error($errorKey)
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                @endif

            @endforeach

        </div>
    </div>
@endforeach