document.addEventListener('click', function(event) {
    if (event.target.matches('#cancel-delete')) {
        console.log('cancel-delete function');
        document.getElementById('modal').classList.add('hidden');
    }

    if (event.target.matches('#confirm-delete')) {
        console.log('confirm delete function');
        document.getElementById('delete-form').submit();
    }

    if (event.target.matches('#showModal')) {
        console.log('show modal function');
        document.getElementById('modal').classList.remove('hidden');
    }
});
