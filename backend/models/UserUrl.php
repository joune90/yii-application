<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user_url".
 *
 * @property int $id
 * @property int $user_id
 * @property string $url_location
 * @property string $url_resource
 * @property string $url_short
 * @property int $pv
 * @property int $is_delete
 * @property int $create_time
 * @property int $update_time
 * @property int $expire_time
 * @property int $status
 */
class UserUrl extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_url';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'url_location','url_location_pc', 'pv', 'is_delete', 'create_time', 'update_time', 'expire_time', 'status'], 'required'],
            [['user_id', 'pv', 'is_delete', 'create_time', 'update_time', 'expire_time', 'status'], 'integer'],
            [['url_location', 'url_resource', 'url_short','url_location_pc'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'url_location' => 'Url Location',
            'url_resource' => 'Url Resource',
            'url_location_pc' => 'Url Resource Pc',
            'url_short' => 'Url Short',
            'pv' => 'Pv',
            'is_delete' => 'Is Delete',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'expire_time' => 'Expire Time',
            'status' => 'Status',
        ];
    }
}
