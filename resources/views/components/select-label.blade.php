@props(['disabled' => false, 'for' => '', 'label' => 'Texto'])

<div>

    <div class="relative h-10 w-full">
        <select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' =>'peer h-full w-full rounded-md border border-gray-400 border-t-transparent bg-transparent p-3 font-sans text-sm font-normal text-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-gray-400 placeholder-shown:border-t-gray-400 focus:border-gray-900 focus:border-t-transparent focus:ring-0 focus:outline-0 disabled:bg-gray-100 disabled:text-black']) !!}>
            {{ $slot }}
        </select>
        <label
            class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-gray-500 transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-gray-400 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-gray-400 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[3.75] peer-placeholder-shown:text-gray-600 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-gray-900 peer-focus:before:border-gray-900 peer-focus:after:border-gray-900 peer-disabled:peer-placeholder-shown:text-gray-600">
            {{ $label }}
        </label>
    </div>
    @unless (!empty(${$for}))
        @error($for)
            <div class="text-red-500 text-xs mt-1">
                {{ $message }}
            </div>
        @enderror
    @endunless

</div>
