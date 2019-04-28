<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-20
 * Time: 16:26
 */

namespace App\Controller\Backend;


use App\Repository\BaseRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends AbstractController {
    protected function paginate(Query $query, Request $request, string $name = "") : Pagerfanta{
        $pager = new Pagerfanta(new DoctrineORMAdapter($query));
        $pager->setAllowOutOfRangePages(true);
        $pager->setCurrentPage($request->query->getInt($name."page", 1));
        $pager->setMaxPerPage($request->query->getInt($name."per-page", 50));

        return $pager;
    }

    protected function paginateWithSorting(BaseRepository $repository, Request $request, string $name = "") : Pagerfanta{
        $orderProperty = $request->get("sort-by");
        $order = $request->get("sort-order");
        $query = $repository->findAllWithPaginator($orderProperty, $order)->getQuery();

        return $this->paginate($query, $request);
    }
}