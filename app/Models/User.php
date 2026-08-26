<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Date;
use App\Models\Organization;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Поля, которые можно массово назначать.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'password',
    ];

    /**
     * Поля, которые должны быть скрыты при сериализации.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Преобразование типов атрибутов.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_seen_at' => 'datetime', // ← добавлено для корректной работы isOnline()
        ];
    }
/**
 * Организации пользователя.
 */
public function organizations(): BelongsToMany
{
    return $this->belongsToMany(
        Organization::class,
        'organization_users',
        'user_id',
        'organization_id'
    )->withPivot([
        'role',
        'status',
    ])->withTimestamps();
}

/**
 * Проверяет, является ли пользователь менеджером организации.
 */
public function isOrganizationManager(int $organizationId): bool
{
    return $this->organizations()
        ->where('organizations.id', $organizationId)
        ->wherePivot('role', 'manager')
        ->wherePivot('status', 'active')
        ->exists();
}

/**
 * Проверяет, является ли пользователь активным
 * участником организации.
 *
 * Активный участник:
 * - manager + active
 * - employee + active
 */
public function isActiveOrganizationMember(int $organizationId): bool
{
    return $this->organizations()
        ->where('organizations.id', $organizationId)
        ->wherePivot('status', 'active')
        ->exists();
}

/**
 * Проверяет, является ли пользователь активным сотрудником организации.
 */
public function isOrganizationEmployee(int $organizationId): bool
{
    return $this->organizations()
        ->where('organizations.id', $organizationId)
        ->wherePivot('role', 'employee')
        ->wherePivot('status', 'active')
        ->exists();
}

    /**
     * Определяем отношение с задачами, созданными пользователем.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Определяем отношение с заявками, сделанными пользователем.
     */
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Определяем отношение с семенами.
     */
    public function seeds()
    {
        return $this->hasMany(Seed::class);
    }

    /**
     * Определяем отношение с профилем пользователя.
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Определяем отношение с чатами.
     */
    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class, 'chat_user', 'user_id', 'chat_id');
    }

    /**
     * Определяем отношение с уведомлениями пользователя.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }



    /**
     * Проверяет, онлайн ли пользователь прямо сейчас (в течение последних 30 секунд).
     * Используется для уведомлений и отображения статуса.
     */
    public function isOnline(): bool
    {
        return $this->last_seen_at &&
            $this->last_seen_at->isAfter(now()->subSeconds(30));
    }

    /**
     * Человекопонятное представление статуса
     */
    public function lastSeenHuman(): string
    {
        if ($this->isOnline()) {
            return 'В сети';
        }

        if (!$this->last_seen_at) {
            return 'Не в сети';
        }

        return $this->last_seen_at->diffForHumans(null, \Carbon\Carbon::DIFF_RELATIVE_TO_NOW, true);
    }
}
