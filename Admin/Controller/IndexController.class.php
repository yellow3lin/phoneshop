<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    function __construct()
    {
        parent::__construct();
        if (!acc('admin')){
            $this->redirect('admin/login');
        }
    }
    public function index(){
        $this->display();
    }
}