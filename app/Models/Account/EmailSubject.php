<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSubject
{
    use HasFactory;

    public $mensagem;
    public $nameBottom;
    public $link;
    public $assunto;
}
