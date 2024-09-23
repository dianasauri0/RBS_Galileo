document.addEventListener('DOMContentLoaded', function() {
    // Modal de agregar profesor
    var modal = document.getElementById("modal");
    var openModalButton = document.getElementById("openModal");
    var closeModalButton = modal.getElementsByClassName("close")[0];

    openModalButton.onclick = function() {
        modal.style.display = "block";
    }

    closeModalButton.onclick = function() {
        modal.style.display = "none";
    }

    // Modal de modificar profesor
    var modifyModal = document.getElementById("modifyModal");
    var modifyCloseButton = modifyModal.getElementsByClassName("close")[0];

    modifyCloseButton.onclick = function() {
        closeModifyModal();
    }

    // Cerrar modales al hacer clic fuera de ellos
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        } else if (event.target == modifyModal) {
            modifyModal.style.display = "none";
        }
    }

    // Funcionalidad de eliminación
    document.getElementById('deleteButton').addEventListener('click', function(e) {
        var selectedIds = document.querySelectorAll('input[name="ids[]"]:checked').length;
        if (selectedIds === 0) {
            e.preventDefault();
            mostrarMensaje('Por favor, seleccione al menos un profesor para eliminar.');
        }

        if (checkboxes.length === 0) {
                e.preventDefault();
            }
    });

    // Funcionalidad de modificación
    document.getElementById('modifyButton').addEventListener('click', function() {
        var selectedCheckboxes = document.querySelectorAll('input[name="ids[]"]:checked');

        if (selectedCheckboxes.length === 0) {
            mostrarMensaje('Por favor, seleccione al menos un profesor para modificar.');
            return;
        }

        if (selectedCheckboxes.length > 1) {
            mostrarMensaje('Por favor, seleccione solo un profesor para modificar.');
            return;
        }

        // Solo un profesor seleccionado
        var selectedCheckbox = selectedCheckboxes[0];
        var selectedRow = selectedCheckbox.closest('tr');

        // Cargar los datos del profesor seleccionado en los campos del formulario
        document.getElementById('modifyNombre').value = selectedRow.getAttribute('data-nombre');
        document.getElementById('modifyApellido').value = selectedRow.getAttribute('data-apellido');
        document.getElementById('modifyDni').value = selectedRow.getAttribute('data-dni');
        document.getElementById('modifyEscuela').value = selectedRow.getAttribute('data-escuela');

        document.getElementById('idsToModify').value = selectedCheckbox.value;
        modifyModal.style.display = 'block';
    });

    // Función para cerrar el modal de modificación
    function closeModifyModal() {
        modifyModal.style.display = 'none';
    }

    // Función para mostrar mensajes
    function mostrarMensaje(mensaje) {
        var messageBox = document.getElementById('messageBox');
        var messageContent = messageBox.querySelector('.message-content');
        var progressBar = messageBox.querySelector('.progress-bar');

        // Cancelar cualquier animación o temporizador previo
        clearTimeout(messageBox.hideTimeout);
        clearTimeout(messageBox.progressTimeout);

        // Restablecer el estado inicial
        messageContent.textContent = mensaje;
        messageBox.style.transform = 'translateX(0)';
        progressBar.style.transition = 'none';
        progressBar.style.width = '100%';

        // Forzar un reflujo/repaint para que el navegador reconozca el cambio en el estilo
        progressBar.offsetHeight;

        progressBar.style.transition = 'width 5s linear';
        messageBox.classList.add('show');

        // Iniciar la animación de la barra de progreso
        messageBox.progressTimeout = setTimeout(function() {
            progressBar.style.width = '0%';
        }, 10);

        // Ocultar el mensaje después de 5 segundos
        messageBox.hideTimeout = setTimeout(function() {
            messageBox.style.transform = 'translateX(120%)';
            setTimeout(function() {
                messageBox.classList.remove('show');
            }, 500);
        }, 5000);
    }
});

    // Funcionalidad de eliminación
    document.addEventListener('DOMContentLoaded', function() {
    const deleteButton = document.getElementById('deleteButton2');
    if (deleteButton) {
        deleteButton.addEventListener('click', function(e) {
            const selectedCheckboxes = document.querySelectorAll('input[name="ids[]"]:checked').length;
            if (selectedCheckboxes === 0) {
                e.preventDefault();
            }
        });
    }
});

// Manejo de errores Agregar / Guardar profesores
document.addEventListener("DOMContentLoaded", function() {
    const forms = [
        { form: document.getElementById('addForm'), button: document.getElementById('agregarProfesorBtn') },
        { form: document.getElementById('modifyForm'), button: document.getElementById('guardarCambiosBtn') }
    ];

    forms.forEach(({ form, button }) => {
        if (form) {
            const inputs = form.querySelectorAll('input');

            form.addEventListener('submit', function(event) {
                let hasErrors = false;

                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    let isValid = true;

                    if ((name === "nombre" || name === "apellido" || name === "modifyNombre" || name === "modifyApellido") && 
                        (!input.value.trim() || /[0-9]/.test(input.value) || /[^a-zA-Z\s]/.test(input.value))) {
                        isValid = false;
                    } else if ((name === "dni" || name === "modifyDni") && 
                        (!input.value.trim() || !/^\d{8}$/.test(input.value))) {
                        isValid = false;
                    } else if ((name === "escuela" || name === "modifyEscuela") && !input.value.trim()) {
                        isValid = false;
                    }

                    if (!isValid) {
                        input.classList.add('input-error');
                        hasErrors = true;
                    } else {
                        input.classList.remove('input-error');
                    }
                });

                if (hasErrors) {
                    event.preventDefault();
                    button.style.backgroundColor = 'red';
                    button.classList.add('shake');
                    setTimeout(() => {
                        button.style.backgroundColor = '';
                        button.classList.remove('shake');
                    }, 500);
                }
            });
        }
    });
});

// Realizado principalmente por Nehuen Perez Titz
// Integrantes del grupo: Nehuen Perez Titz,Lisandro Tejeda, Monzon Kevin