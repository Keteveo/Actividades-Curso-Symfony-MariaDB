# Actividades-Curso-Symfony-MariaDB
A continuación se resume la explicación de los contenidos generados para las distintas unidades didácticas. La explicación la hago en orden inverso, para que la unidad didáctica más reciente se encuentre al principio.
### Unidad Didáctica 2
#### a.- Crear un proyecto para gestionar productos, con un controlador de productos y 4 métodos CRUD
Se crea el proyecto: **gestorProductos**, dentro de él se crea el controlador **productosController** con los siguientes métodos:
* **C** altaProducto
* **R** listadoProducto
* **U** modificaProducto
* **D** bajaProducto
#### b.- Crear la plantilla base
Se genera una plantilla base *base.html.twig* en html5 con los siguientes bloques:
* titulo
* CSS
* scripts
* cabecera
* contenido
* pie

#### c.- Crear las vistas CRUD de gestión del producto
Se crean los siguientes ficheros de plantilla:
* **C** altaProducto.html.twig
* **R** listadoProducto.html.twig
* **U** modificaProducto.html.twig
* **D** bajaProducto.html.twig

Dentro de cada plantilla se cambia el título y la cabecera mostrando en el apartado que se encuentran.

En el contenido se muestra un párrafo con el nombre del apartado

#### d.- Añadir CDNs, crear contenido de listadoProducto y crear vista con menú 
Se añaden los enlaces a las CDN de Bootstrap y FontAwesome
En la vista listadoProducto se añade una tabla con los campos:
* código
* nombre
* stock
* precio
Se crea una navegación bootstrap en la vista menu.html.twig que muestra el menú resaltando el apartado en el que se encuentra

#### e.- Rellenar la tabla de listadoProducto desde el controlador
En el controlador se genera una estructura de datos con dos productos, y se renderiza la vista correspondiente pasando los productos.
En la vista listadoProductos.html.twig se rellena la tabla automáticamente con los productos recibidos como parámetro.


### Unidad didáctica 1
Ejercicios de la Unidad Didáctica 1 del curso de Symfony+MariaDB

Se ha subido un commit para cada uno de los 5 puntos requeridos.

En el punto 4, hay una errata. En la vista noticias.html.twig, se repite dos veces el parámetro {{categoria}}. En lugar de esto, se deben mostrar {{categoria}} y {{provincia}}
Se ha corregido en el último commit
