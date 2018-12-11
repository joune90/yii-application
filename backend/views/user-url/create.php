<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UserUrl */

$this->title = '新增推广链接';
$this->params['breadcrumbs'][] = ['label' => 'User Urls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-url-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
