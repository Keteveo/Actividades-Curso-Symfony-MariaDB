# Alumno: Miguel A. Pulido Pulido 
**Datos de contacto:** 
* mapulidop@gmail.com (personal)
* mapulidop@telefonica.com (trabajo)
* 659 962 573 (móvil: disponible de 08:00 a 20:00)


# Actividades-Curso-Symfony-MariaDB
A continuación se resume la explicación de los contenidos generados para las distintas unidades didácticas. La explicación la hago en orden inverso, para que la unidad didáctica más reciente se encuentre al principio.
## Unidad Didáctica 4
Voy a reutilizar el mismo proyecto Symfony usado en las anteriores unidades didácticas. No borraré el controlador de productos y, cuando llegue el momento, crearé un nuevo controlador "persona".
Igualmente, usaré el mismo entorno de servidor de desarrollo en localhost, al que agregaré las rutas necesarias para los nuevos controladores que haya que crear. 
En cuanto a base de datos, uso el mismo servidor usado en la UD3, con una nueba BD llamada "probador"

### UD4. Punto 1
1.    Crea la entidad “Persona” en Symfony. Los atributos de la Persona son: dni, nombre, apellidos, edad. Realiza las anotaciones y métodos correspondientes para que se genere la tabla en la base de datos.

A continuación enumero los pasos seguidos:
#### UD4.1.a.- Cambiar variables de entorno en .env
Mi BD se llama "probador". El login es root, y al estar en localhost en un entorno de prueba no requiere clave.
DATABASE_URL=mysql://rootd@127.0.0.1:3306/probador

#### UD4.1.b.- Definir el formato de los atributos de la entidad
Antes de crear la entidad, defino los atributos que quiero que tenga la tabla según se solicita 

- DNI: 9 caracteres, not null
- Nombre: 200 caracteres, not null
- Apellidos: 255 caracteres, not null
- Edad: entero, not null

#### UD4.1.c.- Creación de la entidad desde consola de comandos
Creo la entidad usando el siguiente comando de consola. Transcribo a continuación el proceso de creación:

~~~
$ php bin/console make:entity Persona

 created: src/Entity/Persona.php
 created: src/Repository/PersonaRepository.php
 
 Entity generated! Now let's add some fields!
 You can always add more fields later manually or by re-running this command.

 New property name (press <return> to stop adding fields):
 > DNI

 Field type (enter ? to see all types) [string]:
 > 


 Field length [255]:
 > 9

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/Persona.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > nombre

 Field type (enter ? to see all types) [string]:
 >
 Field length [255]:
 > 200

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/Persona.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > apellidos

 Field type (enter ? to see all types) [string]:
 >

 Field length [255]:
 >

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/Persona.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > edad

 Field type (enter ? to see all types) [string]:
 > integer
 integer

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/Persona.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 >

  Success! 

 Next: When you're ready, create a migration with php bin/console make:migration
 ~~~

#### UD4.1.d.- Se crea la migración
A continuación creo la migración:

~~~
Puli@Nilo MINGW64 /c/xampp/htdocs/Symfony/gestorproductos (master)
$ php bin/console doctrine:migrations:diff
Generated new migration class to "C:\xampp\htdocs\Symfony\gestorProductos/src/Migrations/Version20200526153323.php"

To run just this migration for testing purposes, you can use migrations:execute --up 20200526153323

To revert the migration you can use migrations:execute --down 20200526153323
~~~

#### UD4.1.e.- Ejecución de la migración 
A continuación, ejecuto la migración para que se realicen los cambios en la Base de datos. 
Al ser la primera migración de la entidad, y no haber previamente creadas otras entidades y atributos, supone simplemente una creación de tablas.

~~~
php bin/console doctrine:migrations:migrate
~~~

Con ello quedaría concluido el punto 1 de la UD4.

### UD4. Punto 2
2.    Crea un formulario solicitando los datos necesarios para dar de alta a una Persona. Los datos ingresados deben guardarse en la base de datos.

Antes de crear el formulario, creo un controlador llamado **Persona** desde el cual se interactuará con las vistas y el modelo.
~~~
$ php bin/console make:controller PersonaController
~~~
Dentro del controlador, crearé un método con la ruta /persona/alta que se encargará de ejecutar el código que interactúa con la vista del formulario de alta.

Antes de seguir, he borrado el controlador y las vistas de la anterior unidad didáctica.

He generado unas nuevas plantillas para los métodos CRUD. Inicialmente sólo debía agregar la que hace el ALTA (C). Las plantillas "modifica" y "baja" están en blanco inicialmente.
La plantilla Listado muestra un listado de personas a partir de un array multidimensional que recibe como parámetro. 

