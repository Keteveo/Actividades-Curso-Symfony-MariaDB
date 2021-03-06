# Alumno: Miguel A. Pulido Pulido 
**Datos de contacto:** 
* mapulidop@gmail.com (personal)
* mapulidop@telefonica.com (trabajo)
* 659 962 573 (móvil: disponible de 08:00 a 20:00)


# Actividades-Curso-Symfony-MariaDB
A continuación se resume la explicación de los contenidos generados para las distintas unidades didácticas. La explicación la hago en orden inverso, para que la unidad didáctica más reciente se encuentre al principio.

# Proyecto final integrador
## Crear la Base de datos y tabla
Creo el proyecto "proyectofinal" dentro del directorio symfony con el siguiente comando:

    $ composer create-project symfony/website-skeleton proyectofinal
Modifico a continuación etc/hosts y el fichero de virtualhosts de apache para redireccionar a mi nuevo proyecto usando la url http://proyectofinal.com.test

Para acabar el Punto 1, creo la base de datos con la tabla que se solicita, y la relleno con un par de valores de prueba

    CREATE DATABASE IF NOT EXISTS diariocurso;
    USE diariocurso;
    CREATE TABLE IF NOT EXISTS  noticia (id int(8) AUTO_INCREMENT, titulo VARCHAR(200), contenido VARCHAR(5000), fecha DATETIME, PRIMARY KEY (id));
    INSERT INTO noticia (titulo, contenido, fecha) VALUES 
        ('Inicio del curso','Arranca el curso de Symfony+MariaDB con un grupo reducido de alumnos',CURRENT_TIME), 
        ('UD1','La primera unidad didáctica es una pequeña introducción al mundo Symfony', CURRENT_TIME-1);

A continuación modifico el fichero .env de nuestro proyecto Symfony para enlazar correctamente a nuestra base de datos

    DATABASE_URL=mysql://root@127.0.0.1:3306/diariocurso
Y, por último, importo la base de datos que creé para crear la entidad, y regenero para que cree los getters y setters

    $ php bin/console doctrine:mapping:import App\Entity annotation --path src/Entity
    $ php bin/console make:entity --regenerate App

## Crear las vistas CRUD
En primer lugar modifico el fichero con la vista base para incluir los enlaces cloud de bootstrap y fontawesome.
A continuación, creo el controlador noticia para utilizarlo probando las vistas según las voy generando.

    $ php bin/console make:controller noticia

En la vista base creo una estructura con los bloques cabecera, contenido y pie, inicialmente vacíos de contenido dinámico pero con una estructura base de bootstrap.

#### Menú
En la cabecera he insertado un menú muy básico que he separado en una plantilla independiente menu.html.twig  

Este menú inicialmente tiene solamente dos opciones, una para mostrar todas las noticias que hace asimismo de título de sección, y otra con un botón para dar de alta una noticia. 

En el menú no he puesto las opciones cr**UD** de modificación y borrado porque éstas estarán incluidas como iconos asociados a cada noticia en la propia vista de listado de noticias.

### Vista de listado (cRud)
He decidido que el listado se realizará en el index de plantillas de Noticia. De ese modo, cada vez que se ingrese a la ruta /noticias/ se mostrará el listado con las distintas opciones en el menú y las noticias.

Como quiero reutilizar código, he creado el listado para renderizar un array de noticias que se reciben como parámetro y un mensaje. De ese modo, desde las vistas de edición y alta, podré mostrar la nueva noticia dada de alta renderizando la vista de listado con un mensaje correcto. Igualmente, esta vista servirá para emitir mensajes de error si solo se pasa un mensaje y el array de noticias que se pasa está vacío.

Para mostrar el listado de noticias (Read) he decidido mostrar cada noticia en una tarjeta de la clase *card* de bootstrap, con el título en el encabezado, el contenido en el cuerpo, y los iconos de modificar/borrar en el pie.

En la parte superior se deja un espacio para insertar un mensaje, dado que esta vista será llamada desde varios métodos del controlador, indicando la operación que se ha realizado.
La vista base recibe como parámetros: 

* **mensaje** que es el mensaje que se mostrará en la parte superior de la vista
* **noticias** que es un array de noticias con los campos **titulo**, **contenido** y **fecha**

He puesto unos condicionales para mostrar los botones de modificación y borrado **únicamente** cuando se renderiza la vista sin mensaje. Es decir, solo cuando se pretenda mostrar el listado sin más. Si se muestra con un mensaje, será del estilo "ha dado de alta la nueva noticia", en cuyo caso no tiene sentido mostrar sobre ella los botones de editar y modificar, y solo se mostrará la caja de noticia sin opciones asociadas.

### Vista de nueva noticia (Crud)
La vista de nueva noticia (Create) consiste en un formulario con los campos titulo y contenido, además de un botón de "crear noticia".
He creado la vista directamente en html, no he usado el formbuilder, puesto que me resulta más práctico. El resultado es el mismo.

La vista de nueva noticia no recibe parámetros.

### Vista de modificación (crUd)
La vista de modificación consiste en un formulario de edición en una tarjeta bootstrap de 6 unidades de ancho, y en la parte derecha otra tarjeta bootstrap mostrando el contenido inicial.

Igual que en el alta, no he usado formbuilder, sino que he generado el formulario directamente usando html.

La vista de modificación recibe como parámetro una noticia, cuyo contenido añade a los valores por defecto del formulario para poder editar su contenido.

### Vista de borrado (cruD)

Para el borrado no es necesario realizar una vista independiente.

