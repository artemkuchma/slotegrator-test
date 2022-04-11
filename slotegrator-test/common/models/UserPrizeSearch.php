<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserPrize;

/**
 * UserPrizeSearch represents the model behind the search form of `common\models\UserPrize`.
 */
class UserPrizeSearch extends UserPrize
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'ptid', 'bonus', 'many', 'item_id', 'status'], 'integer'],
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
        $query = UserPrize::find();
        //если НЕ админ то добавить фильтрацию по uid
        if(!User::isAdminById(\Yii::$app->user->id)){
            $query->where(['uid'=> \Yii::$app->user->id]);
        }


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['statusD']);
        $query->joinWith(['item']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ptid' => $this->ptid,
            'bonus' => $this->bonus,
            'many' => $this->many,
            'item_id' => $this->item_id,
            'status' => $this->status,

        ]);


        return $dataProvider;
    }
}
