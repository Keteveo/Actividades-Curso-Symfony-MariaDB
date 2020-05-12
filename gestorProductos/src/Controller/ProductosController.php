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
        return $this->render('productos/index.html.twig', [
            'controller_name' => 'ProductosController/altaProducto',
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
        return $this->render('productos/index.html.twig', [
            'controller_name' => 'ProductosController/modificaProducto',
        ]);
    }

    /**
     * @Route("/productos/baja", name="baja")
     */
    public function bajaProducto()
    {
        return $this->render('productos/index.html.twig', [
            'controller_name' => 'ProductosController/bajaProducto',
        ]);
    }
}