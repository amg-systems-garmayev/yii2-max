<?php

namespace garmayev\max\types;

use yii\base\Model;

class Response extends Model
{
    private bool $_success;
    private string $_message;
    private int $_chat_id;
    private string $_chat_type;
    private string $_status;
}