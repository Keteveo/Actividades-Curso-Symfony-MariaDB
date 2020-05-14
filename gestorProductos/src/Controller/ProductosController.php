<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductosController extends AbstractController
{
    /**
     * @Route("/productos", name="productos")
     */
    public function index()
    {
        return $this->render('productos/index.html.twig', [
            'controller_name' => 'ProductosController',
        ]);
    }
    
    /**
     * @Route("/productos/alta", name="alta")
     */
    public function altaProducto()
    {
        return $this->render('productos/altaProducto.html.twig', [
            'titulo' => 'Alta de producto',
        ]);
    }

    /**
     * @Route("/productos/listado", name="listado")
     */
    public function listadoProducto()
    {
        return $this->render('productos/index.html.twig', [
            'controller_name' => 'ProductosController/listadoProducto',
        ]);
    }
    
    /**
     * @Route("/productos/modifica", name="modifica")
     */
    public function modificaProducto()
    {
        return $this->render('productos/modificaProducto.html.twig', [
            'titulo' => 'Modificación de producto',
        ]);
    }

    /**
     * @Route("/productos/baja", name="baja")
     */
    public function bajaProducto()
    {
        return $this->render('productos/bajaProducto.html.twig', [
            'titulo' => 'Baja de producto',
        ]);
    }
}
