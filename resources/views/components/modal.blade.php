@vite('resources/js/app.js')


<div class="fixed inset-0 flex items-center justify-center z-50 hidden" id="modal">
    <div class="bg-black bg-opacity-50 fixed inset-0"></div>
    <div
        class="bg-black rounded-lg shadow-lg z-10 px-6 py-10 border-2 border-white max-w-sm mx-auto flex flex-col gap-10">
        <h2 class="text-lg font-bold">Удалить данный принтер?</h2>
        <div class="flex gap-4 justify-between mt-4">
            <x-forms.button type="button" class="hover:border-red-500 !bg-black text-white" id="confirm-delete">
                Удалить
            </x-forms.button>
            <x-forms.button type="button" class="hover:border-green-500 !bg-black text-white" id="cancel-delete">
                Отмена
            </x-forms.button>
        </div>
    </div>
</div>

{{-- <script>
    document.getElementById('cancel-delete').onclick = function() {
        document.getElementById('modal').classList.add('hidden');
    };

    document.getElementById('confirm-delete').onclick = function() {
        document.getElementById('delete-form').submit();
    };

    function showModal() {
        document.getElementById('modal').classList.remove('hidden');
    }
</script> --}}
