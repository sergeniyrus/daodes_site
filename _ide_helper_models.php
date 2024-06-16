<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $from_wallet_id
 * @property int $to_wallet_id
 * @property string $amount
 * @property string $fee
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Wallet $fromWallet
 * @property-read \App\Models\Wallet $toWallet
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryPay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryPay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryPay query()
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryPay whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryPay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryPay whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryPay whereFromWalletId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryPay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryPay whereToWalletId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryPay whereUpdatedAt($value)
 */
	class HistoryPay extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $keyword
 * @property int|null $rang_access
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRangAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HistoryPay> $historyPaysFrom
 * @property-read int|null $history_pays_from_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HistoryPay> $historyPaysTo
 * @property-read int|null $history_pays_to_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUserId($value)
 */
	class Wallet extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $category_name
 * @property string $author
 * @method static \Illuminate\Database\Eloquent\Builder|category_news newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|category_news newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|category_news query()
 * @method static \Illuminate\Database\Eloquent\Builder|category_news whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|category_news whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|category_news whereId($value)
 */
	class category_news extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $category_name
 * @property string $author
 * @method static \Illuminate\Database\Eloquent\Builder|category_offers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|category_offers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|category_offers query()
 * @method static \Illuminate\Database\Eloquent\Builder|category_offers whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|category_offers whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|category_offers whereId($value)
 */
	class category_offers extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $new_id
 * @property string $author
 * @property string $text
 * @method static \Illuminate\Database\Eloquent\Builder|comments_news newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|comments_news newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|comments_news query()
 * @method static \Illuminate\Database\Eloquent\Builder|comments_news whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|comments_news whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|comments_news whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|comments_news whereNewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|comments_news whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|comments_news whereUpdatedAt($value)
 */
	class comments_news extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $offer_id
 * @property string $author
 * @property string $text
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer query()
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer whereOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer whereUpdatedAt($value)
 */
	class comments_offer extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property string $text
 * @property string $img
 * @property int $category_id
 * @property string $author
 * @property int|null $views
 * @method static \Illuminate\Database\Eloquent\Builder|news newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|news newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|news query()
 * @method static \Illuminate\Database\Eloquent\Builder|news whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|news whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|news whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|news whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|news whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|news whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|news whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|news whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|news whereViews($value)
 */
	class news extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property string $text
 * @property string $img
 * @property int $category_id
 * @property string $author
 * @property int|null $views
 * @property string|null $state
 * @property string|null $method
 * @property string|null $budget
 * @property string|null $coin
 * @property string|null $control
 * @property string|null $finish
 * @method static \Illuminate\Database\Eloquent\Builder|offers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|offers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|offers query()
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereCoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereFinish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereViews($value)
 */
	class offers extends \Eloquent {}
}

