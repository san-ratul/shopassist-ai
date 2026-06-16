@props([
    'id',
    'name',
    'label',
    'value' => '',
    'required' => false,
    'helper' => null,
    'error' => null,
    'configured' => false,
])

<div>
    <label
            for="{{ $id }}"
            class="block text-sm font-medium text-gray-700"
    >
        {{ $label }}

        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    <div
            x-data="{ show: false }"
            class="relative mt-2"
    >
        <input
                :type="show ? 'text' : 'password'"
                id="{{ $id }}"
                name="{{ $name }}"
                value="{{ $value }}"
                autocomplete="off"
                placeholder="{{ $configured ? '••••••••••••' : 'Enter a new value' }}"
                class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-12 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-black focus:outline-none focus:ring-2 focus:ring-black/10"
        >

        <button
                type="button"
                @click="show = !show"
                class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 transition hover:text-gray-600 focus:outline-none"
        >
            {{-- Eye --}}
            <svg
                    x-show="!show"
                    x-cloak
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-5 w-5"
            >
                <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"
                />

                <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
            </svg>

            {{-- Eye Slash --}}
            <svg
                    x-show="show"
                    x-cloak
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-5 w-5"
            >
                <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 3l18 18"
                />

                <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M10.477 10.489A3 3 0 0012 15a3 3 0 002.51-4.523"
                />

                <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6.228 6.228C4.36 7.412 2.926 9.36 2.036 11.683a1.012 1.012 0 000 .639C3.423 16.49 7.36 19.5 12 19.5a9.77 9.77 0 004.772-1.228"
                />

                <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9.88 4.68A9.966 9.966 0 0112 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639a17.92 17.92 0 01-2.212 3.592"
                />
            </svg>
        </button>
    </div>

    @if ($configured)
        <p class="mt-2 flex items-center gap-2 text-sm text-green-600">
            <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
            >
                <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 13l4 4L19 7"
                />
            </svg>

            <span>
                A value is already configured. Leave blank to keep the existing value.
            </span>
        </p>

    @elseif ($helper)

        <p class="mt-2 text-sm text-gray-500">
            {{ $helper }}
        </p>

    @endif

    @if ($error)
        <p class="mt-2 text-sm text-red-600">
            {{ $error }}
        </p>
    @endif
</div>