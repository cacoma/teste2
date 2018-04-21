<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\broker;
use App\monthlyQuote;
use App\stock;
class invest extends Model
{
    //
  protected $table = 'invests';
  //Accessors and mutators allow you to format Eloquent attribute values when you retrieve or set them on model instances. 
  //The date will properly be stored and converted on output so long as you add the field to your $dates property on your model.
  protected $dates = ['created_at','updated_at','date_invest'];
  
  protected $fillable = [
    'type', 'symbol', 'quant', 'price',	'created_at',	'user_id', 'date_invest', 'broker_fee','broker_id',
  ];
  
  public function user()
    {
        return $this->belongsTo(User::Class,'user_id','id');
    }
  
  public function broker()
    {
        return $this->belongsTo(broker::Class,'broker_id','id');
    }
  
    public function stock()
    {
        return $this->belongsTo(stock::Class);
    }
    
    public function monthlyQuote()
    {
        return $this->hasMany(monthlyQuote::Class, 'stock_id', 'stock_id');
    }
  
    public function lastMonthlyQuote() 
    {
      return $this->monthlyQuote()->last();  
    }      
    
}