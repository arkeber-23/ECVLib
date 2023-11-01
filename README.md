# EasyPHP MVC en PHP
EasyPHP es una librería diseñada para simplificar el proceso de desarrollo web, permitiéndote crear sitios web de manera rápida y eficiente. Con EasyPHP, puedes aprovechar una variedad de herramientas y funciones que aceleran la creación y el despliegue de tu sitio web.


## Primer proyecto con EasyPHP
Para comenzar a usar esta librería, solo necesitas clonar el repositorio y abrir una terminal en la raíz del proyecto:

```
https://github.com/arkeber-23/easy-Php.git
cd easy-php
php thor serve
```

## Configuración inicial 

Los datos de configuración para la librería se almacenan en el directorio **EASYPHP/config/config.php**. Aunque EasyPHP requiere muy poca configuración adicional, es posible que desees revisar el archivo en caso de que necesites cambiar la zona horaria **(timezone)**.

### Configuración de variables de entorno 

En EasyPHP, puedes ajustar la configuración de tu aplicación según si se ejecuta en tu computadora local o en un servidor de producción. Esto se logra a través del archivo **```.env```** en la raíz de tu proyecto EasyPHP, donde se almacenan las configuraciones clave dependiendo del entorno de uso.

### Base de datos y migraciones
La configuración de la base de datos en EasyPHP se gestiona a través del archivo **```.env```**, que se encuentra en la raíz de tu proyecto. Puedes definir las credenciales de la base de datos y otras configuraciones relacionadas con la conexión en este archivo.
```
DB_DRIVER=mysql
HOST=localhost
DB_NAME=test 
DB_USER=root 
DB_PASSWORD=
DB_CHARSET=utf8

```

Es importante mencionar que, por el momento, EasyPHP solo admite MySQL como motor de base de datos.


# Lo Básico  
## Creación de archivos
Para generar la estructura de un patrón MVC (Modelo-Vista-Controlador) en EasyPHP, puedes utilizar el siguiente comando en la línea de comandos (ya sea en el CMD de Windows o en un terminal de otros sistemas):
```
php thor create:all nombre_archivo
```
### Creación de archivos individualmente
Si deseas crear archivos individualmente en EasyPHP, puedes utilizar los siguientes comandos en la línea de comandos:
* Para crear un controlador:
```
php Thor create:controller nombre_archivo
```
* Para crear un modelo:
```
php thor create:model nombre_archivo
```
* Para crear una migración:
```
php thor create:migration nombre_archivo
```

Estos comandos te permiten generar controladores, modelos y migraciones de base de datos de manera independiente, lo que facilita la organización y el desarrollo de tu aplicación siguiendo el patrón MVC (Modelo-Vista-Controlador) en EasyPHP.

## Rutas
Para crear una nueva ruta en EasyPHP, debes dirigirte a la carpeta app\routes y crear un nuevo archivo con la extensión .php. En este archivo, podrás definir las rutas y las acciones correspondientes que determinarán cómo tu aplicación responde a las solicitudes entrantes.
#### Metodos Soportados 
*GET, POST, PUT, DELETE*
* Ejemplo 
```php
Router::get('/', function (Request $request) {
    echo 'Hello World!';
});
```
o 
llamando a un controlador
```php
Router::get('/home', [new TestController, 'index']);
```
### Rutas con prefijos
En EasyPHP, puedes definir rutas con prefijos para agrupar rutas relacionadas bajo una URL común.

```php
Router::prefix('/api', function () {
    Router::get('/', function () {
        echo 'Hello World!';
    });
});
```
## Clase Request
La clase `Request` se encarga de manejar las solicitudes HTTP entrantes en EasyPHP. Proporciona métodos para acceder a información relevante sobre la solicitud, como el método HTTP, la URL, los parámetros y los encabezados.

### Métodos Principales
- `getHeaders()`: Devuelve un array con los encabezados de la solicitud.
- `getContentType()`: Obtiene el tipo de contenido de la solicitud.
- `getMethod()`: Obtiene el método de solicitud HTTP (GET, POST, etc.).
- `getUrl()`: Obtiene la URL de la solicitud.
- `getParameters()`: Obtiene los parámetros de la solicitud.
- `searchParam($name)`: Busca un parámetro por nombre y devuelve su valor, o "Parameter not found" si no se encuentra.
- `getFiles()`: Obtiene los archivos enviados en la solicitud.
- `setFiles($files)`: Establece los archivos de la solicitud.

La clase `Request` es fundamental para la manipulación de las solicitudes HTTP en tu aplicación EasyPHP.

## Clase Response
La clase `Response` se encarga de manejar las respuestas HTTP en EasyPHP. Proporciona métodos para enviar respuestas con códigos de estado, datos JSON, vistas HTML y redirecciones.

### Métodos Principales
- `http_code($code)`: Establece el código de respuesta HTTP y devuelve una instancia de la clase `Response`.
- `json($data = [])`: Envía una respuesta en formato JSON con el tipo de contenido adecuado.
- `view($viewName, $data = [])`: Muestra una vista HTML en función del nombre proporcionado y los datos proporcionados.
- `redirect($path)`: Realiza una redirección a una URL especificada.

La clase `Response` es esencial para gestionar las respuestas y vistas en tu aplicación EasyPHP, lo que permite presentar información de manera efectiva a los usuarios.
 * Ejemplo
 ```php
    Response::http_code(200)::json(['hello' => 'world']);
 ```


## EasyPHP ORM

