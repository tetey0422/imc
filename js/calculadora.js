// Función para calcular el IMC
function calcularIMC() {
    // Obtener los valores de los campos
    let peso = parseFloat(document.getElementById("peso").value);
    let altura = parseFloat(document.getElementById("altura").value);
    let sistema = document.querySelector('input[name="sistema"]:checked').value;
    let cedula = document.getElementById("cc").value;

    // Verificar que los valores son válidos
    if (isNaN(peso) || isNaN(altura) || peso <= 0 || altura <= 0) {
        alert("Por favor, ingrese valores válidos para el peso y la altura.");
        return;
    }

    let imc;
    if (sistema === "kg") {
        // Sistema métrico: kg y metros
        imc = peso / (altura * altura);
    } else if (sistema === "lbs") {
        // Sistema imperial: libras y pulgadas/pies
        let unidadAltura = document.querySelector('input[name="unidadAltura"]:checked').value;

        if (unidadAltura === "pies") {
            altura = altura * 12; // Convertir pies a pulgadas
        }

        // Calcular IMC en sistema imperial
        imc = (peso / (altura * altura)) * 703;
    }

    // Determinar la categoría del IMC
    let categoria = '';
    if (imc < 18.5) {
        categoria = 'Bajo peso';
    } else if (imc >= 18.5 && imc < 24.9) {
        categoria = 'Normal';
    } else if (imc >= 25 && imc < 29.9) {
        categoria = 'Sobrepeso';
    } else {
        categoria = 'Obesidad';
    }

    // Mostrar el resultado del IMC
    document.getElementById("imcValue").textContent = `Tu IMC es: ${imc.toFixed(2)}`;
    document.getElementById("categoriaIMC").textContent = `Categoría: ${categoria}`;
    document.getElementById("resultado").style.display = 'block';

    // Enviar datos al servidor via AJAX
    let formData = new FormData();
    formData.append('cc', cedula);
    formData.append('altura', altura);
    formData.append('peso', peso);
    formData.append('imc', imc.toFixed(2));
    formData.append('categoria', categoria);

    fetch('procesar_imc.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Registro guardado exitosamente');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al guardar el registro');
    });
}