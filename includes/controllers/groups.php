<?php

/**
 * Created by PhpStorm.
 * User: Evan
 * Date: 11/21/2015
 * Time: 7:57 PM
 * @property _Groups model
 */
class groups extends Controller
{

    public function __construct()
    {
        parent::__construct();
        self::checkMember();
    }

    public function makeAdmin()
    {

        if (isset($_POST['admin_id']) && isset($_GET['g'])) {
            $this->model->makeAdmin();
            header("Location: ../groups/group?g=" . $_GET['g']);
        }

    }

    public function update()
    {
        $this->model->init($_POST['g']);
        $this->view->name = $this->model->getName();
        $this->view->description = $this->model->getDescription();
        $this->view->privacy = $this->model->getPrivacy();

        //SETUP AND INIT BASIC WALL
        if (isset($_POST['privacy']) && isset($_POST['description'])) {

            //make update
            $this->model->updateGroup();
            header("Location: ../groups/group?g=" . $_POST['g']);
        }
        if (isset($_POST['g']) && isset($_POST['member_id'])) {


            $this->view->title = 'Update Group';
            $this->view->render('groups/update');

        }

    }

    public function kick()
    {

        if (isset($_POST['member_id']) && isset($_GET['g'])) {
            $this->model->kick();
            header("Location: ../groups/group?g=" . $_GET['g']);
        }

    }

    public function delete()
    {

        if (isset($_POST['delete_group']) && isset($_GET['g'])) {
            $this->model->delete();
            header("Location: ../groups");
        }

    }

    public function leave()
    {

        if (isset($_POST['leave_id']) && isset($_GET['g'])) {
            $this->model->leave();
            header("Location: ../groups");
        }

    }


    public function join()
    {

        if (isset($_POST['user_id']) && isset($_GET['g'])) {
            $this->model->join();
            header("Location: ../groups/group?g=" . $_GET['g']);
        }

    }

    public function removeAdmin()
    {

        if (isset($_POST['admin_id']) && isset($_GET['g'])) {
            $this->model->removeAdmin();
            header("Location: ../groups/group?g=" . $_GET['g']);
        }

    }

    public function index()
    {/*
        if (isset($_GET['g'])) {
            $gid = $_GET['g'];*/
        $uid = Session::get('my_user')['id'];

        //SETUP AND INIT BASIC WALL

        // $this->loadModel('Wall');
        //$this->model->init($uid);

        $st = $this->model->getGroups($uid);
        //GET list of groups
        if (!empty($st)) {

            foreach ($st as $a_post) {
                $this->view->groups[] = $a_post;
            }

        }

        //FINALLY RENDER THE PAGE HTML
        $this->view->title = 'Your Groups';
        $this->view->render('groups/index');
        //}

    }


    public function create()
    {
        $uid = Session::get('my_user')['id'];

        //SETUP AND INIT BASIC WALL
        if (isset($_POST['name']) && isset($_POST['privacy']) && isset($_POST['description'])) {

            $validName = $this->model->validateName();
            if (!$validName) {
                $this->view->alerts[] = ['Group name already taken', 'warning'];
                $this->view->title = 'Create Groups';
                $this->view->render('groups/create');
            } else {
                $this->model->createGroup($_POST['name'], $_POST['description'], $_POST['privacy'], $uid);
                header("Location: ../groups");
            }

        } else {
            $st = $this->model->getGroups($uid);
            //GET list of groups
            if (!empty($st)) {

                foreach ($st as $a_post) {
                    $this->view->groups[] = $a_post;
                }

                $this->view->validName = $this->model->validateName();
            }

            //FINALLY RENDER THE PAGE HTML
            $this->view->title = 'Create Groups';
            $this->view->render('groups/create');
            //}
        }

    }

    public function group()
    {
        if (isset($_GET['g'])) {
            $gid = $_GET['g'];

            //SETUP AND INIT BASIC WALL for group

            $this->model->init($gid);
            $this->view->name = $this->model->getName();
            $this->view->description = $this->model->getDescription();
            $this->view->members = $this->model->getMembers();
            //GET POSTS FROM MODEL
            if (!empty($this->model->getPosts())) {
                foreach ($this->model->getPosts() as $a_post) {
                    $this->view->posts[] = $this->getModel('Group_Post', $a_post['group_post_id']);
                }
            }
        } else {
            if (Session::get('my_user'))
                header("Location: ../groups");
            else
                header("Location: ../home");
        }
        //FINALLY RENDER THE PAGE HTML
        $this->view->title = $this->model->getName();
        $this->view->render('groups/group');

    }
}
