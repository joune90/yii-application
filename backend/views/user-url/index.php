<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserUrlSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '链接列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-url-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增推广链接', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                    'label'     => '落地页链接移动端',
                    'attribute' =>  'url_location',
            ],
            [
                'label'     => '落地页链接PC端',
                'attribute' =>  'url_location_pc',
            ],
            [
                    'label'     => '生成的短链接',
                'attribute' =>  'url_short',
            ],

            //'pv',
            //'is_delete',
            //'create_time:datetime',
            //'update_time:datetime',
            //'expire_time:datetime',
            //'status',

            ['class' => 'yii\grid\ActionColumn','template'=>'{delete}'],
        ],
    ]); ?>
</div>
