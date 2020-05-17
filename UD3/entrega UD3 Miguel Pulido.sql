/********************************
*  Unidad Didáctica 3           *
*  Alumno: Miguel A. Pulido     *
*********************************/

-- 1 Crear la base de datos TallerMecánico y crea y rellena las siguientes tablas(40 puntos):
-- TABLA COCHES (matricula: 7 caracteres, marca: entre 1 y 25 caracteres y no puede ser nulo, modelo: entre 1 y 50 caracteres, color: entre 1 y 20 caracteres valor por defecto blanco, precio: número con decimales).
create database TallerMecanico;
USE TallerMecanico;
CREATE TABLE coches(matricula CHAR(7), marca CHAR(25) NOT NULL, modelo CHAR(50), color CHAR(20) DEFAULT 'blanco', precio FLOAT(8,2), PRIMARY KEY (matricula));
INSERT INTO coches (matricula, marca, modelo, color, precio) VALUES ('V1010PB', 'FORD', 'FOCUS', 'BLANCO',17894.78);
INSERT INTO coches (matricula, marca, modelo, color, precio) VALUES ('V1234LC', 'AUDI', 'A4', 'VERDE',25468.65);
INSERT INTO coches (matricula, marca, modelo, color, precio) VALUES ('V2360OX', 'OPEL', '3008', 'AZUL',15476.34);
-- Nota: como la matricula es única para un coche, he indicado que será la clave primaria.

-- TABLA REVISIONES (nRevision número sin decimales auto incrementable y que rellene de ceros a la izquierda, cambioAceite: booleano, cambioFiltro: booleano, fechaEntrada: fecha, horaSalida: hora, matricula: 7 caracteres)

CREATE TABLE revisiones( nRevision INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, cambioAceite BOOLEAN, cambioFiltro BOOLEAN, fechaEntrada DATE, horaSalida TIME, matricula CHAR(7), PRIMARY KEY (nRevision), FOREIGN KEY (matricula) REFERENCES coches(matricula) );
-- Aunque no se solicita en el enunciado, he indicado que matricula es clave foránea enlazada a la tabla coches.
INSERT INTO revisiones (cambioAceite, cambioFiltro,fechaEntrada, horaSalida, matricula) VALUES (TRUE, FALSE, '2018-05-11', '12:30', 'V2360OX');
INSERT INTO revisiones (cambioAceite, cambioFiltro,fechaEntrada, horaSalida, matricula) VALUES (TRUE, FALSE, '2018-06-06', '14:20', 'V1010PB');
INSERT INTO revisiones (cambioAceite, cambioFiltro,fechaEntrada, horaSalida, matricula) VALUES (TRUE, FALSE, '2018-06-07', '17:25', 'V2360OX');

-- 2.    Realiza las siguientes actividades (4 puntos por respuesta):
-- 2.1.  Añade el campo fechaSalida a la tabla revisiones (tipo fecha).
ALTER TABLE revisiones ADD COLUMN fechaSalida date DEFAULT(fechaEntrada);
ALTER TABLE revisiones ALTER COLUMN fechaSalida DROP DEFAULT;
-- NOTA: Para evitar dejarlo en blanco he añadido un valor por defecto igual al día de la entrada, por poner algo en los registros existentes. Luego he quitado el valor por defecto, ya que los nuevos registros no tendrán inicialmente fecha de salida. 

-- 2.2.  Elimina el campo horaSalida de la tabla revisiones.
ALTER TABLE revisiones DROP COLUMN horaSalida;

-- 2.3.  Modifica la cantidad de caracteres del campo modelo de la tabla coches para que tenga un máximo de 100.
ALTER TABLE coches MODIFY COLUMN modelo char(100);
-- Como se amplía el número de caracteres, la información contenida en el campo se mantiene. Si se hubiera reducido, se debería haber tenido en cuenta que en algún registro se podría haber perdido información.

-- 2.4.  Actualiza a “Peugeot” la marca del coche V2360OX.
UPDATE coches SET marca='Peugeot' WHERE matricula='V2360OX';

