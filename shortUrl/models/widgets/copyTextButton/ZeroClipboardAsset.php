<?php

namespace app\models\widgets\copyTextButton;

use yii\web\AssetBundle;

/**
 * Class ZeroClipboardAsset
 * Asset bundle for the zeroclipboard javascript library.
 *
 * @package chornij\zeroclipboard
 */
class ZeroClipboardAsset extends AssetBundle
{

    /**
     * @var string Source asset directory
     */
    public $sourcePath = '@app/models/widgets/copyTextButton';

    /**
     * @var array JavaScript files
     */
    public $js = ['dist/ZeroClipboard.min.js'];
}
