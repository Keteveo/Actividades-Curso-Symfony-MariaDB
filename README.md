# Alumno: Miguel A. Pulido Pulido 
**Datos de contacto:** 
* mapulidop@gmail.com (personal)
* mapulidop@telefonica.com (trabajo)
* 659 962 573 (móvil: disponible de 08:00 a 20:00)


# Actividades-Curso-Symfony-MariaDB
A continuación se resume la explicación de los contenidos generados para las distintas unidades didácticas. La explicación la hago en orden inverso, para que la unidad didáctica más reciente se encuentre al principio.
### Unidad Didáctica 2
#### a.- Crear un proyecto para gestionar productos, con un controlador de productos y 4 métodos CRUD
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

#### b.- Crear la plantilla base
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

#### c.- Crear las vistas CRUD de gestión del producto
Se crean los siguientes ficheros de plantilla:
* **C** altaProducto.html.twig
* **U** modificaProducto.html.twig
* **D** bajaProducto.html.twig

Dentro de cada plantilla se cambia el título y la cabecera mostrando en el apartado que se encuentran.
En el contenido se muestra un párrafo con el nombre del apartado

Se añaden clases bootstrap para mostrar el pie de página pegado a la parte inferior de la misma.
Se modifican las clases para que la cabecera vaya en etiqueta header, el cuerpo en main y el pie en footer. 

#### d.- Añadir CDNs, crear contenido de listadoProducto y crear vista con menú 
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

#### e.- Rellenar la tabla de listadoProducto desde el controlador
En el controlador se genera una estructura de datos con dos productos, y se renderiza la vista correspondiente pasando los productos.
En la vista listadoProductos.html.twig se rellena la tabla automáticamente con los productos recibidos como parámetro.


### Unidad didáctica 1