## Clase EasyOrm
La clase `EasyOrm` es una extensión de la clase `Database` en EasyPHP y se utiliza para simplificar las operaciones de acceso a la base de datos. Proporciona métodos para realizar consultas SELECT, INSERT, UPDATE y DELETE, así como operaciones de JOIN en la base de datos.

### Métodos Principales
- `select($column = ['*'])`: Prepara una consulta SELECT con la opción de seleccionar columnas específicas.
- `insert($data = [])`: Realiza una inserción de datos en la tabla.
- `update($id, $data = [], $column = 'id')`: Actualiza un registro en la tabla en función del ID proporcionado.
- `delete($column, $id)`: Elimina un registro de la tabla en función de la columna y el ID especificados.
- `where($column, $value, $operator = '=')`: Establece una cláusula WHERE para la consulta SQL.
- `join($table, $firstColumn, $operator = '=', $secondColumn = null, $type = 'inner', $where = false)`: Realiza una operación de JOIN con otra tabla.
- `orderBy($column, $direction = 'asc')`: Ordena los resultados de la consulta.
- `groupBy($column)`: Agrupa los resultados de la consulta por una columna específica.
- `get()`: Ejecuta la consulta SQL y devuelve todos los resultados como un array de objetos.
- `first()`: Ejecuta la consulta SQL y devuelve el primer resultado como un objeto.
- `lastInsertId()`: Recupera el último ID insertado en la base de datos.

La clase `EasyOrm` simplifica las operaciones de base de datos en tu aplicación EasyPHP, lo que te permite interactuar con la base de datos de manera eficiente.

## Modelos que Extienden EasyOrm
En EasyPHP, los modelos de tu aplicación extienden automáticamente la clase `EasyOrm`, lo que proporciona a los modelos una serie de métodos predefinidos para realizar operaciones CRUD en la base de datos. Esto simplifica significativamente la interacción con la base de datos.

**Nota:** Los archivos modelo (`Model`) en EasyPHP ya incluyen estos métodos de forma predeterminada debido a la herencia de la clase `EasyOrm`. Esto significa que no es necesario volver a definir estos métodos en tus modelos. Puedes utilizarlos directamente en tus modelos sin tener que escribir código adicional.

La herencia de la clase `EasyOrm` en los modelos agiliza la interacción con la base de datos y simplifica el desarrollo de tu aplicación EasyPHP. Los métodos CRUD y otras operaciones están disponibles de forma predeterminada en tus modelos, lo que te ahorra tiempo y esfuerzo en el desarrollo.

## Ejemplos
#### Creación de un nuevo registro:
- Las llaves deben ser los mismos nombres de las columnas de la tabla. Aquí tienes el ejemplo corregido: 
```
$user = new User(); 
$user->insert(['name' => 'John', 'email' => 'john@example.com']);
```
En este ejemplo, 'name' y 'email' son las llaves del array asociativo, y representan las columnas de la tabla donde se insertarán los valores 'John' y 'john@example.com'.

#### Actualización de un registro existente:
```
$user = new User();
$user->update(1, ['email' => 'newemail@example.com','id_user']);
```
#### Eliminación de un registro:
```
$user = new User();
$user->delete('id', 2);
```
#### Consulta SELECT:
``` 
$user = new User();
$users = $user->select(['name', 'email'])->where('name', 'John')->get();
```
### Método `EasyOrm::join`
Unión de una tabla a la consulta actual.

```php
public function join(
    $table,
    $firstColumn,
    $operator = '=',
    $secondColumn = null,
    $type = 'inner',
    $where = false
) { }
```
 `Parámetros:`
 * `$table` (string): El nombre de la tabla a unir.
 * `$firstColumn` (string): La columna en la que se realizará la unión.
 * `$operator` (string): El operador a utilizar para la unión.
 * `$secondColumn` (string|null): La columna en la que se realizará la unión, o null si se utiliza solo la primera columna.
 * `$type` (string): El tipo de unión a realizar. Debe ser uno de: 'inner', 'left', 'right', 'full', 'cross'.
 * `$where` (string|bool): La condición WHERE para la unión, o false para excluir.
 ##### Ejemplo de uso
 Supongamos que 'User' es el modelo para la tabla 'users'. Puedes realizar una consulta SELECT con JOIN de la siguiente manera:
```
$user = new User();
$resultados = $user
    ->select()
    ->join('roles', 'role_id', '=', 'id')
    ->join('profile', 'user_id', '=', 'id')
    ->get();
```
## Thor CLI

Thor es una herramienta de línea de comandos que facilita la creación y migración de archivos en EasyPHP. Te permite generar rápidamente archivos esenciales para tu aplicación.

### Ejemplo de comandos

A continuación se presentan algunos de los comandos disponibles con Thor:

- `php thor create:model <name>`: Crea un archivo de modelo en EasyPHP.
- `phph thor create:controller <name>`: Crea un archivo de controlador en EasyPHP.
- `php thor create:migration <name>`: Crea un archivo de migración en EasyPHP.

### Migraciones

Thor también admite migraciones, lo que facilita la gestión de cambios en la base de datos de tu aplicación:

- `php thor migrate`: Ejecuta todas las migraciones registradas en tu aplicación.
- `php thor rollback`: Revierte todas las migraciones a un estado anterior.

### Ayuda

Para obtener ayuda y conocer más sobre los comandos disponibles, puedes utilizar el siguiente comando:

- `thor -h`: Muestra la ayuda y proporciona información detallada sobre cómo usar Thor.

Thor es una herramienta útil para agilizar el desarrollo en EasyPHP, permitiéndote generar rápidamente los archivos necesarios y gestionar las migraciones de tu base de datos.


[Donar😘](https://www.paypal.me/detoditoeber23)

