<?php
// app/Models/EvaluationItem.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EvaluationItem extends Model
{
    protected $fillable = ['title', 'type', 'sequence', 'category', 'year'];
}
