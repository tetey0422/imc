document.addEventListener('DOMContentLoaded', function () {
    // Verificar sesión
    const cedula = sessionStorage.getItem('cedula');
    if (!cedula) {
        alert('Por favor, ingrese su cédula primero');
        window.location.href = 'index.php';
        return;
    }

    cargarHistorial();
    // Inicializar los listeners de eventos
    initEventListeners();
});
function validarFormatoCedula(cedula) {
    return /^\d{8,12}$/.test(cedula);
}

function initEventListeners() {
    // Listener para cambios en el sistema de medición
    document.querySelectorAll('input[name="sistema"]').forEach(radio => {
        radio.addEventListener('change', toggleAlturaOptions);
    });

    // Listener para cambios en la unidad de altura imperial
    document.querySelectorAll('input[name="unidadAltura"]').forEach(radio => {
        radio.addEventListener('change', actualizarPlaceholdersImperial);
    });
}

function toggleAlturaOptions() {
    const sistema = document.querySelector('input[name="sistema"]:checked').value;
    const alturaImperial = document.getElementById('alturaImperial');
    const unidadAltura = document.getElementById('unidadAltura');
    const unidadPeso = document.getElementById('unidadPeso');
    const alturaInput = document.getElementById('altura');
    const pesoInput = document.getElementById('peso');

    if (sistema === 'kg') {
        alturaImperial.style.display = 'none';
        unidadAltura.textContent = 'metros';
        unidadPeso.textContent = 'kg';
        alturaInput.step = '0.01';
        alturaInput.placeholder = 'Ej: 1.70'; // 1.70 metros
        pesoInput.placeholder = 'Ej: 70'; // Peso en kg
    } else {
        alturaImperial.style.display = 'block';
        unidadPeso.textContent = 'libras';
        alturaInput.step = '0.1';
        actualizarPlaceholdersImperial();
        pesoInput.placeholder = 'Ej: 154'; // Peso en libras (aproximadamente 70 kg)
    }

    // Limpiar valores anteriores
    alturaInput.value = '';
    pesoInput.value = '';
    ocultarResultado();
}

function actualizarPlaceholdersImperial() {
    const unidadAltura = document.querySelector('input[name="unidadAltura"]:checked').value;
    const alturaInput = document.getElementById('altura');
    const unidadAlturaSpan = document.getElementById('unidadAltura');

    if (unidadAltura === 'pies') {
        alturaInput.placeholder = 'Ej: 5.6'; // 1.70 metros son aproximadamente 5.6 pies
        unidadAlturaSpan.textContent = 'pies';
    } else {
        alturaInput.placeholder = 'Ej: 67'; // 1.70 metros son aproximadamente 67 pulgadas
        unidadAlturaSpan.textContent = 'pulgadas';
    }

    // Limpiar valor anterior
    alturaInput.value = '';
    ocultarResultado();
}

function ocultarResultado() {
    document.getElementById('resultado').style.display = 'none';
}

function calcularIMC() {
    const cedula = sessionStorage.getItem('cedula');
    if (!cedula) {
        alert('Sesión no válida. Por favor, vuelva a ingresar.');
        window.location.href = 'index.php';
        return;
    }

    let peso = parseFloat(document.getElementById('peso').value);
    let altura = parseFloat(document.getElementById('altura').value);
    const sistema = document.querySelector('input[name="sistema"]:checked').value;

    if (!validarEntradas(peso, altura, sistema)) {
        return;
    }

    let alturaEnMetros, pesoEnKg;
    if (sistema === 'kg') {
        alturaEnMetros = altura;
        pesoEnKg = peso;
    } else {
        const unidadAltura = document.querySelector('input[name="unidadAltura"]:checked').value;
        if (unidadAltura === 'pies') {
            // Convertir pies a metros
            alturaEnMetros = altura * 0.3048;
        } else {
            // Convertir pulgadas a metros
            alturaEnMetros = altura * 0.0254;
        }
        // Convertir libras a kilogramos
        pesoEnKg = peso * 0.453592;
    }

    const imc = pesoEnKg / (alturaEnMetros * alturaEnMetros);
    const categoria = determinarCategoria(imc);

    mostrarResultado(imc, categoria);
    guardarResultado(cedula, alturaEnMetros, pesoEnKg, imc, categoria);
}

function validarEntradas(peso, altura, sistema) {
    if (isNaN(peso) || isNaN(altura) || peso <= 0 || altura <= 0) {
        alert('Por favor, ingrese valores numéricos válidos mayores que cero.');
        return false;
    }

    if (sistema === 'kg') {
        if (peso < 20 || peso > 300) {
            alert('Por favor, ingrese un peso válido entre 20 y 300 kg.');
            return false;
        }
        if (altura < 0.5 || altura > 2.5) {
            alert('Por favor, ingrese una altura válida entre 0.5 y 2.5 metros.');
            return false;
        }
    } else {
        if (peso < 44 || peso > 660) {
            alert('Por favor, ingrese un peso válido entre 44 y 660 libras.');
            return false;
        }
        const unidadAltura = document.querySelector('input[name="unidadAltura"]:checked').value;
        if (unidadAltura === 'pies') {
            if (altura < 1.5 || altura > 8) {
                alert('Por favor, ingrese una altura válida entre 1.5 y 8 pies.');
                return false;
            }
        } else {
            if (altura < 20 || altura > 96) {
                alert('Por favor, ingrese una altura válida entre 20 y 96 pulgadas.');
                return false;
            }
        }
    }
    return true;
}

