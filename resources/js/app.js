import './bootstrap';
import Swal from 'sweetalert2';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

window.confirmDelete = function (formId){
    Swal.fire({
        title: 'Anda Yakin Ingin Menghapus Data Ini?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    })
}

window.flashMessage = function(type, message){
    Swal.fire({
        icon: type,
        title: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
}
