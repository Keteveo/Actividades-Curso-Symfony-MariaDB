<?php

namespace App\Controller;

use App\Entity\Noticia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class NoticiaController extends AbstractController
{
    /**
     * @Route("/noticia", name="noticia")
     */
    public function index()
    {
        //Primero se crea una instancia de EntityManager
        $EntityManager=$this->getDoctrine()->getManager();
        //a continuación se crea un repositorio con los datos del modelo de clase Persona
        $repositorio=$this->getDoctrine()->getRepository(Noticia::class);
        //Se carga en la variable $personas todo el contenido del repositorio
        $noticias=$repositorio->findAll();

        //Se renderiza el listado, pasando como parámetro todas las noticias del repositorio

        return $this->render('noticia/index.html.twig', [
            'noticias' => $noticias,
        ]);
    }

     /**
     * @Route("/noticia/nueva", name="nueva")
     */
    public function nueva()
    {
        return $this->render('noticia/nueva.html.twig', [
            
        ]);
    }
     /**
     * @Route("/noticia/nuevaNoticia", name="nuevaNoticia")
     */
    public function nuevaNoticia(Request $request)
    {
    //Este método recoge el formulario en la variable $request 

        //Creamos una instancia de Entity Manager para operar contra el modelo
        $EntityManager=$this->getDoctrine()->getManager();
        //$noticias_repo=$this->getDoctrine()->getRepository(Noticia::class);

        //Como vamos a introducir datos en la entidad a través de Entity Manager, primero creo una variable de la clase de la entidad
        $alta=new Noticia();
        //Extraemos los valores del request a variables 
        $titulo =$request->get('titulo');
        $contenido =$request->get('contenido');
        $fecha = (new \DateTime('now'));

        //Una comprobación básica para no introducir por error noticias vacías
        if (!$titulo || !$contenido)
        {
            //Muestra un mensaje de error y devuelve una noticia vacía
            return $this->render('noticia/index.html.twig', [
                'noticias' => $alta,
                'mensaje' => 'La noticia debe tener título y contenido',
            ]);
        }


        //Asigno a la entidad los valores de la nueva noticia   
        $alta->setTitulo($titulo);
        $alta->setContenido($contenido);
        $alta->setFecha($fecha);
        
        //Cargo los datos del alta, y a continuación hago un flush para que sean efectivos
        $EntityManager->persist($alta);
        $EntityManager->flush();

        //obtengo el listado de noticias
        $repositorio=$this->getDoctrine()->getRepository(Noticia::class);
        
        //Se carga en la variable la última noticia introducida
        $ultima=$repositorio->findBy(array(),array('id'=>'DESC'),1,0);

        //Muestra el listado enviando solamente la noticia recién creada, e indicando como título "realizado alta..."
        return $this->render('noticia/index.html.twig', [
            'noticias' => $ultima,
            'mensaje' => 'Creada nueva noticia',
        ]);
    }

    
     /**
     * @Route("/noticia/edicion/{id}", name="edicion")
     */
    public function edicion(Request $request, Noticia $noticia)
    {
        //Primero se crea una instancia de EntityManager
        $EntityManager=$this->getDoctrine()->getManager();
      
        //Si no existe el ID, muestra un error
        if(!$noticia){
            return $this->render('noticia/index.html.twig', [
                'noticias' => $noticia,
                'mensaje' => 'No se puede modificar. No existe la noticia',
            ]);
        }
        
        return $this->render('noticia/edicion.html.twig', [
            'noticia'=> $noticia,
        ]);
    }

     /**
     * @Route("/noticia/modificaNoticia", name="modificaNoticia")
     */
    public function modificaNoticia(Request $request)
    {
      
        //Creamos una instancia de Entity Manager para operar contra el modelo
        $EntityManager=$this->getDoctrine()->getManager();
        $repositorio=$this->getDoctrine()->getRepository(Noticia::class);
                //busca la noticia con el id a dar de baja
        $alta=$repositorio->findById($request->get('id'));

        
        //Asigno a la entidad los valores de la nueva noticia   
        $alta[0]->setTitulo($request->get('titulo'));
        $alta[0]->setContenido($request->get('contenido'));
        $alta[0]->setFecha($request->get('fecha'));
        
        //Cargo los datos del alta, y a continuación hago un flush para que sean efectivos
        $EntityManager->persist($alta[0]);
        $EntityManager->flush();




        //Muestra el listado enviando solamente la noticia recién creada, e indicando como título "realizado alta..."
        return $this->render('noticia/index.html.twig', [
            'noticias' => $alta,
            'mensaje' => 'Se ha modificado la noticia',
        ]);
    }

    /**
     * @Route("/noticia/borrado/{id}", name="borrado")
     */
    public function borrado($id)
    {
        //Primero se crea una instancia de EntityManager
        $EntityManager=$this->getDoctrine()->getManager();
        //a continuación se crea un repositorio con los datos del modelo de clase Persona
        $repositorio=$this->getDoctrine()->getRepository(Noticia::class);
        
        //busca la noticia con el id a dar de baja
        $borrar=$repositorio->findById($id);

        //Si no existe el ID, muestra un error
        if(!$borrar){
            return $this->render('noticia/index.html.twig', [
                'noticias' => $borrar,
                'mensaje' => 'No se puede realizar el borrado. No existe la noticia',
            ]);
        }
        //Borra la noticia seleccionada
        $EntityManager->remove($borrar[0]);
        $EntityManager->flush();
        //Y actualiza el listado de noticias con el mensaje de borrado, y la noticia borrada

        return $this->render('noticia/index.html.twig', [
            'noticias' => $borrar,
            'mensaje' => 'Noticia eliminada',
        ]);
    }
}
