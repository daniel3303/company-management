<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-20
 * Time: 16:26
 */

namespace App\Controller\Backend;


use App\Doctrine\Filter\Filter;
use App\Doctrine\Filter\FilterCollection;
use App\Repository\BaseRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends AbstractController {
    const DEFAULT_PER_PAGE = 10;

    protected function paginate(Query $query, Request $request, string $name = ""): Pagerfanta {
        $pager = new Pagerfanta(new DoctrineORMAdapter($query));
        $pager->setAllowOutOfRangePages(true);
        $pager->setCurrentPage($request->query->getInt($name . "page", 1));
        $pager->setMaxPerPage($request->getSession()->get($name . "per-page") ?? static::DEFAULT_PER_PAGE);

        return $pager;
    }

    protected function paginateWithSorting(BaseRepository $repository, Request $request, string $name = ""): Pagerfanta {
        $orderProperty = $request->get("sort-by");
        $order = $request->get("sort-order");

        $filterCollection = new FilterCollection();

        try {
            $filters = $request->query->get($name . "filter", []);

            if (is_array($filters)) {
                foreach ($filters as $filter) {
                    if (!isset($filter["path"]) || !isset($filter["comparator"]) || !isset($filter["value"]) || $filter["value"] == "") {
                        continue; //ignore filter
                    }

                    $filter = new Filter($filter["path"], $filter["comparator"], $filter["value"]);
                    $filterCollection->addFilter($filter);
                }
            }
        } catch (\Exception $exp) {
            // Ignore filters
            echo $exp->getMessage();
        }


        // the query
        $query = $repository->findAllWithPaginator($orderProperty, $order, null, $filterCollection)->getQuery();

        return $this->paginate($query, $request);
    }
}