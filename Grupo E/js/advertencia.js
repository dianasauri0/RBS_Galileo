document.getElementById('deleteButton').addEventListener('click', function(e) {
    var selectedIds = document.querySelectorAll('input[name="ids[]"]:checked').length;

    var messageBox = document.getElementById('messageBox');
    if (selectedIds === 0) {
        e.preventDefault(); // Evita que el formulario se envíe
        messageBox.textContent = 'Debes seleccionar al menos 1 para poder eliminar.';
        messageBox.style.display = 'block';
        setTimeout(function() { messageBox.style.display = 'none'; }, 5000);
    }
});

document.getElementById('modifyButton').addEventListener('click', function() {
    var selectedIds = [];
    document.querySelectorAll('input[name="ids[]"]:checked').forEach(function(checkbox) {
        selectedIds.push(checkbox.value);
    });

    var messageBox = document.getElementById('messageBox');
    if (selectedIds.length === 0) {
        messageBox.textContent = 'Por favor, seleccione un profesor para modificar.';
        messageBox.style.display = 'block';
        setTimeout(function() { messageBox.style.display = 'none'; }, 5000);
        return;
    } else if (selectedIds.length > 1) {
        messageBox.textContent = 'Por favor, seleccione solo un profesor para modificar.';
        messageBox.style.display = 'block';
        setTimeout(function() { messageBox.style.display = 'none'; }, 5000);
        return;
    }

    document.getElementById('idsToModify').value = selectedIds[0];
    // Aquí podrías cargar los datos del profesor seleccionado en los campos del formulario
    document.getElementById('modifyModal').style.display = 'block';
});

function closeModifyModal() {
    document.getElementById('modifyModal').style.display = 'none';
}