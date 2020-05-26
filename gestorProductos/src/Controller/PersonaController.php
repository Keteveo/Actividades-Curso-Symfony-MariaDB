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

        //Como vamos a introducir datos en la entidad a través de Entity Manager, primero creo una variable de la clase de la entidad
        $alta=new Persona();

        //Creo un array multidimensional que usaré para mostrar la persona dada de alta usando la tabla que muestra varias personas 
        $nuevo=array(
            ['DNI'=>$request->get('inputDNI'),
            'nombre'=>$request->get('inputNombre'),
            'apellidos'=>$request->get('inputApellidos'),
            'edad'=>$request->get('inputEdad')],
        );  
 
        //Asigno a la entidad los valores de la nueva persona   
        $alta->setNombre($nuevo[0]['nombre']);
        $alta->setApellidos($nuevo[0]['apellidos']);
        $alta->setDNI($nuevo[0]['DNI']);
        $alta->setEdad($nuevo[0]['edad']);

        //NOTA: inicialmente no se realiza comprobación de que los datos son  válidos
        //Como mejora, comprobaría que todos los campos tienen algún dato para evitar un error al intentar subir un valor vacío a un valor definido como "not null"

        //Primero creo el EntityManager y después lo utilizo para cargar los datos del alta, y a continuación hago un flush para que sean efectivos
        $EntityManager=$this->getDoctrine()->getManager();
        $EntityManager->persist($alta);
        $EntityManager->flush();

        //Muestra el listado, pero indicando como título "realizado alta...", y pasando el array multidimensional de personas con una única persona en la posición 0
        return $this->render('persona/listadoPersona.html.twig', [
            'titulo' => 'Realizado el alta de '.$nuevo[0]['nombre'],
            'persona' => $nuevo
        ]);

    }


    /**
     * @Route("/persona/listado", name="listado")
     */
    public function listadoPersona()
    {
        $persona= array(
            ['DNI'=>'123412134a',
            'nombre'=>'Felipe',
            'apellidos'=>'Santos mata',
            'edad'=>40],
            ['DNI'=>'098231011c',
            'nombre'=>'Joaquín',
            'apellidos'=>'Rivera Rodríguez',
            'edad'=>50],
            ['DNI'=>'0909808381E',
            'nombre'=>'Jacinto',
            'apellidos'=>'Vivo Muerto',
            'edad'=>30],
            
        );
        return $this->render('persona/listadoPersona.html.twig', [
            'persona' => $persona,
            'titulo' => 'Listado de personas'
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
        return $this->render('persona/bajaPersona.html.twig', [
            'titulo' => 'Baja de persona',
        ]);
    }
    




}
