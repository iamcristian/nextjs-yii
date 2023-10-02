# nextjs+yii2

`
composer create-project --prefer-dist yiisoft/yii2-app-basic backend
`
- add database postgres
    ```
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=post',
    'username' => 'postgres',
    'password' => 'postgres',
    'charset' => 'utf8',
    ```
- create migrations for create databases
<br>`
php yii migrate/create create_post_table
`<br>
`
php yii migrate/create create_comments_table
`
- edit in migrations/*
```
<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post}}`.
 */
class m231002_002443_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(512),
            'body' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%post}}');
    }
}
```
```
<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment}}`.
 */
class m231002_002823_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(512),
            'body' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'post_id' => $this->integer()
        ]);

        $this->addForeignKey('fk_comment_post_post_id', '{{%comment}}', 'post_id', '{{%post}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_comment_post_post_id', '{{%comment}}');
        $this->dropTable('{{%comment}}');
    }
}
```
- execute `php yii migrate`
- discomment in web.php
```
'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            // 'enableStrictParsing' => true,
            'rules' => [

            ],
        ],
```
- generate models
  ```
  {
    Table Name -> post
    Model Class Name -> Post
    Namespace -> app\models
    use table prefix -> true
    generate activequery -> true
    ActiveQuery Namespace -> app\models\query
    PostQuery class -> PostQuery
  }
   {
    Table Name -> comment
    Model Class Name -> Comment
    Namespace -> app\models
    use table prefix -> true
    generate activequery -> true
    ActiveQuery Namespace -> app\models\query
    PostQuery class -> CommentQuery
  }
  ```
- create controllers
  ```
  <?php
  namespace app\controllers;
  use app\models\Post;
  use yii\rest\ActiveController;

  class PostController extends ActiveController{
    public $modelClass = Post::class;
  }
  ```
  ```
  <?php
  namespace app\controllers;
  use app\models\Comment;
  use yii\rest\ActiveController;
  
  class CommentController extends ActiveController{
    public $modelClass = Comment::class;
  }
  ```
- access in postman for post `http://localhost:8080/post/create`
  `(key->Content-Type, value->application/json)`<br>
  body
  ```
  {
    "title": "Demo post",
    "body": "lorem"
  }
  ```
- add the configs in web.php
```
  <?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'L_rXZMgmc5L5Cu735ToD_IRpMzWpU4B-',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'format' => \yii\web\Response::FORMAT_JSON,
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $response->headers->add('Access-Control-Allow-Origin', 'http://localhost:3000');
                $response->headers->add('Access-Control-Allow-Credentials', 'true');
                $response->headers->add('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
                $response->headers->add('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
            }
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            // 'enableStrictParsing' => true,
            'rules' => [

            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

```
