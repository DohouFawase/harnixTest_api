<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class feedBack extends Model
{
    //*
    use Notifiable;

    protected $fillable = [
        "name",
        "email",   
        "product_service",
        'rating',
        "comment",
        "response",
        'status',
    ];


    // Cette méthode est utilisée pour récupérer l'adresse email de l'utilisateur
    public function routeNotificationForMail()
    {
        return $this->email; // L'email de l'utilisateur sera utilisé pour l'envoi de la notification
    }


  
}
