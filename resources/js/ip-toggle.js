const ipSelected = document.getElementById('ip-select');
const block = document.getElementById('ip-block');

// const ip = document.getElementById('ip');
// const square = document.getElementById('ip-square');


if(ipSelected){
    ipSelected.addEventListener('change', function(e){
        const selected = e.target.value;
        if(selected.toLowerCase() === 'no'){
            // square.classList.add('hidden');
            // ip.classList.add('hidden');
            block.classList.add('hidden');
        }
        else if(selected.toLowerCase() === 'yes'){
            // square.classList.remove('hidden');
            // ip.classList.remove('hidden');
            block.classList.remove('hidden');
        }
    });
}