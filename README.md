## 🛠️ Calculadora de IMC

Calculadora de IMC es una aplicación web diseñada para calcular el Índice de Masa Corporal (IMC) de los usuarios. Permite a los usuarios ingresar su cédula, altura y peso para calcular su IMC y categorizarlo. Además, guarda el historial de IMC de cada usuario y lo muestra en una gráfica.

## 📋 Descripción del Proyecto

Calculadora de IMC es una herramienta enfocada en la facilidad de uso y la experiencia del usuario. Algunas de sus principales características incluyen:

- **Cálculo de IMC**: Permite a los usuarios calcular su IMC ingresando su altura y peso.
- **Historial de IMC**: Guarda y muestra el historial de IMC de cada usuario.
- **Interfaz moderna**: Un diseño responsivo y atractivo para cualquier dispositivo.
- **Gestión eficiente de datos**: Conexión a base de datos para almacenar y recuperar datos de usuarios e IMC.

## 🚀 Tecnologías Utilizadas

| Tecnología | Uso principal |
|------------|---------------|
| HTML5      | Estructura del contenido del sitio web. |
| CSS3       | Estilos visuales y diseño responsivo. |
| JavaScript | Funcionalidad dinámica en el frontend. |
| PHP        | Lógica del backend y conexión con la base de datos. |
| MySQL      | Gestión de datos para usuarios e IMC. |
| XAMPP      | Servidor local para desarrollo y pruebas. |

## 📂 Estructura del Proyecto

```plaintext
📂 calculadora-imc/
├── 📂 css/                # Archivos CSS para estilos
│   ├── index.css
│   ├── imc.css
│   ├── header.css
│   └── footer.css
├── 📂 img/                # Imágenes del sitio
├── 📂 includes/           # Archivos PHP reutilizables (header, footer)
│   ├── config.php
│   ├── footer.php
│   └── header.php
├── 📂 js/                 # JavaScript para funcionalidad dinámica
│   └── calculadora.js
├── 📂 sql/                # Archivos SQL para la base de datos
│   └── imc.sql
├── index.php              # Página principal
├── imc.php                # Página de la calculadora de IMC
├── obtener_usuario.php    # Página para obtener datos del usuario
├── procesar_imc.php       # Página para procesar y guardar datos del IMC
└── README.md              # Archivo README del proyecto
```
## 📦 Instalación y Configuración

1️⃣ Requisitos previos:

XAMPP (o un servidor local similar) instalado en tu máquina.
Navegador web actualizado.

2️⃣ Configurar la base de datos:

Inicia XAMPP y activa los servicios de Apache y MySQL.
Abre http://localhost/phpmyadmin.
Crea una base de datos llamada bdimc.
Importa el archivo SQL ubicado en sql/imc.sql.

3️⃣ Configurar config.php:

```
<?php  
$servername = "localhost";  
$username = "root";  
$password = "";  
$dbname = "bdimc";  

$conn = new mysqli($servername, $username, $password, $dbname);  

if ($conn->connect_error) {  
    die("Conexión fallida: " . $conn->connect_error);  
}  
?>
```

## 4️⃣ Iniciar la aplicación:

Coloca el proyecto dentro de la carpeta htdocs de XAMPP.
Accede al sitio en tu navegador: http://localhost/imc/index.php.

## 📌 Funcionalidades Principales
| Funcionalidad          | Descripción                                      |
|------------------------|--------------------------------------------------|
| Página de inicio       | Permite a los usuarios ingresar su cédula.       |
| Calculadora de IMC     | Calcula el IMC basado en la altura y peso ingresados. |
| Historial de IMC       | Muestra el historial de IMC del usuario.         |

## 📱 Responsividad

El diseño está optimizado para pantallas de escritorio y dispositivos móviles, garantizando una experiencia de usuario fluida en cualquier entorno.

## 🛠️ Futuras Mejoras

Implementar autenticación y registro avanzado de usuarios.
Integrar un sistema de historial de IMC más detallado.
Añadir gráficos interactivos para visualizar el historial de IMC.
Incorporar recomendaciones personalizadas basadas en el IMC calculado.

## 📄 Licencia

Este proyecto está licenciado bajo la Licencia MIT. Consulta el archivo LICENSE para más detalles.

## 📧 Contacto

Si tienes preguntas o sugerencias, no dudes en contactarnos:

- GitHub (Jefrey): https://github.com/tetey0422