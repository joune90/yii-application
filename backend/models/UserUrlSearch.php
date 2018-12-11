<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\UserUrl;

/**
 * UserUrlSearch represents the model behind the search form of `backend\models\UserUrl`.
 */
class UserUrlSearch extends UserUrl
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'pv', 'is_delete', 'create_time', 'update_time', 'expire_time', 'status'], 'integer'],
            [['url_location','url_location_pc', 'url_resource', 'url_short'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UserUrl::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'pv' => $this->pv,
            'is_delete' => $this->is_delete,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'expire_time' => $this->expire_time,
            'status' => $this->status,
        ]);
        if(Yii::$app->user->getId() != 1){
            $query->andWhere(['user_id'=>Yii::$app->user->getId()]);
        }

        $query->andFilterWhere(['like', 'url_location', $this->url_location])
            ->andFilterWhere(['like', 'url_resource', $this->url_resource])
            ->andFilterWhere(['like', 'url_short', $this->url_short]);

        return $dataProvider;
    }
}
