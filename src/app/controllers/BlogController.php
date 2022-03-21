<?php

use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;


class BlogController extends Controller
{
    public function indexAction()
    {
        // $blog=Blogs::find();
        // $disp="";
        // foreach($blog as $key => $val)
        // {
        //     $disp.=' <div class="col">
        //     <div class="card shadow-sm">
              
        //       <img  src="https://nscdn.nstec.com/does-suddenlink-routers-allow-use-of-vpn-.jpg" width="100%" height="225">
        //       <div class="card-body">
        //           <h5>'.$val->title.'</h5>
        //         <p class="card-text">'.$val->category.'</p>
        //         <div class="d-flex justify-content-between align-items-center">
                  
        //           <button class="btn btn-primary" name="b_id" value="'.$val->blog_id.'">Read Blog</button>
        //         </div>
        //       </div>
        //     </div>
        //   </div>';
        // }
        // $data=array();
        // array_push($data, $disp);
        // $this->view->data=$data;
        $this->dispatcher->forward(
            [
                'controller' => 'blog',
                'action'     => 'list'
                
                
            ]
        );

    }
    public function addBlogAction()
    {
        $postdata=$this->request->getPost();
        print_r($postdata);
        $blog= new Blogs();
        // $blog->user_id=$_SESSION['mydetails']['user_id'];
        // $blog->tittle=$postdata['title'];
        // $blog->category=$postdata['category'];
        // $blog->blog_content=$postdata['blogContent'];
        // $res= $blog->save();
        
        $blog->assign(
            $this->request->getPost(),
            [
                'title',
                'category',
                'blog_content'
                                          
               
            ]
        );
        $blog->user_id=$_SESSION['mydetails']['user_id'];
        $res=$blog->save();
        $this->dispatcher->forward(
            [
                'controller' => 'dashboard',
                'action'     => 'bloglist'
                
                
            ]
        );

        // print_r($blog->blog_content);
        // echo $res;
       
    }

    public function actionAction()
    {
        $postdata=$this->request->getPost();
        $action=$postdata['action'];
        $b_id=substr($action, 5);
        $action=substr($action, 0, 4);
        $blog=Blogs::findFirst($b_id);
        switch($action)
        {
        case 'edit':
            {
                $this->dispatcher->forward(
                    [
                        'controller' => 'dashboard',
                        'action'     => 'editblog',
                        'params'     => array($b_id)
                        
                        
                    ]
                );
                break;
            }
        case 'dele':
            {
                $blog->delete();
                $this->dispatcher->forward(
                    [
                        'controller' => 'dashboard',
                        'action'     => 'bloglist'
                        
                        
                    ]
                );
                break;
            }
        case 'upda':
            {
                //echo $b_id;
                $postdata=$this->request->getPost();
                //print_r($postdata);
                $blog->title=$postdata['title'];
                $blog->category=$postdata['category'];
                $blog->blog_content=$postdata['blog_content'];
                $blog->update();
                //die();
                $this->dispatcher->forward(
                    [
                        'controller' => 'dashboard',
                        'action'     => 'bloglist'
                        
                        
                    ]
                );
                break;
               // die();
            }
        }
    }

    public function singleblogAction()
    {

        $postdata=$this->request->getPost();
        $b_id=$postdata['b_id'];
        echo $b_id;
        $blog=Blogs::findFirst($b_id);
        $data=array();
        array_push($data, $blog);
        $this->view->data=$data;
        // die();

    }



    public function listAction()
    {
        
         $currentPage = $this->request->getQuery('page', 'int', 1);
       // $currentPage=1;
        $paginator   = new PaginatorModel(
            [
                'model'  => Blogs::class,
                'limit' => 2,
                'page'  => $currentPage,
            ]
        );
        
        $page = $paginator->paginate();
        // echo "<pre>";
        // print_r($page);
        // echo "</pre>";
        // $result=$page->getItems();
        // print_r($result);
        // echo $result[0]['name'];
         //$this->view->setVar('page', $page);
        //$this->view->data=$page;
        echo "Pagenation";
        $disp='';
        foreach($page->getItems() as $key => $val)
        {
            $disp.=' <div class="col">
            <div class="card shadow-sm">
              
              <img  src="https://nscdn.nstec.com/does-suddenlink-routers-allow-use-of-vpn-.jpg" width="100%" height="225">
              <div class="card-body">
                  <h5>'.$val['title'].'</h5>
                <p class="card-text">'.$val['category'].'</p>
                <div class="d-flex justify-content-between align-items-center">
                  
                  <button class="btn btn-primary" name="b_id" value="'.$val['blog_id'].'">Read Blog</button>
                </div>
              </div>
            </div>
          </div>';
        }
        $data=array();
        array_push($data, $disp);
        array_push($data, $page);
        $this->view->data=$data;

        // $this->dispatcher->forward(
        //     [
        //         'controller' => 'blog',
        //         'action'     => 'index',
        //         'params'     => array($page)
                
                
        //     ]
        // );
    }

}
