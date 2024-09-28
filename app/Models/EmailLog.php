<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'order_id',
        'email_content',
        'email_sent',
        'response'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function emailLogs()
{
    return $this->hasMany(EmailLog::class);
}

}

