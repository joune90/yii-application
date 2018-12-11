<?php
namespace backend\controllers;

use backend\models\UserUrl;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','jump'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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
        return $this->redirect(['user-url/index']);
    }

    public function actionJump()
    {

        //获取参数
        $userUrlId = Yii::$app->request->get('id');
        //判断是否是移动端打开
        $flag = $this->isMobile();
        //获取需要跳转的资源链接
        $userUrl = UserUrl::find()->where(['id'=>$userUrlId])->asArray()->one();
        $url_img = $userUrl['url_location'];
        $url_img_pc = $userUrl['url_location_pc'];
        //设置使用次数
        UserUrl::updateAll(['pv'=>($userUrl['pv']+1),'update_time'=>time()],['id'=>$userUrlId]);
        return $this->renderPartial('jump',['url_img'=>$url_img,'url_img_pc'=>$url_img_pc,'flag'=>$flag]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function isMobile(){

            // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
            if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
                return true;
            }
            // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
            if (isset($_SERVER['HTTP_VIA'])) {
                // 找不到为flase,否则为true
                return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
            }
            // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger');
                // 从HTTP_USER_AGENT中查找手机浏览器的关键字
                if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                    return true;
                }
            }
            // 协议法，因为有可能不准确，放到最后判断
            if (isset ($_SERVER['HTTP_ACCEPT'])) {
                // 如果只支持wml并且不支持html那一定是移动设备
                // 如果支持wml和html但是wml在html之前则是移动设备
                if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                    return true;
                }
            }
            return false;
        }

}
