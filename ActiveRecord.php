<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\smart;

use \yii\db\ActiveRecord as BaseActiveRecord;
use yii\web\NotFoundHttpException;

class ActiveRecord extends BaseActiveRecord
{
    /**
     * Tries to load model and throws an exception on missing model
     *
     * @param integer $id
     * @return \yii\db\ActiveRecord
     * @throws \yii\web\NotFoundHttpException
     */
    public static function loadModel($id)
    {
        $className = static::className();
        $model = $className::findOne($id);
        if (!$model instanceof \yii\db\ActiveRecord)
            throw new NotFoundHttpException(404);
        return $model;
    }

    public function tryInsert($runValidation = true, $attributes = null)
    {
        if (!$this->insert($runValidation, $attributes))
            throw new \LogicException;
        return true;
    }
}