**Creación del formulario**
El método altaPersona, que atiende a la ruta persona/alta, simplemente renderiza la plantilla donde se ubicará el formulario.
Dentro de la plantilla altaPersona.html.twig se genera un formulario dentro del bloque "contenido", usando además bootstrap para mostrar el formulario con un mejor look.

El formulario, al ser rellenado, es atendido desde el controlador con el método "nuevaPersona". Es en este método desde el que se utiliza el entity manager para insertar el dato en el modelo. 
Una vez insertado en la BD, se utiliza la plantilla listadoPersona.html.twig, a la que se pasa como parámetro un array multidimensional de personas. En este caso, sólo lleva una persona. También se pasa como título "Realizado el alta de ".

En el menú se añaden condicionales para resaltar el enlace del método que se ha ejecutado. En caso de estar en la ruta de alta o nuevaPersona, resalta el enlace "Alta".

Por último, se prueba que la funcionalidad sea la solicitada. Se constata que, pese a haber indicado que los campos debían ser "not null", cuando se dejan en blanco en el formulario, los campos no aparecen rellenos en la BD. Dejo esto como punto de investigación futura.



## Unidad Didáctica 3
En el directorio UD3 se incluye el fichero SQL con la resolución de las actividades propuestas.
En los comentarios del fichero se copia el enunciado de cada una de las actividades. 
También se incluyen dentro de comentarios SQL las respuestas de texto y aclaraciones de por qué se ha hecho la resolución de una forma u otra.

## Unidad Didáctica 2
### a.- Crear un proyecto para gestionar productos, con un controlador de productos y 4 métodos CRUD
Se crea el proyecto: **gestorProductos**, dentro de él se crea el controlador **productosController** con los siguientes métodos y rutas asociadas:
|   | Método | Ruta |
| --- | --- | ---|
| **C** | altaProducto     | producto/alta |
| **R** | listadoProducto  | producto/listado |
| **U** | modificaProducto | producto/modifica |
| **D** | bajaProducto     | producto/baja |

Por el momento los métodos se crean sin código. 
Como no hemos creado aún las vistas, todos los métodos renderizan la vista por defecto producto/index.html.twig
El parámetro que pasan es el nombre del controlador/método. 

### b.- Crear la plantilla base
Se genera una plantilla base *base.html.twig* en html5 con los siguientes bloques:
| {{% block %}}  | Contenido del bloque |
| --- | --- |
| titulo          | Título de la página html | 
| hojas_de_estilo | Hojas de estilo y scripts ubicados dentro del <head>  | 
| scripts         | scripts al final del <body> | 
| cabecera        | sección dentro del <body> donde irá la cabecera página | 
| contenido       | sección dentro del <body> donde irá el contenido de las páginas | 
| pie             | sección al final del <body> donde irá el pie de página | 

Dentro de la plantilla base se incluyen los CDNs de Bootstrap y FontAwesome, para poder utilizarlos desde las páginas web

También se incluye un fichero CSS que personaliza los elementos de bootstrap. Para ello se instala primero la función "asset" de Symfony con "composer require symfony/asset"

Se inserta como asset el fichero public/css/estilo.css que inicialmente se crea vacío, y se irá usando a futuro.

### c.- Crear las vistas CRUD de gestión del producto
Se crean los siguientes ficheros de plantilla:
* **C** altaProducto.html.twig
* **U** modificaProducto.html.twig
* **D** bajaProducto.html.twig

Dentro de cada plantilla se cambia el título y la cabecera mostrando en el apartado que se encuentran.
En el contenido se muestra un párrafo con el nombre del apartado

Se añaden clases bootstrap para mostrar el pie de página pegado a la parte inferior de la misma.
Se modifican las clases para que la cabecera vaya en etiqueta header, el cuerpo en main y el pie en footer. 

### d.- Añadir CDNs, crear contenido de listadoProducto y crear vista con menú 
Se pide que se añadan en este punto los enlaces a las CDN de Bootstrap y FontAwesome. Yo los añadí ya en el punto b

Se crea el siguiente fichero de plantilla:
* **R** listadoProducto.html.twig (se deja creada, aunque se completará en el siguiente apartado)

En la vista listadoProducto se añade una tabla con los campos:
* código
* nombre
* stock
* precio
Se crea una navegación bootstrap en la vista menu.html.twig que muestra el menú resaltando el apartado en el que se encuentra usando condicionales a partir de la ruta, usando la función app.request.get('_route')

Se redirecciona desde la ruta /productos/ a la ruta /productos/listado para que por defecto se muestre el listado de productos

### e.- Rellenar la tabla de listadoProducto desde el controlador
En el controlador se genera una estructura de datos con varios productos. En la vista listadoProducto.html.twig se realiza un bucle para recorrer la estructura de datos completa que se recibió como parámetro, y mostrar cada producto en una fila de una tabla.

Se utilizan filtros para mostrar los precios con dos decimales.

