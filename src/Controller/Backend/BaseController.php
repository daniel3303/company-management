<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-20
 * Time: 16:26
 */

namespace App\Controller\Backend;


use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends AbstractController {
    public function configurePaginator(PaginationInterface $paginator, Request $request, string $name = "paginator") : PaginationInterface{
        $paginator->setCurrentPageNumber($request->query->get($name."-page"));
        $paginator->setItemNumberPerPage(10);
        return $paginator;
    }
}