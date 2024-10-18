<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'deadline',
        'budget',
        'status',
        'category_id',
        'user_id',
        'accepted_bid_id',
        'completion_time',
        'rating', // Добавлено новое поле
        'completed',
    ];
    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь с предложениями
    public function bids()
    {
        // Сортировка предложений по цене (от меньшего к большему) и времени выполнения
        return $this->hasMany(Bid::class)
            ->orderBy('price', 'asc') // Сортировка по цене
            ->orderBy('days', 'asc')  // Сортировка по дням
            ->orderBy('hours', 'asc'); // Сортировка по часам
    }

    // Связь с голосами (лайк/дизлайк)
    public function votes()
    {
        return $this->hasMany(TaskVote::class);
    }

    public function category()
{
    return $this->belongsTo(TaskCategory::class);

}

public function acceptedBid()
{
    return $this->belongsTo(Bid::class, 'accepted_bid_id');
}


}
