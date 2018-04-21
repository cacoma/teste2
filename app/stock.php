<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\monthlyQuotes;
use App\invests;
class stock extends Model
{
    //
      use Notifiable;
  
  protected $fillable = [
        'symbol', 'type',
    ];
   protected $hidden = [
    ];
  
  public function monthlyQuotes()
    {
        return $this->hasMany(monthlyQuotes::Class,'id','stock_id');
    }
    
  public function invests()
    {
        return $this->hasMany(invests::Class,'id','stock_id');
    }
    
  
}