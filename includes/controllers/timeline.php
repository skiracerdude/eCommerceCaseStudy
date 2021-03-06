<?php

/**
 * Created by PhpStorm.
 * User: Evan
 * Date: 11/20/2015
 * Time: 1:12 PM
 */
class timeline extends postsContainer
{
    /**
     * timeline constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkMember();
    }

    /**
     * set up the time line of current user
     */
    public function index()
    {
        $this->init(Session::get('my_user')['id']);
        $this->view->title = 'Your Timeline';
        $this->view->render('timeline/index');
    }
}
