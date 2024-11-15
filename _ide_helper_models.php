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
 * @property string|null $deadline
 * @property string|null $comment
 * @property int $days
 * @property int $hours
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Task $task
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Bid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bid query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bid whereDeadline($value)
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
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\News> $news
 * @property-read int|null $news_count
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryNews newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryNews newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryNews query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryNews whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryNews whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryNews whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryNews whereUpdatedAt($value)
 */
	class CategoryNews extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryOffers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryOffers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryOffers query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryOffers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryOffers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryOffers whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryOffers whereUpdatedAt($value)
 */
	class CategoryOffers extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $offer_id
 * @property int $user_id
 * @property string|null $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
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
 * @property string $amount The amount of money transferred between wallets.
 * @property string $fee The transaction fee applied to the transfer.
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
 * @property string $title
 * @property string $content
 * @property string|null $img
 * @property int $category_id
 * @property int $user_id
 * @property int $views
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CategoryNews $category
 * @property-read \App\Models\User $user
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
 * @property string $title
 * @property string $content
 * @property string|null $img
 * @property int $user_id
 * @property int $category_id
 * @property int $views
 * @property string|null $state
 * @property string|null $method
 * @property string|null $budget
 * @property string|null $coin
 * @property string|null $control
 * @property string|null $finish
 * @property string|null $start_vote
 * @property string|null $pdf_ipfs_cid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Offers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offers query()
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereCoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereFinish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers wherePdfIpfsCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereStartVote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereViews($value)
 */
	class Offers extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $word0
 * @property string|null $word1
 * @property string|null $word2
 * @property string|null $word3
 * @property string|null $word4
 * @property string|null $word5
 * @property string|null $word6
 * @property string|null $word7
 * @property string|null $word8
 * @property string|null $word9
 * @property string|null $word10
 * @property string|null $word11
 * @property string|null $word12
 * @property string|null $word13
 * @property string|null $word14
 * @property string|null $word15
 * @property string|null $word16
 * @property string|null $word17
 * @property string|null $word18
 * @property string|null $word19
 * @property string|null $word20
 * @property string|null $word21
 * @property string|null $word22
 * @property string|null $word23
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Seed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seed query()
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereUpdatedAt($value)
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
 * @property string $title
 * @property string $content
 * @property string $budget
 * @property string|null $deadline
 * @property string $status
 * @property int $user_id
 * @property int|null $category_id
 * @property int $likes
 * @property int $dislikes
 * @property int|null $accepted_bid_id
 * @property string|null $start_time
 * @property int $in_progress
 * @property int $completed
 * @property string|null $completion_time
 * @property int|null $rating
 * @property string|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDeadline($value)
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
 * @property string $keyword
 * @property int|null $access_level
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bid> $bids
 * @property-read int|null $bids_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\UserProfile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Seed> $seeds
 * @property-read int|null $seeds_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccessLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
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
 * @property string $role
 * @property string|null $avatar_url
 * @property string|null $nickname
 * @property string|null $gender
 * @property string|null $timezone
 * @property string|null $languages
 * @property string|null $birth_date
 * @property string|null $education
 * @property string|null $resume
 * @property string|null $portfolio
 * @property string|null $specialization
 * @property string $rating
 * @property string $trust_level
 * @property string $sbt_tokens
 * @property int $tasks_completed
 * @property int $tasks_failed
 * @property string|null $recommendations
 * @property string|null $activity_log
 * @property string|null $achievements
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read UserProfile|null $profile
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereAchievements($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereActivityLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereEducation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile wherePortfolio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereRecommendations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereResume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereSbtTokens($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereTasksCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereTasksFailed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereTrustLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUserId($value)
 */
	class UserProfile extends \Eloquent {}
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

