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
                    value = value.replace(/[^0-9a-f]/g,
                        '');
                    value = value.match(/.{1,4}/g)?.join(':') || value;
                    target.maxLength = 39;
                    target.placeholder = '2001:0db8:85a3:0000:0000:8a2e:0370:7334';
                    ipTypeLabel.textContent = 'IPv6';
                } else {
                    value = value.replace(/[^0-9]/g,
                        '');
                    value = value.match(/.{1,3}/g)?.join('.') || value;
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