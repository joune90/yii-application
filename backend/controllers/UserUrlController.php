<?php

namespace backend\controllers;

use common\models\User;
use Yii;
use backend\models\UserUrl;
use backend\models\UserUrlSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * UserUrlController implements the CRUD actions for UserUrl model.
 */
class UserUrlController extends Controller
{
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
                'only' => ['index','create'],
                'rules' => [
                    [
                        'actions' => ['index','create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all UserUrl models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserUrlSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserUrl model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserUrl model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserUrl();

        if ($model->load(Yii::$app->request->post())) {
            //获取推广链接地址pc端，
            $urlLocationPc = Yii::$app->request->post('UserUrl')['url_location_pc'];

            //获取移动端地址
            $urlLocation = Yii::$app->request->post('UserUrl')['url_location'];

            //获取当前登录的用户
            $userId = Yii::$app->user->getId();

            //新增链接记录
            $userUrl = new UserUrl();
            $userUrl->user_id = $userId;
            $userUrl->url_location = $urlLocation;
            $userUrl->url_location_pc = $urlLocationPc == '' ? $urlLocation : $urlLocationPc;
            $userUrl->url_resource = '';
            $userUrl->url_short = '';
            $userUrl->pv = 0;
            $userUrl->is_delete = 0;
            $userUrl->create_time = time();
            $userUrl->update_time = time();
            $userUrl->expire_time = time()+24*60*60*30;
            $userUrl->status = 0;
            if($userUrl->save()){
                //当前的推广链接为：
                $url_resource = Yii::$app->params['url'].'/index.php?r=site/jump&id='.$userUrl->id;

                //当前的短链接为：
                $url_short = $this->productUrl(urlencode($url_resource));
                $userUrl->url_resource = $url_resource;
                $userUrl->url_short = $url_short;
                $userUrl->save();
                $this->redirect(['user-url/index']);
            }
        }
        $userId = Yii::$app->user->getId();
        //判断当前的用户是否有足够的链接权限
        //获取用户所有的链接数
        $num = UserUrl::find()->Where(['user_id'=>$userId])->count();
        $userNum = User::find()->select(['num'])->where(['id'=>$userId])->asArray()->one();
        if($num >= $userNum['num']){
            return $this->redirect(['user-url/index']);
        }else{
            return $this->render('create', [
                'model' => $model,
            ]);
        }


    }

    /**
     * Updates an existing UserUrl model.
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
     * Deletes an existing UserUrl model.
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
     * Finds the UserUrl model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserUrl the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserUrl::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function productUrl($url_long){

        $api = 'http://api.t.sina.com.cn/short_url/shorten.json';
        // json //$api = 'http://api.t.sina.com.cn/short_url/shorten.xml'; // xml
        $source = '2625363911';
//        $url_long = 'http://blog.csdn.net/fdipzone';
        $request_url = sprintf($api.'?source=%s&url_long=%s', $source, $url_long);
        $data = file_get_contents($request_url);
        $url = json_decode($data,true);
        return $url[0]['url_short'];
        exit;
    }
}
