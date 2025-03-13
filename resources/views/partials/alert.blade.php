<style>
    .alert-popup {
        background-color: #0B0C18; /* Темное окно уведомлений */
        border: 1px solid gold;
        border-radius: 10px;
        color: #ffffff;
        columns: #ffffff;
        z-index: 3;
    }

    .alert-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7); /* Прозрачное затемнение */
        z-index: 4; /* Значение z-index должно быть меньше, чем у .alert-popup */
    }

    .blue_btn {
        display: inline-block;
        color: #ffffff;
        background: #0b0c18;
        padding: 5px 10px;
        font-size: 1.3rem;
        border: 1px solid gold;
        border-radius: 10px;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
        text-decoration: none;
    }

    .blue_btn:hover {
        box-shadow: 0 0 20px goldenrod;
        transform: scale(1.05);
        color: #ffffff;
    }
</style>
@if ((isset($message) && is_string($message)) || (session('success') && is_string(session('success'))) || (session('error') && is_string(session('error'))) || (session('info') && is_string(session('info'))))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const backdrop = document.createElement('div');
            backdrop.className = 'alert-backdrop';
            document.body.appendChild(backdrop);

            @if (isset($message) && is_string($message))
                Swal.fire({
                    icon: 'info',
                    title: '{{ $message }}',
                    text: '{{ $message }}',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'alert-popup',
                        title: 'alert-title',
                        text: 'alert-text',
                        confirmButton: 'alert-button blue_btn'
                    },
                    didClose: () => {
                        document.body.removeChild(backdrop);
                    }
                });
            @elseif (session('success') && is_string(session('success')))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'alert-popup',
                        title: 'alert-title',
                        text: 'alert-text',
                        confirmButton: 'alert-button blue_btn'
                    },
                    didClose: () => {
                        document.body.removeChild(backdrop);
                    }
                });
            @elseif (session('error') && is_string(session('error')))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'alert-popup',
                        title: 'alert-title',
                        text: 'alert-text',
                        confirmButton: 'alert-button blue_btn'
                    },
                    didClose: () => {
                        document.body.removeChild(backdrop);
                    }
                });
            @elseif (session('info') && is_string(session('info')))
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: '{{ session('info') }}',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'alert-popup',
                        title: 'alert-title',
                        text: 'alert-text',
                        confirmButton: 'alert-button blue_btn'
                    },
                    didClose: () => {
                        document.body.removeChild(backdrop);
                    }
                });
            @endif
        });
    </script>
@endif
