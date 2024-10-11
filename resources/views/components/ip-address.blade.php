@props(['printer' => ''])

<div class="space-y-6">
    <x-forms.select name="ip_exists" label="Есть IP?" id="ip-select" class="w-full">
        <option value="yes">Есть</option>
        <option value="no">Нету</option>
    </x-forms.select>
    <div id="ip-block">
        <div class="inline-flex items-center gap-x-2" id="ip-square">
            <span class="w-2 h-2 bg-white inline-block"></span>
            <label class="font-bold" for="ip" id="ip-label">IP адрес</label>
            <label class="font-bold text-blue-500" for="ip" id="ip-type-label">IPv4</label>
        </div>
        <div class="bg-[#1f1f1f] rounded-xl gap-4 py-4 px-3 transition-all duration-500 ease-in-out"
            id="ip-switch-container">
            <div class="inline-flex gap-1 w-full justify-between">
                <div class="inline-block transition-transform duration-500 ease-in-out w-2/3 " id="ip-input">
                    <x-forms.input label="" placeholder="255.10.192.12" name="IP" id="ip"
                        maxlength="15" class="!bg-[#151515]" value="{{ $printer ? $printer->IP : old('IP', '') }}" />

                    {{-- когда нибудь это будет сделано --}}
                    {{-- <x-forms.ip-input label="" name="not IP" /> --}}

                </div>
                <div class="transition-transform w-1/3 duration-500 inline-block ease-in-out" id="ip-button">
                    <button id="ip-type"
                        class="text-nowrap w-full rounded-xl py-4 border-2 border-[#151515] bg-[#181818]" type="button"
                        onclick="log()">IPv6</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const IpModule = (() => {
        const ipType = document.getElementById('ip-type');
        const ipInput = document.getElementById('ip-input');
        const ipButton = document.getElementById('ip-button');
        const ipTypeLabel = document.getElementById('ip-type-label');
        const ip = document.getElementById('ip');
        const switchContainer = document.getElementById('ip-switch-container');

        let isIPv6 = switchContainer.classList.contains('flex-row-reverse');

        const ipProxy = new Proxy(ip, {
            set(target, property, value) {
                if (property === 'value') {
                    if (isIPv6) {
                        value = value.replace(/[^0-9a-f:]/g,
                            '');
                        // value = value.match(/.{1,4}/g)?.join(':') || value;
                        target.maxLength = 39;
                        target.placeholder = '2001:0db8:85a3:0000:0000:8a2e:0370:7334';
                        ipTypeLabel.textContent = 'IPv6';
                    } else {
                        value = value.replace(/[^0-9.]/g,
                            '');
                        // value = value.match(/.{1,3}/g)?.join('.') || value;
                        target.maxLength = 15;
                        target.placeholder = '255.10.192.12';
                        ipTypeLabel.textContent = 'IPv4';
                    }
                }

                target[property] = value;
                return true;
            }
        });

        const handleInputChange = () => {
            ipProxy.value = ip.value;
        };

        ip.addEventListener('input', handleInputChange);

        const toggleIPType = () => {
            isIPv6 = !isIPv6;
            ip.value = '';
            ipProxy.value = ip.value;

            switchContainer.classList.toggle('flex-row-reverse');

            ipInput.style.transform = switchContainer.classList.contains('flex-row-reverse') ?
                'translateX(52%)' :
                'translateX(0)';
            ipButton.style.transform = switchContainer.classList.contains('flex-row-reverse') ?
                'translateX(-202%)' :
                'translateX(0)';

            setTimeout(() => {
                ipType.textContent = isIPv6 ? 'IPv4' : 'IPv6';
            }, 400);
        };

        ipType.addEventListener('click', toggleIPType);

        ipProxy.value = ip.value;

        return {
            init,
        }
    })();

    IpModule.init();
</script>

{{-- отпуск --}}
{{-- влад напротив 217 --}}
