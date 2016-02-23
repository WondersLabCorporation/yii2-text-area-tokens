<?php
namespace WondersLabCorporation;

use yii\helpers\Html;
use yii\widgets\InputWidget;
use yii\helpers\ArrayHelper;

/**
 * Class TextAreaTokens
 * Total refactoring of mmedojevicbg\TextAreaTokens
 * @package WondersLab
 */
class TextAreaTokens extends InputWidget
{
    /**
     * @var TextAreaTokensAsset
     */
    public $asset = 'TextAreaTokensAsset';
    /**
     * @var string field name whether model attribute or custom name
     */
    protected $fieldName;
    /**
     * @var array List of tokens as ['token' => 'label', ...]
     */
    public $tokens = [];
    /**
     * @var string Token label prefix
     */
    public $tokenStart = '[';
    /**
     * @var string Token label suffix
     */
    public $tokenEnd = ']';
    /**
     * @var array Token item element HTML attributes like ['class' => 'some-class', ...]
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $tokenOptions = [];
    /**
     * @var array the HTML attributes for the container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $containerOptions = [];
    /**
     * true to render default suffix with plus icon,
     * string to render as it is,
     * Closure to generate suffix based on token
     * @var bool|string|\Closure
     */
    public $tokenSuffix = true;
    /**
     * @var string|false Tokens list title or false to remove it
     */
    public $tokensTitle = 'Available tokens';

    /**
     * Init fieldName with provided attribute or name, generate textarea and token container IDs
     *
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->hasModel()) {
            $this->fieldName = $this->attribute;
        } else {
            $this->fieldName = $this->name;
        }
        $this->initTextAreaId();
        $this->initTokenContainerId();
    }

    /**
     * Render widget to page
     */
    public function run()
    {
        $containerOptions = ArrayHelper::merge($this->containerOptions, [
            'class' => 'available-tokens',
        ]);
        echo  Html::beginTag('div', $containerOptions);
            if ($this->hasModel()) {
                echo Html::activeTextarea($this->model, $this->attribute, $this->options);
            } else {
                echo Html::textarea($this->name, $this->value, $this->options);
            }
            echo $this->renderTokens();
        echo Html::endTag('div');
        $this->registerClientScript();
    }

    /**
     * Render tokens container
     */
    public function renderTokens()
    {
        $result = '';
        if ($this->tokensTitle) {
            $result .= Html::beginTag('div');
            $result .= $this->tokensTitle;
            $result .= Html::endTag('div');
        }
        $this->tokenOptions = ArrayHelper::merge($this->containerOptions, [
            'class' => 'token',
        ]);
        foreach ($this->tokens as $token => $label) {
            $result .= $this->renderToken($token, $label);
        }
        return $result;
    }

    /**
     * Render single token item
     * @param $token
     * @param $label
     * @return string Single token HTML code
     */
    protected function renderToken($token, $label)
    {
        $result = '';
        $tokenOptions = ArrayHelper::merge($this->tokenOptions, [
            'data' => [
                'token' => $this->tokenStart . $token . $this->tokenEnd
            ]
        ]);
        $result .= Html::beginTag('span', $tokenOptions);
        $result .= $label;
        if ($this->tokenSuffix) {
            $result .= $this->renderTokenSuffix($token);
        }
        $result .= Html::endTag('span');
        return $result;
    }

    /**
     * @param $token
     * @return string
     */
    protected function renderTokenSuffix($token)
    {
        $result = '';
        if ($this->tokenSuffix === true) {
            $result .= Html::beginTag('i', ['class' => 'glyphicon glyphicon-plus add-tag']);
            $result .= Html::endTag('i');
        } elseif ($this->tokenSuffix instanceof \Closure) {
            $result .= call_user_func($this->tokenSuffix, $token);
        } elseif (is_string($this->tokenSuffix)) {
            $result .= $this->tokenSuffix;
        }
        return $result;
    }

    /**
     * Register client script to insert tokens
     */
    protected function registerClientScript()
    {
        $view = $this->getView();
        $assetClass = $this->asset;
        call_user_func([$assetClass, 'register'], $view);
        $js = <<<EOT
        $('#{$this->containerOptions['id']} .token').click(function(){
            var token = $(this).data('token');
            insertAtCaret('{$this->options['id']}', token);
        });
EOT;
        $view->registerJs($js);
    }

    /**
     * Generate Text area ID
     * @return string
     */
    protected function initTextAreaId()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = 'text-area-tokens-' . $this->fieldName;
        }
    }

    /**
     * Generate Token container ID
     * @return string
     */
    protected function initTokenContainerId()
    {
        if (!isset($this->containerOptions['id'])) {
            $this->containerOptions['id'] = 'available-tokens-' . $this->fieldName;
        }
    }
}