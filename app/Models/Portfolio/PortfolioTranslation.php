<?php

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioTranslation extends Model
{
    use HasFactory;
    protected $table='portfolio_translations';
    protected $fillable=[
        'id',
        'portfolio_id',
        'name',
        'client',
        'desc',
        'local'];
    protected $hidden=['portfolio_id','local'];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}