function determinarCategoria(imc) {
    if (imc < 18.5) return 'Bajo peso';
    if (imc < 25) return 'Normal';
    if (imc < 30) return 'Sobrepeso';
    if (imc < 35) return 'Obesidad grado 1';
    if (imc < 40) return 'Obesidad grado 2';
    return 'Obesidad grado 3';
}

function mostrarResultado(imc, categoria) {
    const resultadoDiv = document.getElementById('resultado');
    const imcValue = document.getElementById('imcValue');
    const categoriaIMC = document.getElementById('categoriaIMC');

    imcValue.textContent = `Tu IMC es: ${imc.toFixed(2)}`;
    categoriaIMC.textContent = `Categoría: ${categoria}`;

    // Aplicar clases de color según la categoría
    categoriaIMC.className = 'categoria-' + categoria.toLowerCase().replace(/ /g, '-');

    resultadoDiv.style.display = 'block';
}

function guardarResultado(cedula, altura, peso, imc, categoria) {
    const formData = new FormData();
    formData.append('cedula', cedula);
    formData.append('altura', altura.toFixed(2));
    formData.append('peso', peso.toFixed(2));
    formData.append('imc', imc.toFixed(2));
    formData.append('categoria', categoria);

    fetch('includes/procesar_imc.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                cargarHistorial();
            } else {
                alert('Error al guardar: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al comunicarse con el servidor');
        });
}

function cargarHistorial() {
    const cedula = sessionStorage.getItem('cedula');
    if (!cedula) {
        console.error('No hay cédula en sessionStorage');
        return;
    }

    const historialDiv = document.getElementById('historial');
    historialDiv.innerHTML = '<p class="loading">Cargando historial...</p>';

    fetch('includes/obtener_historial.php?cedula=' + encodeURIComponent(cedula))
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            switch (data.status) {
                case 'no_user':
                    historialDiv.innerHTML = `
                        <div class="mensaje-info">
                            <h3>Bienvenido a la Calculadora de IMC</h3>
                            <p>Para comenzar a llevar un registro de tu IMC, calcula tu primer índice 
                               utilizando el formulario a continuación.</p>
                            <p>Beneficios de registrar tu IMC:</p>
                            <ul>
                                <li>Seguimiento de tu progreso en el tiempo</li>
                                <li>Visualización gráfica de tus cambios</li>
                                <li>Historial completo de mediciones</li>
                            </ul>
                        </div>`;
                    break;

                case 'no_records':
                    historialDiv.innerHTML = `
                        <div class="mensaje-info">
                            <h3>¡Comienza tu Seguimiento de IMC!</h3>
                            <p>Aún no tienes registros de IMC guardados. 
                               Utiliza la calculadora a continuación para realizar tu primera medición.</p>
                            <p>Tu historial aparecerá aquí una vez que registres tu primer IMC.</p>
                        </div>`;
                    break;

                case 'success':
                    const registros = data.data;
                    let html = '<h2>Historial de IMC</h2>';
                    html += '<div class="tabla-responsive">';
                    html += '<table class="tabla-historial">';
                    html += '<thead><tr>';
                    html += '<th>Fecha</th>';
                    html += '<th>Altura (m)</th>';
                    html += '<th>Peso (kg)</th>';
                    html += '<th>IMC</th>';
                    html += '<th>Categoría</th>';
                    html += '</tr></thead><tbody>';

                    registros.forEach(registro => {
                        html += `<tr>
                            <td>${formatearFecha(registro.dfecha)}</td>
                            <td>${registro.naltura}</td>
                            <td>${registro.npeso}</td>
                            <td>${parseFloat(registro.nimc).toFixed(2)}</td>
                            <td class="categoria-${registro.ccategoria.toLowerCase().replace(/ /g, '-')}">${registro.ccategoria}</td>
                        </tr>`;
                    });

                    html += '</tbody></table></div>';

                    if (registros.length > 1) {
                        html += '<div id="grafica-imc"></div>';
                    }

                    historialDiv.innerHTML = html;

                    if (registros.length > 1) {
                        crearGraficaIMC(registros);
                    }
                    break;

                default:
                    throw new Error('Respuesta del servidor no válida');
            }
        })
        .catch(error => {
            console.error('Error detallado:', error);
            historialDiv.innerHTML =
                `<div class="error-mensaje">
                    <p>Ocurrió un error al cargar el historial.</p>
                    <p>Por favor, intenta nuevamente más tarde.</p>
                </div>`;
        });
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function crearGraficaIMC(data) {
    // Preparar datos para la gráfica
    const fechas = data.map(r => formatearFecha(r.dfecha));
    const imcs = data.map(r => parseFloat(r.nimc));

    // Configuración de la gráfica
    const config = {
        type: 'line',
        data: {
            labels: fechas,
            datasets: [{
                label: 'IMC',
                data: imcs,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'IMC'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Evolución del IMC'
                }
            }
        }
    };

    // Crear la gráfica
    const ctx = document.createElement('canvas');
    document.getElementById('grafica-imc').appendChild(ctx);
    new Chart(ctx, config);
}