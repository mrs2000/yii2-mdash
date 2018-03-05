yii2-mdash
=================
E. Muravjov's typographer for Yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist mrssoft/yii2-mdash "*"
```

or add

```
"mrssoft/yii2-mdash": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Configuration:

```php
'components' => [
	...
	'mdash' => [
      'class' => 'mrssoft\mdash\Mdash',
      'remote' => true, // Use remote API or local. Default false.
      'options' => [ // Typograph options. See http://mdash.ru/rules.html
        'Text.paragraphs' => 'off',
        'Text.breakline' => 'off',
        'OptAlign.all' => 'off',
        'Etc.unicode_convert' => 'off',
        'Nobr.spaces_nobr_in_surname_abbr' => 'off',
        'Etc.split_number_to_triads' => 'off'
      ]
   ]
   ....
]
```

Usage:

```php
$mdash = new Mdash([
  'remote' => false,
  'options' => [
    'Text.paragraphs' => 'off',
  ]
]);

echo $mdash->process("мой текст");
```

Usage as filter:

```php
public function rules()
{
    return [
    	['text', 
        	'\mrssoft\mdash\MdashFilter', 
        	'options' => ['remove' => false]
        ],
    ]
}
```

