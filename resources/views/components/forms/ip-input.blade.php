@props(['label', 'name', 'count' => 4])

@php
    $old = old($name, []); // Capture old values if form is re-rendered
    $defaults = [
        'type' => 'text',
        'id' => $name,
        'name' => $name,
        'class' => 'rounded-xl bg-white/10 border border-white/10 px-5 py-4 w-full',
        'data-label' => $label,
        'data-count' => $count,
    ];
@endphp

<x-forms.field :$label :$name :$count class="!bg-[#151515] w-full">
    <div id="inputs-container" class="flex text-center items-center justify-center w-full">
    </div>
</x-forms.field>

<script>
    let isIPv6 = document.getElementById('ip-switch-container').classList.contains('flex-row-reverse');
    const countValue = isIPv6 ? 8 : {{ $count }};
    const container = document.getElementById('inputs-container');
    const oldValues = @json($old);

    const ipFields = {}; // Object to hold input elements

    // Create a proxy to manage dynamic input creation
    const inputProxy = new Proxy(ipFields, {
        set(target, property, value) {
            if (!target[property]) {
                // Create a new input element if it doesn't exist
                const newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.id = '{{ $name }}_' + (parseInt(property) + 1); // Set unique ID
                newInput.name = '{{ $name }}[' + (parseInt(property) + 1) + ']'; // Name as array
                newInput.className = 'rounded-xl bg-white/10 border border-white/10 px-5 py-4 w-[100px]';

                // Set maxLength based on IP type
                newInput.maxLength = isIPv6 ? 4 : 3;

                // Set old values or default empty value
                newInput.value = oldValues[property] || value || "";

                // Add the new input field to the container and proxy
                container.appendChild(newInput);
                target[property] = newInput;
            } else {
                // Update existing input element's value
                target[property].value = value;
            }

            return true;
        }
    });

    // Initialize input fields
    for (let i = 0; i < countValue; i++) {
        inputProxy[i] = oldValues[i] || ""; // Populate from old values or leave empty
    }

    // Example of how to dynamically update input fields through the proxy
    // inputProxy[2] = '192'; // Dynamically sets the 3rd input's value
</script>
