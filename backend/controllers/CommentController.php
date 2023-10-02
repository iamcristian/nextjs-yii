<?php

namespace app\controllers;

use app\models\Comment;
use yii\rest\ActiveController;

class CommentController extends ActiveController{
    public $modelClass = Comment::class;
}