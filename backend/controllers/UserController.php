<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{

    public $enableCsrfValidation=false;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['createajax'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['create', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];

    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->getId() == 1){
            $dataProvider = new ActiveDataProvider([
                'query' => User::find(),
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        }else{
            return $this->redirect('site/error');
        }

    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->getId() != 1){
            $this->redirect(['site/error']);
        }
        $model = new User();
        if(Yii::$app->request->post('User')){
            $model->username = Yii::$app->request->post('User')['username'];
            $model->password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('User')['password_hash']);
            $model->num = Yii::$app->request->post('User')['num'];
            $model->auth_key = '';
            $model->email = '';
            $model->created_at = time();
            $model->updated_at = time();
            $model->expire_time = time()+60*60*24*30;
            $model->save();
//            return $this->redirect(['index', 'id' => $model->id]);
            return $this->redirect(['user/index']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionCreateajax()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        $phone = Yii::$app->request->post('phone');

        if($username == '' || $password == '' || $phone == ''){
            echo 'e';
        }

        //验证用户名是否存在
        $user = User::find()->where('username=:username',[':username'=>$username])->asArray()->one();
        if($user){
            echo 're';exit;
        }
        $model = new User();
        $model->username = $username;
        $model->password_hash = Yii::$app->security->generatePasswordHash($password);
        $model->num = 1;
        $model->auth_key = '';
        $model->email = $phone;
        $model->created_at = time();
        $model->updated_at = time();
        $model->expire_time = time()+60*30;
        $flag = $model->save();
        if($flag){
            echo 'ok';exit;
        }else{
            echo 'e';exit;
        }

    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
