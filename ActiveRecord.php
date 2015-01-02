<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\smart;

use \yii\db\ActiveRecord as BaseActiveRecord;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;

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

    /**
     * Performs ajax validation if needed
     * @return array
     */
    public function performAjaxValidation()
    {
        if (\Yii::$app->request->isAjax && $this->load($_POST)) {
            \Yii::$app->response->format = 'json';
            return ActiveForm::validate($this);
        }
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     */
    public function trySave($runValidation = true, $attributeNames = null)
    {
        if (!$this->save($runValidation, $attributeNames))
            throw new \LogicException;
        return true;
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws \Exception
     */
    public function tryInsert($runValidation = true, $attributeNames = null)
    {
        if (!$this->insert($runValidation, $attributeNames))
            throw new \LogicException;
        return true;
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws \Exception
     */
    public function tryUpdate($runValidation = true, $attributeNames = null)
    {
        if ($this->update($runValidation, $attributeNames) === false)
            throw new \LogicException;
        return true;
    }
}