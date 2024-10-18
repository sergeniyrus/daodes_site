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
 * @property int $task_id
 * @property int $user_id
 * @property string $price
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $days
 * @property int $hours
 * @property-read \App\Models\Task $task
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Bid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bid query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereUserId($value)
 */
	class Bid extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $id_offer
 * @property int $id_user
 * @property string $text
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIdOffer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 */
	class Comment extends \Eloquent {}
}

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
 * @property-read \App\Models\Wallet|null $fromWallet
 * @property-read \App\Models\Wallet|null $toWallet
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property string $content
 * @property string $img
 * @property int $category_id
 * @property string $user_id
 * @property int|null $views
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|News newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|News query()
 * @method static \Illuminate\Database\Eloquent\Builder|News whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereViews($value)
 */
	class News extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $word0
 * @property string $word1
 * @property string $word2
 * @property string $word3
 * @property string $word4
 * @property string $word5
 * @property string $word6
 * @property string $word7
 * @property string $word8
 * @property string $word9
 * @property string $word10
 * @property string $word11
 * @property string $word12
 * @property string $word13
 * @property string $word14
 * @property string $word15
 * @property string $word16
 * @property string $word17
 * @property string $word18
 * @property string $word19
 * @property string $word20
 * @property string $word21
 * @property string $word22
 * @property string $word23
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Seed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seed query()
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord13($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord14($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord15($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord16($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord17($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord18($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord19($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord20($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord21($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord22($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord23($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereWord9($value)
 */
	class Seed extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $accepted_bid_id
 * @property string $title
 * @property string $description
 * @property string $budget
 * @property string|null $deadline_time
 * @property string $status
 * @property int $user_id
 * @property int|null $likes
 * @property int|null $dislikes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $category_id
 * @property string|null $start_time
 * @property int $in_progress
 * @property int|null $completed
 * @property string|null $deadline
 * @property string|null $completion_time
 * @property int|null $rating
 * @property string|null $completed_at
 * @property-read \App\Models\Bid|null $acceptedBid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bid> $bids
 * @property-read int|null $bids_count
 * @property-read \App\Models\TaskCategory|null $category
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TaskVote> $votes
 * @property-read int|null $votes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereAcceptedBidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCompletionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDeadlineTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDislikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereInProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUserId($value)
 */
	class Task extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @method static \Illuminate\Database\Eloquent\Builder|TaskCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskCategory whereUpdatedAt($value)
 */
	class TaskCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $task_id
 * @property int $user_id
 * @property int $is_like
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Task $task
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TaskVote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskVote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskVote query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskVote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskVote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskVote whereIsLike($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskVote whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskVote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskVote whereUserId($value)
 */
	class TaskVote extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property mixed $password
 * @property string|null $keyword
 * @property int|null $rang_access
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bid> $bids
 * @property-read int|null $bids_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Seed> $seeds
 * @property-read int|null $seeds_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
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
 * @property-read \App\Models\User|null $user
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
 * @property int $id_offer
 * @property int $id_user
 * @property string $text
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer query()
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer whereIdOffer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|comments_offer whereIdUser($value)
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
 * @property string $content
 * @property string $img
 * @property int $category_id
 * @property int $user_id
 * @property int|null $views
 * @property int|null $state
 * @property string|null $start_vote
 * @property string|null $type
 * @property string|null $method
 * @property int|null $budget
 * @property string|null $coin
 * @property string|null $control
 * @property string|null $finish
 * @property string|null $pdf_ipfs_cid
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|offers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|offers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|offers query()
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereCoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereFinish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers wherePdfIpfsCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereStartVote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereViews($value)
 */
	class offers extends \Eloquent {}
}

