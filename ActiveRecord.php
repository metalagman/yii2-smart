<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\smart;

use \yii\db\ActiveRecord as BaseActiveRecord;
use yii\helpers\ArrayHelper;
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
        $model = static::findOne($id);
        if (!$model instanceof BaseActiveRecord)
            throw new NotFoundHttpException();
        return $model;
    }

    /**
     * Performs ajax validation if needed
     *
     * @param array $data
     * @return array|false
     */
    public function performAjaxValidation($data = [])
    {
        if (\Yii::$app->request->isAjax && $this->load($data)) {
            \Yii::$app->response->format = 'json';
            return ActiveForm::validate($this);
        }

        return false;
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

    /**
     * Returns list of items suitable for use in html lists
     *
     * @param string $textField
     * @param string $valueField
     * @return array
     */
    public static function listData($textField, $valueField = 'id')
    {
        return ArrayHelper::map(static::find()->asArray()->all(), $valueField, $textField);
    }
}