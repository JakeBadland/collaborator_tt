<?php

namespace app\controllers;

use phpDocumentor\Reflection\Types\Integer;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{
    private int $block_interval = 300;


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest){
            return Yii::$app->response->redirect(['site/profile']);
        }

        return $this->render('login');
    }

    public function actionProfile()
    {
        if (Yii::$app->user->isGuest){
            return Yii::$app->response->redirect(['site/login']);
        }

        return $this->render('profile', ['login' => Yii::$app->user->id]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest){
            return Yii::$app->response->redirect(['site/profile']);
        }

        $post = Yii::$app->request->post();

        $model = new LoginForm();
        if ($model->load($post, "") && $model->login()) {
            return Yii::$app->response->redirect(['site/profile']);
        }

        $model->password = '';

        $block_time = $this->isBlocked();
        if ($block_time){
            $this->view->params['error'] = 'Попробуйте еще раз через ' . ($this->block_interval - $block_time) . ' секунд.';
        }

        if (!$block_time && $model->errors){
            $this->view->params['error'] = "Неверные данные.";
        }

        return $this->render('login', [
            'model' => $model
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    private function isBlocked()
    {
        $file_name = Yii::getAlias('@data'). '/system.json';

        if (!is_file($file_name)){
            return null;
        }

        $system = json_decode(file_get_contents($file_name), true);

        $system['filed_attempts']++;

        if ($system['filed_attempts'] >= 3){
            $system['filed_attempts'] = 0;
            $system['block_date'] = date("Y-m-d H:i:s");
            file_put_contents($file_name, json_encode($system));
            return 0;
        }

        if ($system['block_date']){
            //die('already blocked due date ...');
            $now = new \DateTime(date("Y-m-d H:i:s"));
            $block_date = new \DateTime($system['block_date']);
            $diffInSeconds = $now->getTimestamp() - $block_date->getTimestamp();

            if ($diffInSeconds < $this->block_interval){
                return $diffInSeconds;
            }

            $system['block_date'] = null;
            $system['filed_attempts'] = 0;
        }

        file_put_contents($file_name, json_encode($system));
    }

    private function filedAttempt()
    {

    }

}
