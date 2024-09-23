document.addEventListener("DOMContentLoaded", function() {
    const inputs = document.querySelectorAll('input[name="nombre"], input[name="modifyNombre"], input[name="apellido"], input[name="modifyApellido"], input[name="dni"], input[name="modifyDni"], input[name="escuela"], input[name="modifyEscuela"]');
    let activeTooltip = null;

    function mostrarTooltip(element, rules) {
        eliminarToolTips();
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.style.opacity = 0; // Inicialmente invisible
    
        // Ajustar la posición del tooltip a la derecha del elemento
        tooltip.style.left = `${element.getBoundingClientRect().right + 5}px`; // Añade un pequeño margen
        tooltip.style.top = `${element.getBoundingClientRect().top + window.scrollY}px`;
    
        rules.forEach(rule => {
            const line = document.createElement('div');
            line.innerHTML = rule.valid ? '✓ ' + rule.message : '• ' + rule.message;
            tooltip.appendChild(line);
        });
    
        const allValid = rules.every(rule => rule.valid);
        tooltip.style.backgroundColor = allValid ? 'lightgreen' : 'lightcoral';
        document.body.appendChild(tooltip);
        fadeIn(tooltip);
    
        activeTooltip = tooltip;
    }

    function fadeIn(element) {
        let op = 0.1;  // Opacidad inicial
        const timer = setInterval(function () {
            if (op >= 1){
                clearInterval(timer);
            }
            element.style.opacity = op;
            element.style.filter = 'alpha(opacity=' + op * 100 + ")";
            op += op * 0.1;
        }, 8);
    }

    function fadeOut(element) {
        let op = 1;  // Opacidad inicial
        const timer = setInterval(function () {
            if (op <= 0.1){
                clearInterval(timer);
                element.remove();
            }
            element.style.opacity = op;
            element.style.filter = 'alpha(opacity=' + op * 100 + ")";
            op -= op * 0.1;
        }, 5);
    }

    function eliminarToolTips() {
        if (activeTooltip) {
            fadeOut(activeTooltip);
            activeTooltip = null;
        }
    }

    function validateInput(input) {
        const name = input.getAttribute('name');
        let rules = [];
    
        if (name === "nombre" || name === "modifyNombre" || name === "apellido" || name === "modifyApellido") {
            rules = [
                { valid: input.value.trim() !== "", message: "No puede estar vacío." },
                { valid: !/[0-9]/.test(input.value), message: "No puede contener números." },
                { valid: !/[^a-zA-Z\s]/.test(input.value), message: "No puede contener caracteres especiales." }
            ];
        } else if (name === "dni" || name === "modifyDni") {
            rules = [
                { valid: input.value.trim() !== "", message: "No puede estar vacío." },
                { valid: /^\d{8}$/.test(input.value), message: "Debe contener 8 dígitos." },
                { valid: /^[0-9]+$/.test(input.value), message: "No puede contener caracteres especiales, ni letras." }
            ];
        } else if (name === "escuela" || name === "modifyEscuela") {
            rules = [
                { valid: input.value.trim() !== "", message: "No puede estar vacío." }
            ];
        }
    
        mostrarTooltip(input, rules);
    }
    

    inputs.forEach(input => {
        input.addEventListener('focus', () => validateInput(input));
        input.addEventListener('input', () => validateInput(input));
        input.addEventListener('blur', () => eliminarToolTips());
    });
});
