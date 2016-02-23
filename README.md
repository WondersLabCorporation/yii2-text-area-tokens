Text Area Tokens - Yii2 extension
====================================
This is a fork of https://github.com/mmedojevicbg/yii2-text-area-tokens with additional tokens configuration:
- Symbol of token start and token end.
- New format of a token element "token" => "Label"
- Ability to add class and other attributes to token element

This is drop-in replacement for textarea form element. It provides tokens below form element. Text is automatically inserted
into textarea by clicking one of tokens.

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Add to your `composer.json` file

```json
"repositories": [
        {
            "url": "https://github.com/WondersLabCorporation/yii2-text-area-tokens.git",
            "type": "git"
        }
    ]
```
and run

```
composer require WondersLabCorporation/yii2-text-area-tokens:"dev-master"
```


Usage
------------

```php
echo TextAreaTokens::widget(['model' => $model,
                             'attribute' => 'textfield1',
                             'tokens' => ['first_name'=>'First Name', 'last_name' => 'Last Name'],
                             'tokenStart' => '[',
                             'tokenEnd' => ']',
                             'tokenOptions' => [
                                'class'=> [
                                     'myclass'
                                ]
                             ],
                             'options' => ['rows' => 8, 'cols' => 100]])
```