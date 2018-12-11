<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserUrl */

$this->title = 'Update User Url: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Urls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-url-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
