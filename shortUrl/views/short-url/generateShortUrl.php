<?php
use yii\bootstrap\ActiveForm;
use app\models\widgets\AjaxSubmitButton;
use app\models\widgets\copyTextButton\CopyTextButton;

$form = ActiveForm::begin([
    'id' => 'url-generate-form',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]); ?>

<?= $form->field($model, 'url')->textInput(['autofocus' => false]) ?>


    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?php
            AjaxSubmitButton::begin([
                'label' => 'Укоротить',
                'ajaxOptions' =>
                    [
                        'type' => 'POST',
                        'cache' => false,
                        'beforeSend' => new \yii\web\JsExpression('function( xhr ) {
                                return true;
                            }'),
                        'success' => new \yii\web\JsExpression('function(html){
                                if (html["status"] =="error"){
                                var messages=html["messages"]
                                    jQuery.each(messages, function(i, val) {
                                        $("div.field-"+i).addClass("has-error");
                                        $("div.field-"+i).removeClass("has-success");
                                        $("div.field-"+i).find("p.help-block").html(messages[i][0]);
                                    });
                                }else{
                                        $("#resultContent").html(html);
                                        $("#CopyTextButton").show();
                                }
                            }'),
                    ],
                'options' => ['type' => 'submit', 'class' => 'btn btn-primary'],
            ]);
            AjaxSubmitButton::end();
            ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>

    <div id="resultContent" class="col-lg-6"></div>

<?= CopyTextButton::widget([
    'id'=>'CopyTextButton',
    'label' => '<span class="glyphicon glyphicon-copy"></span> ' . \Yii::t('app', 'Copy'),
    'encodeLabel' => false,
    'options' => [
        'style' => 'display:none',
    ],
    'text' => "$('#resultContent input').val()",
]) ?>