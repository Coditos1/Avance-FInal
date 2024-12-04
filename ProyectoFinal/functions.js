function validateLetters(input) {
    const regex = /^[A-Za-z\s]+$/; // Expresión regular para solo letras y espacios
    if (!regex.test(input.value)) {
        alert("Este campo solo puede contener letras.");
        input.value = ""; // Limpiar el campo
    }
}

function formatPhoneNumber(input) {
    // Eliminar todos los caracteres que no sean dígitos
    const value = input.value.replace(/\D/g, '');
    
    // Formatear el número
    const formattedValue = value.replace(/(\d{3})(\d{3})(\d+)/, '($1) $2-$3');
    
    // Asignar el valor formateado al campo de entrada
    input.value = formattedValue.substring(0, 14); // Limitar a 14 caracteres
}

function validateQuantity(input) {
    const value = parseInt(input.value);
    if (value <= 0) {
        alert("La cantidad debe ser un número positivo mayor que cero.");
        input.value = ""; // Limpiar el campo
    }
}

function validateDate(input) {
    const today = new Date();
    const oneMonthFromNow = new Date();
    oneMonthFromNow.setMonth(today.getMonth() + 1);

    const selectedDate = new Date(input.value);

    if (selectedDate < today) {
        alert("La fecha no puede ser menor que la fecha actual.");
        input.value = ""; // Limpiar el campo
    } else if (selectedDate > oneMonthFromNow) {
        alert("La fecha no puede ser mayor a un mes a partir de la fecha actual.");
        input.value = ""; // Limpiar el campo
    }
}

function validateDateVolII(input) {
    const today = new Date();
    const oneMonthAgo = new Date();
    oneMonthAgo.setMonth(today.getMonth() - 1);

    const selectedDate = new Date(input.value);

    if (selectedDate > today) {
        alert("La fecha no puede ser mayor a la fecha actual.");
        input.value = ""; // Limpiar el campo
    } else if (selectedDate < oneMonthAgo) {
        alert("La fecha no puede ser más de un mes anterior a la fecha actual.");
        input.value = ""; // Limpiar el campo
    }
}
