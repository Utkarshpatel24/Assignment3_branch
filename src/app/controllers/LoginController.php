<?php
//session_start();

use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    public function indexAction()
    {
        //return '<h1>Hello!!!</h1>';
       
    }

    public function registerAction()
    {
        $postData=$this->request->getPost();
        $email=$postData['email'];
        $password=$postData['password'];
        //echo $password;
        // $user=new Users();
        //   $result=Users::find();
       // $this->view->users=Users::find();
       // $this->view->users=Users::find_by_email_and_password($email, $password);
        // // $result=$user::find_by_email_and_password($email, $password);
        // $result = Users::find([
        //     'email' => $email,
        //     'password' => $password
        // ]);
        // echo "<pre>";
        // print_r($result);
        // echo "</pre>";
        // echo $result->email;
        // echo "hello";
        // $this->view->data="good boy";
        //$this->view->users=Users::find("email = '".$email."'");
        // $result=Users::find("email = '".$email."'");
        // print_r($result);
        // $this->view->users=Users::find([
        //     "email = '".$email."'",
        //     "password = '".$password."'"
        // ] );
        // echo "ggggggg";
        // die();
        $users = Users::find(
            [
                'conditions' => 'email = ?1 AND password =?2 AND status =?3',
                'bind'       => [
                    1 => $email,
                    2 => $password,
                    3 => 'approved'
                ]
            ]
        );
        
           
        // $this->view->users=$users;
       
        if (count($users) == 1) {
            $_SESSION['mydetails']=$this->getArray($users[0]);
            header("location:/dashboard");

        } else {
           
            $this->view->data="Not Registered or Approved";
        }
        

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
}