-- 2.5.  Eliminar el coche cuya matrícula es: V1010PB.
DELETE FROM revisiones WHERE matricula='V1010PB';
DELETE FROM coches WHERE matricula='V1010PB';
-- En este caso, como en la definición de las tables creamos una referencia, debemos respetar la integridad relacional. Es decir, primero hay que borrar las revisiones del coche con la matrícula señalada y luego el coche. De otro modo nos daría un error.

-- 2.6.  Consulta que muestre los coches cuyo precio supere los 20000  y sean de color blanco.
SELECT * FROM coches WHERE precio>20000 AND color='blanco';

-- 2.7.  Consulta que muestre las matrículas de coches que hayan cambiado el aceite y filtro.
SELECT coches.matricula FROM coches INNER JOIN revisiones ON coches.matricula=revisiones.matricula WHERE revisiones.cambioAceite AND revisiones.cambioFiltro;

-- 2.8.  Consulta que muestre los coches cuyo precio sean menores de 15000  y mayores de 20000.
-- No entiendo bien la pregunta, porque si busco los coches cuyo precio sea menor de 15000 y mayor de 20000, siempre devolverá una consulta vacía. 
SELECT * FROM coches WHERE precio<15000 AND precio>20000;
-- Ahora bien, si lo que se pide es que la consulta muestre los coches con precio menor de 15000 y los coches con precio mayor que 20000 esta sería la consulta:
SELECT * FROM coches WHERE precio<15000 OR precio>20000;
-- La consulta excluiría los coches que cumplen 15000<=precio<=20000

-- 2.9.  Hacer una consulta de la tabla COCHES que muestre los siguientes campos: Marca, modelo, y precio de los coches cuya marca comiencen con la letra A.
SELECT marca, modelo, precio FROM coches WHERE marca LIKE 'A%';
-- 2.10.     Añade y calcula los campos SUBTOTAL, IVA 0.21, TOTAL A PAGAR.
-- NOTA: Como se me pide que añada los campos, añadiré subtotal como campo ya que la tabla revisiones no tiene anteriormente conceptos de precio, y los otros dos los crearemos como campo virtual calculado a partir del subtotal.
ALTER TABLE revisiones ADD COLUMN subtotal FLOAT(8,2);
ALTER TABLE revisiones ADD COLUMN IVA FLOAT(8,2) AS (subtotal * 0.21) VIRTUAL;
ALTER TABLE revisiones ADD COLUMN total FLOAT(8,2) AS (subtotal + IVA) VIRTUAL;
-- NOTA: Otra opción hubiera sido crear el campo 'subtotal' tal como se muestra, y posteriormente hacer un SELECT en el que se muestren como campos calculados 'IVA' y 'total' a partir de 'subtotal'. En ese caso, estas serían las consultas SQL:
ALTER TABLE revisiones ADD COLUMN subtotal FLOAT(8,2);
SELECT subtotal, subtotal*0.21 AS 'IVA', subtotal*1.21 AS 'Total a pagar' FROM revisiones;
-- NOTA: En cualquier caso, como subtotal inicialmente tiene un valor NULL, todos los campos indicados tendrán el valor NULL. Para resolverlo, se podría haber iniciado subtotal como campo calculado a partir de las dos operaciones de mantenimiento realizables… pero todo eso no lo pide el enunciado de la pregunta.
-- 2.11.     Consulta que muestre todos los datos del coche y de la revisión del coche cuya matrícula es V2360OX.
SELECT coches.*, revisiones.* FROM coches INNER JOIN revisiones ON coches.matricula = revisiones.matricula WHERE coches.matricula='V2360OX';
-- 2.12.     Consulta que muestre las distintas marcas de coche que hay en la tabla coches.
SELECT DISTINCT marca FROM coches;
-- 2.13. Consulta que muestre la marca y el modelo de los coches cuyo precio esté entre 20000 y 30000 ordenado por marca de la z-a.
SELECT marca, modelo FROM coches WHERE precio>=20000 and precio<=30000 ORDER BY marca DESC;
-- 2.14. Consulta que muestre los datos de las revisiones realizadas en mayo del 2018.
SELECT * FROM revisiones WHERE (fechaEntrada<'2018-05-1' AND (fechaSalida>='2018-05-01' OR fechaSalida=NULL)) OR (MONTH(fechaEntrada)=5 AND YEAR(fechaEntrada)=2018);
-- He considerado que las revisiones hechas en mayo son aquellas en las que el vehículo estuvo algún día del mes de mayo en el taller. Es decir, que si entraron antes de mayo, hayan salido como mínimo después del 1 de mayo o no hayan salido aún, o bien aquellos que hayan entrado durante mayo, independientemente del día de salida.

