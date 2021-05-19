<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\{bootstrap\ActiveForm, helpers\Html};

$this->title = 'Login';
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $form = ActiveForm::begin(['id' => 'login-form']);

        echo $form->field($model, 'username')
            ->textInput(['autofocus' => true]),

            $form->field($model, 'password')->passwordInput(),

            $form->field($model, 'rememberMe')->checkbox()
    ?>
        <div class="form-group">
            <?= Html::submitButton(
                'Login',
                [
                    'class' => 'btn btn-primary',
                    'name' => 'login-button',
                ]
            ) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
