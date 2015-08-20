<?php

namespace frontend\models;

use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;

class TagQuery extends ActiveQuery {

    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }

}
