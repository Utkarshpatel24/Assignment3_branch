<?php

use Phalcon\Mvc\Model;

class Blogs extends Model
{
    public $id;
    public $user_id;
    public $title;
    public $category;
    public $blog_content;
    public $time;
    
}