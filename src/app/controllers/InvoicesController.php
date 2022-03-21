<?php

//use MyApp\Models\Invoices;
// use Phalcon\Http\Request;
use Phalcon\Mvc\Controller;
// use Phalcon\Mvc\View;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

/**
 * @property Request $request
 * @property View    $view
 */
class InvoicesController extends Controller
{
    // public function indexAction()
    // {
    //             $numberPage = $this->request->getQuery('page', 'int', 1);
    //     $criteria   = Criteria::fromInput($this->di, Robots::class, $_GET);
    //     $parameters = $criteria->getParams();

    //     $builder    = $this->modelsManager->createBuilder()->from(Robots::class);

    //     $paginator = new QueryBuilder(
    //         [
    //             'builder'    => $builder,
    //             'parameters' => $parameters,
    //             'limit'      => 10,
    //             'page'       => $numberPage,
    //         ]
    //     );
    //     $paginate  = $paginator->paginate();
    //     //Optional check $paginate->getTotalItems() and flash if nothing was found.

    //     $this->view->page = $paginate;
    // }
    public function listAction()
    {
        
         $currentPage = $this->request->getQuery('page', 'int', 1);
       // $currentPage=1;
        $paginator   = new PaginatorModel(
            [
                'model'  => Users::class,
                'limit' => 1,
                'page'  => $currentPage,
            ]
        );
        
        $page = $paginator->paginate();
        echo "<pre>";
        print_r($page);
        echo "</pre>";
        $result=$page->getItems();
        print_r($result);
        echo $result[0]['name'];
         $this->view->setVar('page', $page);
        //$this->view->data=$page;
        echo "Pagenation";
    }
}