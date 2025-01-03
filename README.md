## Sitio Web para Consultorio Médico  

Este proyecto es un sistema web diseñado para la gestión de pacientes en un consultorio médico. Incluye funcionalidades como la generación de tarjetas de paciente con códigos QR, administración CRUD, y módulos personalizables para facilitar la operación del consultorio.

---

### Características Principales  

- **Gestión de Usuarios**  
  - Roles disponibles: Administrador y Cajero.  
  - Control de acceso basado en roles para garantizar la seguridad de los datos.  

- **Gestión de Pacientes**  
  - CRUD completo para pacientes: Crear, Leer, Actualizar y Eliminar.  
  - Generación automática de un código único al registrar un paciente.  
  - Creación de tarjetas con código QR para identificación rápida.  

- **Módulos Personalizables**  
  - Posibilidad de modificar y añadir módulos para adaptarse a las necesidades del consultorio.  

- **Lectura de Códigos QR**  
  - Funcionalidad para escanear el código QR de los pacientes y registrar visitas.  

---

### Tecnologías Utilizadas  

- **Frontend**  
  - Bootstrap para diseño responsive y moderno.  
  - Font Awesome Icons para agregar iconos atractivos y funcionales.  
  - Sweet Alerts para alertas interactivas y personalizadas.  

- **Backend**  
  - PHP con patrón MVC para mantener el código organizado y escalable.  

- **Base de Datos**  
  - MySQL para almacenamiento de información.  

- **Otros**  
  - Estilo visual inspirado en AdminLTE para una interfaz profesional y fácil de usar.  

---

### Funcionalidades Detalladas  

#### Administrador  
- Dashboard con:  
  - Listado de cajeros.  
  - Listado de pacientes registrados.  
- Gestión CRUD para pacientes.  
- Generación e impresión de tarjetas con código QR.  

#### Cajero  
- Dashboard con:  
  - Lectura y validación de códigos QR.  
  - Registro de visitas de pacientes.  
  - Creación rápida de nuevos pacientes.  

---

### Estructura del Proyecto  

- **templates/**  
  Contiene archivos reutilizables como `header.php`, `footer.php` y `dashboard_layout.php`.  

- **view/**  
  Contiene las vistas del sistema como `login.php`, `dashboard.php`, entre otras.  

- **models/**  
  Incluye los modelos de datos que interactúan con la base de datos.  

- **controllers/**  
  Contiene los controladores que gestionan la lógica del sistema.  

---

### Instalación  

1. Clona el repositorio:  
   ```bash
   git clone https://github.com/tuusuario/consultorio-medico.git
   ```  

2. Configura la base de datos:  
   - Crea una base de datos en MySQL.  
   - Importa el archivo SQL proporcionado en la carpeta `database`.  

3. Configura la conexión a la base de datos:  
   - Edita el archivo `config/db.php` y agrega las credenciales de tu servidor.  

4. Inicia un servidor local para ejecutar el proyecto:  
   ```bash
   php -S localhost:8000
   ```  

---

### Contribuciones  

Este proyecto está abierto a mejoras. Si deseas contribuir:  
1. Haz un fork del repositorio.  
2. Crea una rama con tu nueva funcionalidad o corrección.  
3. Envía un pull request explicando tus cambios.  

---

### Licencia  

Este proyecto está licenciado bajo [MIT License](LICENSE).  

---  

¡Gracias por usar este sistema! 😊 