/* 2.15. ¿Cambiarías algo de alguna de las tablas para hacer que la base de datos sea más óptima?
 * Tal como está expuesto el enunciado, no se establecen claves primarias ni foráneas en las tablas . Un ejemplo de cómo afecta esto lo encontramos en el punto 2.5 en el que se pide eliminar un coche por su matrícula, sin antes comprobar si hay algún registro de revisiones de ese coche. Si no se hubiera definido la relación de claves primaria y secundaria entre ambas tablas, si se borrara el coche sin borrar antes sus revisiones, quedaría una revisión con una matrícula que no corresponde a ningún coche. Por el contrario, tal como definí las tablas con el vínculo 1:n entre sí, el intento de borrado del vehículo habría arrojado un error porque existe una revisión asignada a esa matrícula, por garantizar la integridad referencial. Para poder borrarlo, primero habría que borrar la revisión y luego el vehículo.
 * Aparte de esto, la base de datos no está normalizada:
 *
 * Empezamos a normalizar por la tabla coches
 * La dupla marca-modelo define un modelo de coche. Por ese motivo, los sacaría a dejando en la tabla coches marcamodeloID como clave foránea. Mantendría la matrícula como clave primaria, o bien crearía un campo iD autoincrementado como clave primaria. El precio también lo mantendría, dado que no está enlazado a la marca-modelo, pues entran en juego cosas como las promociones etc.
 *
 * La tabla MarcaModelo tendría un campo ID como clave primaria, y el campo modelo y marca como atributos. Ahora bien, la marca debería sacarse a una tabla marca, dejando en MarcaModelo como clave foranea marcaID. Esto haría que la tabla MarcaModelo tenga como campos ID, modelo y MarcaID. La tabla marca tendría un campo ID como primaria y el campo marca.
 *
 * Seguimos por la tabla revisiones. En ella inicialmente tendríamos los campos nRevision, cambioAceite, cambioFiltro, fechaEntrada, fechaSalida, Matricula como clave foranea y subtotal. Ninguno de esos campos se va a repetir siguiendo una pauta, por lo que no es necesario sacarlo a otra tabla. Ahora bien, tenemos dos conceptos de mantenimiento cada uno en un campo, que son susceptibles de ir a otra tabla 'concepto' dejando en revisiones la clave foranea conceptoID que los enlaza. Asimismo, subtotal se calcula a partir de los trabajos realizados, por lo que se va a esa nueva tabla como 'precio'. Se podría añadir el campo unidades en caso que un concepto pueda realizarse más de una vez, aunque eso no se especifica en la lógica de negocio. Mantenemos en la tabla las fechas de entrada y salida, por lo que la tabla 'revisiones' quedaría con los campos nRevision como clave primaria, fechaEntrada, fechaSalida, y trabajoID como clave foranea enlazada a la tabla 'concepto'.
 * La tabla 'concepto' inicialmente tendría un campo ID como pk, un campo 'nombre' que podrá ser 'Cambio aceite' o 'Cambio filtro', o cualquier otro concepto que se pueda definir posteriormente y un precio.
 *
 * Por último, los campos subtotal, IVA y total a pagar no estarían dentro de la BD dado que son campos dinámicos que se calculan a partir de los campos de las distintas tablas. Para calcular el subtotal a pagar por una revisión, miraríamos 
 *
 * Una vez ralizada la normalización, vuelvo a comprobar si todas las relaciones se mantienen 1:n y veo que es así, que no ha surgido ninguna tabla con relación m:n que nos haga generar una tabla intermedia con doble clave pública.
 *
 * Para acabar, si bien las matrículas son únicas y se pueden usar como clave primaria de la tabla 'coche', yo hubiera creado campo ID único y autoincrementado que fuera la clave primaria de la tabla, siendo la matrícula un atributo. De ese modo mantendríamos una nomenclatura homogenea en toda la BD, en la que todas las tablas tengan un campo ID autoincrementado como PK.
*/