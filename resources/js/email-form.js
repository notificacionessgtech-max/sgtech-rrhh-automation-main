import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

toastr.options = {
  "closeButton": false,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "timeOut": "3000",
};
export function sendEmail() {
    const form = document.getElementById('emailForm');
    if (!form) return; // No ejecutar si no hay formulario en la vista

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        const spinner = document.getElementById('spinner');
        if (spinner) spinner.classList.remove('hidden');

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (spinner) spinner.classList.add('hidden');
                if (data.success) {
                    toastr.success(data.message || 'Correo enviado con éxito');
                    form.reset();
                } else {
                    toastr.error(data.message || 'Error al enviar el correo, intente de nuevo');
                }
            })
            .catch(err => {
                if (spinner) spinner.classList.add('hidden');
                toastr.error('Error al enviar el correo, intente de nuevo');
                console.error(err);
            });
    });
}