En la vista de listado de noticias se mostrarán los iconos de edición y borrado de cada noticia en su tarjeta, que serán los que dirijan a la ruta de borrado. Una vez se haya realizado el borrado, se renderizará la vista de listado usando un mensaje de confirmación del borrado.

## Métodos de controlador CRUD

### Gestionar el alta de la noticia
Se implementa el método \noticia\nueva que genera la vista con el formulario
Se implementa también el método \noticia\nuevanoticia que atiende al SUBMIT asociado al botón de "Crear noticia" del formulario. Es desde este métdodo desde el cual se comunica con el modelo realizando el alta en la base de datos. 

En cuanto a la fecha, el formulario de alta no solicita una nueva fecha/hora de creación, sino que se utiliza la fecha actual del sistema para darla de alta.

## Gestionar la baja de la noticia
El método del controlador que atiende la ruta \noticia\baja\id se encarga de realizar el borrado en la BD del registro con ese ID. 

    Posible mejora:    
    Implementar una vista donde se muestre la noticia y se pida una confirmación. Dicha confirmación sería atendida por el método que se encarga de realizar el borrado.

## Gestionar la modificación de la noticia
En este punto se implementa el método que atiende a la ruta \noticia\edicion\id que se desencadena al pulsar el botón "modificar" del formulario enviando como parámetro el ID de la noticia a modificar. Desde este método se muestra la vista de modificación.

Cuando se modifica la noticia en la vista de modificación se llama a la ruta /noticia/modificaNoticia/id para lo cual se implementa el método que se comunica con la BD y realiza la modificación. Finalmente, devuelve un mensaje de éxito mostrando el listado con la noticia recién modificada.

Sobre la fecha, al modificar se cambia la fecha de la noticia poniendo la fecha actual del sistema.

## Hasta aquí el caso final integrador. El código está comentado. He realizado pruebas funcionando correctamente y contemplando excepciones básicas. Como extra, he añadido un diseño con bootstrap e iconos fontawesome.


# Unidad Didáctica 4
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

### UD4. Punto 3
3.    Haz un listado de las personas almacenadas en la base de datos.
En el anterior punto ya se había dejado preparada una vista que mostrara un listado a partir de un array multidimensional

En la ruta persona/listado se crea una instancia de EntityManager, se carga el contenido del repositorio de personas en una variable, y a continuación se cargan todos datos en un array que se pasa a la vista para mostrar el listado de personas.

### UD4. Punto 4
4.    Crea un cuadro de texto que solicite un dni y elimine la persona correspondiente en la base de datos.
>Antes de realizar lo pedido en este punto se reorganiza el código del apartado anterior, declarando el gestor de entidad al principio del método y realizando unas comprobaciones básicas. Queda como mejora a futuro consultar si existe previamente el DNI en la base de datos para evitar introducir un registro duplicado.
>
>Se modifica además la plantilla del listado para permitir además del título un menjaje. De este modo, se podrá utilizar esta misma plantilla para mostrar excepciones generadas en los distintos métodos de baja, modificación y alta. Eso supuso algún retoque en las plantillas para dejar una identidad visual de conjunto correcta.

Para la baja se crea la ruta /persona/baja en la que el controlador renderiza una vista con el listado de DNIs.
Para renderizar el listado de DNIs, primero consulta el repositorio de personas y le pasa todos los valores a la vista, que rellena una tabla usando solamente los DNIs

El formulario relleno se recoge en la ruta y método elimina, que recibe los parámetros por POST en un Request.

Se comprueba primero si el valor introducido es nulo, en cuyo caso vuelve a renderizar la página de baja con un mensaje de error
A continuación, busca el DNI introducido en el repositorio. SI no se encuentra, renderiza de nuevo la página de baja con un mensaje de error
Si el DNI se encuentra, utiliza el Element Manager para borrar los campos cuyo DNI coincida con el introducido.
Para prevenir el caso de que el DNI pudiera estar repetido por error, realiza la búsqueda en un bucle. Al finalizar el bucle, hace un flush para aplicar los campos
Por último, se renderiza de nuevo la página de baja mostrando un mensaje de éxito con el DNI borrado.


### UD4. Punto 5
5.    Crea un cuadro de texto que solicite un dni y modifique el nombre de dicha persona por: Pepito.
Este apartado es muy parecido al anterior. La diferencia principal es que la vista muestra DNI y nombre, y el formulario pregunta DNI y nombre.
Se genera el método modificaPersona que atiende a la ruta persona/modifica 
También se genera el método modificador que atiende a la ruta modificador, donde se reciben los parámetros POST 

Una vez se introduce un valor en el formulario, se realiza la modificación, introduciendo el nombre elegido en todos los registros con ese DNI.
Se hace una comprobación preliminar y se vuelve a mostrar la vista de modificación con un mensaje de error en caso de que el DNI o nombre sean nulos, o que el DNI no se encuentre en la base de datos.
En caso de éxito, se retorna un mensaje indicando el DNI con el nombre modificado.

### Opciones de mejora
La primera y más obvia es comprobar en el alta si está repetido el DNI igual que se hace en la modificación y borrado.

Otra opción es atender el alta desde una única función, que reciba el posible Request POST. Al arrancar la función, comprobaría si hay o no argumentos POST. EN caso de que no hubiera, debería ejecutar lo que actualmente se encuentra en el método altaPersona(), y si hubiera parámetros, lo que actualmente se encuentra en el método nuevaPersona(). De ese modo, con un único método y una única ruta podría atenderse a ambas funciones.

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

