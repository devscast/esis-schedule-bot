import Swal from 'sweetalert2';

const customClass = {
    title: 'text-bolder',
    loader: 'spinner-border text-primary'
}

const Confirm = Swal.mixin({
    text: "Êtes vous sûr de vouloir effectué cette action ?",
    showDenyButton: false,
    showCancelButton: true,
    cancelButtonText: 'Annuler',
});

const Toast = Swal.mixin({
    customClass,
    toast: true,
    position: 'top-end',
    timer: 3000,
    timerProgressBar: true,
    showConfirmButton: false,
    didOpen(toast) {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

export const confirmation = async (action, onConfirm) => {
    await Confirm
        .fire({confirmButtonText: action})
        .then((result) => {
            if (result.isConfirmed) {
                onConfirm()
            }
        })
}

export const toast = async (type, message) => {
    await Toast.fire({
        icon: type,
        text: message
    })
}
