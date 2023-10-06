
# Mini Framework MVC en PHP
Este repositorio presenta una estructura de carpetas y archivos que puedes utilizar como base para desarrollar aplicaciones PHP siguiendo el patrón de diseño Modelo-Vista-Controlador (MVC). Esta estructura proporciona una organización clara para tu proyecto, lo que facilita el desarrollo y el mantenimiento a medida que tu aplicación crece.
[![mini-frame.png](https://i.postimg.cc/SKf56hPB/mini-frame.png)](https://postimg.cc/Z90Vm14H)
## Clases del Proyecto
- __Router__: Un enrutador que maneja las solicitudes HTTP y las redirige a los controladores adecuados según las rutas definidas. Esto facilita la implementación de rutas amigables para el usuario.
- __Response__: Una clase que ayuda a construir respuestas HTTP, lo que permite enviar encabezados y contenido de manera efectiva.

- __Request__: Una clase que facilita la obtención de datos de la solicitud HTTP, como parámetros de URL y datos del formulario.

## Configuración

1. Configurar el archivo config.php, ya que este archivo setea credenciales para la conexión a la base de datos.

[![config.png](https://i.postimg.cc/D0QQ4v0D/config.png)](https://postimg.cc/BP6PdGxc)

- __Configuración de BASE_URL__
Para asegurarse de que los estilos se carguen correctamente, es esencial definir base_url en tu archivo de configuración (config.php u otro archivo similar). base_url representa la URL base de tu aplicación web. Debes configurarlo de la siguiente manera:

[![base-url.png](https://i.postimg.cc/28KJzTpZ/base-url.png)](https://postimg.cc/Xp9xLfYj)

## Uso 
1. Clona este repositorio en tu entorno de desarrollo.
2. Personaliza la estructura y los archivos según las necesidades de tu proyecto.
3. Desarrolla tu aplicación siguiendo el patrón MVC en los directorios app/Controllers, app/Models, y resources/Views, las vistas deben tener la extensión .phtml.


[Donar😘](https://www.paypal.me/detoditoeber23)