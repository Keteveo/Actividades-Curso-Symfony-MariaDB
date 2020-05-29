<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Persona;

class PersonaController extends AbstractController
{

    /**
    * @Route("/persona", name="persona")
    */
    public function index()
    {
        //Si no se ha seleccionado una ruta, se redirecciona al listado
        return $this->redirectToRoute('listado');
    }

    /**
     * @Route("/persona/alta", name="alta")
     */
    public function altaPersona()
    {
        //NOTA: Este método únicamente renderiza el formulario 
        //El resultado del formulario POST se recoge en el método nuevaPersona
        return $this->render('persona/altaPersona.html.twig', [
            'titulo' => 'Alta de persona',
        ]);
    }

     /**
     * @Route("/persona/nuevaPersona", name="nuevaPersona")
     */
    public function nuevaPersona(Request $request)
    {
        //Este método recoge el formulario en la variable $request 

        //Creamos una instancia de Entity Manager para operar contra el modelo
        $EntityManager=$this->getDoctrine()->getManager();
        $personas_repo=$this->getDoctrine()->getRepository(Persona::class);

        //Como vamos a introducir datos en la entidad a través de Entity Manager, primero creo una variable de la clase de la entidad
        $alta=new Persona();
        //Extraemos los valores del request a variables 
        $dni =$request->get('inputDni');
        $nombre =$request->get('inputNombre');
        $apellidos =$request->get('inputApellidos');
        $edad = $request->get('inputEdad');
  
        //Mejora: comprobación de datos básica. Ningún campo puede estar vacío 
        if ( $edad == 0 || strlen($dni)==0 || strlen($nombre)==0 ||strlen($apellidos)==0) 
        {
            //Se renderiza el listado, pero mostrando un mensaje de error
            $personas=$personas_repo->findAll();
            return $this->render('persona/listadoPersona.html.twig', [
                'persona' => $personas,
                'titulo' => 'No se pudo realizar el alta',
                'mensaje' => 'Los campos no pueden dejarse en blanco'
            ]);
        }
        else if (false) //aquí irían otras condiciones de error, como que el dni esté duplicado
        {
            //Se renderiza el listado, pero mostrando un mensaje de error
            $personas=$personas_repo->findAll();
            return $this->render('persona/listadoPersona.html.twig', [
                'persona' => $personas,
                'titulo' => 'No se pudo realizar el alta',
                'mensaje' => 'ya existe ese dni en la base de datos'
            ]);
        }
        
        //Si llegamos aquí, es porque se ha pasado la validación básica de datos
        //Creo un array multidimensional que usaré para mostrar la persona dada de alta usando la tabla que muestra varias personas 
        $nuevo=array(
            ['dni'=>$dni,
            'nombre'=>$nombre,
            'apellidos'=>$apellidos,
            'edad'=>$edad]
        );  

        //Asigno a la entidad los valores de la nueva persona   
        $alta->setNombre($nuevo[0]['nombre']);
        $alta->setApellidos($nuevo[0]['apellidos']);
        $alta->setDni($nuevo[0]['dni']);
        $alta->setEdad($nuevo[0]['edad']);

        
        //Cargo los datos del alta, y a continuación hago un flush para que sean efectivos
        $EntityManager->persist($alta);
        $EntityManager->flush();

        //Muestra el listado, pero indicando como título "realizado alta...", y pasando el array multidimensional de personas con una única persona en la posición 0
        return $this->render('persona/listadoPersona.html.twig', [
            'titulo' => 'Listado actualizado',
            'persona' => $nuevo,
            'mensaje' => 'Realizado el alta de '.$nuevo[0]['nombre']
        ]);

    }


    /**
     * @Route("/persona/listado", name="listado")
     */
    public function listadoPersona()
    {
        //Primero se crea una instancia de EntityManager
        $EntityManager=$this->getDoctrine()->getManager();
        //a continuación se crea un repositorio con los datos del modelo de clase Persona
        $personas_repo=$this->getDoctrine()->getRepository(Persona::class);
        //Se carga en la variable $personas todo el contenido del repositorio
        $personas=$personas_repo->findAll();
        
        //Se renderiza el listado, pasando como parámetro todas las personas que había sacado del repositorio
        return $this->render('persona/listadoPersona.html.twig', [
            'persona' => $personas,
            'titulo' => 'Listado de personas',
            'mensaje' => ''
        ]);
    }

    /**
     * @Route("/persona/modifica", name="modifica")
     */
    public function modificaPersona()
    {
        return $this->render('persona/modificaPersona.html.twig', [
            'titulo' => 'Modificación de persona',
        ]);
    }

    /**
     * @Route("/persona/baja", name="baja")
     */
    public function bajaPersona()
    {
       //Primero se crea una instancia de EntityManager
       $EntityManager=$this->getDoctrine()->getManager();
       //a continuación se crea un repositorio con los datos del modelo de clase Persona
       $personas_repo=$this->getDoctrine()->getRepository(Persona::class);
       //Se carga en la variable $personas todo el contenido del repositorio
       $personas=$personas_repo->findAll();
       
       //Se renderiza el formulario, que incluirá un listado de DNI. Para ello se envía  el listado de personas sacado del repositorio
       return $this->render('persona/bajaPersona.html.twig', [
           'persona' => $personas,
           'titulo' => 'Baja de personas',
           'mensaje' => 'Introduce el DNI de la persona a dar de baja',
        ]);
    }
    
 
     /**
     * @Route("/persona/elimina", name="elimina")
     */
    public function elimina(Request $request)
    {
        //Primero se crea una instancia de EntityManager
        $EntityManager=$this->getDoctrine()->getManager();
        //a continuación se crea un repositorio con los datos del modelo de clase Persona
        $personas_repo=$this->getDoctrine()->getRepository(Persona::class);
        //Se carga en la variable $personas todo el contenido del repositorio
        $dni=$request->get('inputDni');

        //Si no se rellenó el campo, vuelve a mostrar el formulario de baja con un mensaje de error
        if(!$dni) {
            //Devuelve todas las personas para mostrar el listado de DNIs
            $personas=$personas_repo->findAll();
            return $this->render('persona/bajaPersona.html.twig', [
                'persona' => $personas,
                'titulo' => 'Baja de personas',
                'mensaje' => 'El DNI no puede estar vacío',
            ]);

        }
        //busca las personas con el DNI a dar de baja
        $persona=$personas_repo->findByDni($dni);
        //Borra la persona. Lo hace en un bucle por si hubiera DNIs duplicados por error en la BD, borrar todos
        foreach ($persona as &$borrar) {
            $EntityManager->remove($borrar);
        }
        $EntityManager->flush();
        //Y actualiza el listado de personas
        $personas=$personas_repo->findAll();
        //Si no se encontró el DNI, ho habrá borrado nada y retornará con un mensaje de error
        //Si se encontró el DNI, borrará las personas y devolverá la confirmación
        if(!$persona){
            $msg='No se ha encontrado el DNI '.$dni;
        }
        else{
            $msg='Se ha dado de baja la persona con DNI '.$dni;
        }
        //Se renderiza el formulario, que incluirá un listado de DNIs. Para ello se envía  el listado de personas sacado del repositorio
        return $this->render('persona/bajaPersona.html.twig', [
            'persona' => $personas,
            'titulo' => 'Baja de personas',
            'mensaje' => $msg,
        ]);
    }



}
