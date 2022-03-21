<?php
//session_start();
use Phalcon\Mvc\Controller;
// use Phalcon\Dispatcher;
// use Phalcon\Flash\Direct;

class DashboardController extends Controller
{
    public function indexAction($data = [])
    {
        //if(count($data) != 0)
        if (count($data) == 0) {
            $disp=" <tr>
            <td> ".$_SESSION['mydetails']['user_id']."</td>
            <td> ".$_SESSION['mydetails']['name']."</td>
            <td> ".$_SESSION['mydetails']['username']."</td>
            <td> ".$_SESSION['mydetails']['email']."</td>
            <td> ".$_SESSION['mydetails']['password']."</td>
            <td> ".$_SESSION['mydetails']['role']."</td>
            <td> ".$_SESSION['mydetails']['status']."</td>
            <td><button>edit</button></td>
          </tr>";
            array_push($data, $disp);
            array_push($data, 'edit');
        }
        $this->view->data = $data;
       
    }
    public function editAction()
    {
        $disp="<tr>
        
        <td>".$_SESSION['mydetails']['user_id']."</td>
        <td><input placeholder='".$_SESSION['mydetails']['name']."' name='name'></td>
        <td><input placeholder='".$_SESSION['mydetails']['username']."' name='username'></td>
        <td><input placeholder='".$_SESSION['mydetails']['email']."' name='email'></td>
        <td><input placeholder='".$_SESSION['mydetails']['password']."' name='password'></td>
        <td><input placeholder='".$_SESSION['mydetails']['role']."' name='role'></td>
        <td><input placeholder='".$_SESSION['mydetails']['status']."' name='status'></td>
        <td><button type='submit'>Update</button></td>
        </tr>";
        $data=array();
        array_push($data, $disp);
        array_push($data, 'update');
        // $this->indexAction($data);
        //$this->view('dashboard/index');
        // $this->test();
        //return $this->indexAction($data);
       // return \DashboardController::call('index');
            // $this->flash->error(
            //     "You do not have permission to access this area"
            // );

            // Forward flow to another action
            $this->dispatcher->forward(
                [
                    'controller' => 'dashboard',
                    'action'     => 'index',
                    'params'   => array($data)
                    
                ]
            );


    }

    public function updateAction()
    {
        $postData=$this->request->getPost();
        $user=Users::findFirst($_SESSION['mydetails']['user_id']);
        // echo $user->name;
        $user->name=$postData['name'];
        $user->username=$postData['username'];
        $user->email=$postData['email'];
        $user->password=$postData['password'];
        $user->role=$postData['role'];
        $user->status=$postData['status'];
        $_SESSION['mydetails']['name']=$postData['name'];
        $_SESSION['mydetails']['username']=$postData['username'];
        $_SESSION['mydetails']['email']=$postData['email'];
        $_SESSION['mydetails']['password']=$postData['password'];
        $_SESSION['mydetails']['role']=$postData['role'];
        $_SESSION['mydetails']['status']=$postData['status'];
        $user->update();
        $this->dispatcher->forward(
            [
                'controller' => 'dashboard',
                'action'     => 'index'
                
                
            ]
        );
        //print_r($postData);
    }


    public function readersAction()
    {
        $userlist=Users::find();
        $userlist2=array();
        foreach($userlist as $key => $val)
        {
            array_push($userlist2, $this->getArray($val));
        }
        print_r($userlist2);
        $disp="";
        foreach($userlist2 as $key=>$val)
        {
            $act='app';
            $act1='approve';
            if ($val['status'] == 'approved') {
                $act='dis';
                $act1='dissapprove';
            }
            if ($val['role'] != 'admin') {
                $disp.="<tr>
               <td>".$val['user_id']."</td>
               <td>".$val['name']."</td>
               <td>".$val['username']."</td>
               <td>".$val['email']."</td>
               <td>".$val['role']."</td>
               <td>".$val['status']."</td>
               <td><button name='action' value='".$act."-".$val['user_id']."'>".$act1."</button></td>
               <td><button name='action' value='del-".$val['user_id']."'>Delete</button></td>
            </tr>";
            }
            
        }
        $data=array();
        array_push($data, $disp);
        $this->view->data=$data;
    }


    public function getArray($user)
    {
        return array(
            'user_id'=>$user->user_id,
            'name'=>$user->name,
            'username'=>$user->username,
            'email'=>$user->email,
            'password'=>$user->password,
            'role'=>$user->role,
            'status'=>$user->status

        );
    }

    public function changestatusAction()
    {
        $postData=$this->request->getPost();
        $action=$postData['action'];
        $user_id=substr($action, 4);
        $action=substr($action, 0, 3);
        // echo $action;
        // echo $user_id;
        // die();
        $user=Users::findFirst($user_id);
        switch($action)
        {
            case 'app':
                {
                    $user->status='approved';
                    $user->update();
                    break;
                }
            case 'dis':
                {
                    $user->status='dissapproved';
                    $user->update();
                    break;
                }    
            case 'del':
                {
                    $user->delete();
                    break;
                }
        }
        
        $this->dispatcher->forward(
            [
                'controller' => 'dashboard',
                'action'     => 'readers'
                
                
            ]
        );

    }


    public function addblogAction()
    {

    }

    public function bloglistAction()
    {
        $blog=Blogs::find();
        $disp="";
        foreach($blog as $key=>$val)
        {
            $disp.="<tr>
            <td>".$val->blog_id."</td>
            <td>".$val->user_id."</td>
            <td>".$val->title."</td>
            <td>".$val->category."</td>
            <td>".$val->time."</td>
            <td><button name='action' value='edit-".$val->blog_id."'>Edit</button></td>
            <td><button name='action' value='dele-".$val->blog_id."'>Delete</button></td>
            </tr>";
        }
        $data=array();
        array_push($data, $disp);
        $this->view->data=$data;

    }

    public function editblogAction($b_id)
    {
        // echo $b_id;
        $blog=Blogs::findFirst($b_id);
        $data=array();
        array_push($data, $blog->title);
        array_push($data, $blog->category);
        array_push($data, $blog->blog_content);
        array_push($data, $b_id);
        $this->view->data=$data;
    
    }


   

    public function test()
    {
        echo "gooddddd";
    }
}