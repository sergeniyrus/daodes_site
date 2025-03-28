<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const STATUS_OPEN = 'open';
    const STATUS_NEGOTIATION = 'negotiation';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_ON_REVIEW = 'on_review';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'title',
        'content',
        'budget',
        'deadline',
        'status',
        'user_id',
        'category_id',
        'accepted_bid_id',
        'rating',
        'completed',
        'start_time',
        'completion_time',
        'end_time',
    ];

    public static function getStatuses()
    {
        return [
            self::STATUS_OPEN => 'Открыто',
            self::STATUS_NEGOTIATION => 'Обсуждение',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_ON_REVIEW => 'На проверке',
            self::STATUS_COMPLETED => 'Выполнено',
            self::STATUS_FAILED => 'Провалено',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class)
            ->orderBy('price', 'asc')
            ->orderBy('days', 'asc')
            ->orderBy('hours', 'asc');
    }

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

    public function getDeadlineAttribute($value)
    {
        return \Carbon\Carbon::parse($value);
    }

    public function getBudgetAttribute($value)
    {
        return number_format($value, 2);
    }

    public function isOpen()
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isNegotiation()
    {
        return $this->status === self::STATUS_NEGOTIATION;
    }

    public function isInProgress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isOnReview()
    {
        return $this->status === self::STATUS_ON_REVIEW;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function setStatus($status)
    {
        if (!array_key_exists($status, self::getStatuses())) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        $this->status = $status;
        return $this;
    }

    public function getStatusText()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? 'Неизвестный статус';
    }

    public function canUpdateStatus($newStatus)
    {
        if ($this->status === self::STATUS_COMPLETED || $this->status === self::STATUS_FAILED) {
            return false;
        }

        return array_key_exists($newStatus, self::getStatuses());
    }

    public function getNextStatus()
    {
        switch ($this->status) {
            case self::STATUS_OPEN:
                return self::STATUS_NEGOTIATION;
            case self::STATUS_NEGOTIATION:
                return self::STATUS_IN_PROGRESS;
            case self::STATUS_IN_PROGRESS:
                return self::STATUS_ON_REVIEW;
            case self::STATUS_ON_REVIEW:
                return self::STATUS_COMPLETED;
            default:
                return null;
        }
    }

    public function canBeDeleted()
    {
        return in_array($this->status, [self::STATUS_OPEN, self::STATUS_NEGOTIATION]);
    }
}
