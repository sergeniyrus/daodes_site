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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bid query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bid whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bid whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bid whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bid whereHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bid wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bid whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bid whereUserId($value)
 */
	class Bid extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name_ru
 * @property string $name_en
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read mixed $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\News> $news
 * @property-read int|null $news_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryNews newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryNews newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryNews query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryNews whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryNews whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryNews whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryNews whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryNews whereUpdatedAt($value)
 */
	class CategoryNews extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name_ru
 * @property string|null $name_en
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read mixed $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Offers> $offers
 * @property-read int|null $offers_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryOffers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryOffers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryOffers query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryOffers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryOffers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryOffers whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryOffers whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryOffers whereUpdatedAt($value)
 */
	class CategoryOffers extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name_ru
 * @property string $name_en
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $Tasks
 * @property-read int|null $tasks_count
 * @property-read mixed $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryTasks newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryTasks newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryTasks query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryTasks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryTasks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryTasks whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryTasks whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryTasks whereUpdatedAt($value)
 */
	class CategoryTasks extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property string $type
 * @property int|null $initiator_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $initiator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChatMemberKey> $memberKeys
 * @property-read int|null $member_keys_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $participants
 * @property-read int|null $participants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereInitiatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereUpdatedAt($value)
 */
	class Chat extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $chat_id
 * @property int $user_id
 * @property string $encrypted_key
 * @property string $nonce
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Chat $chat
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMemberKey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMemberKey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMemberKey query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMemberKey whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMemberKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMemberKey whereEncryptedKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMemberKey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMemberKey whereNonce($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMemberKey whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMemberKey whereUserId($value)
 */
	class ChatMemberKey extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereUserId($value)
 */
	class Comment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $news_id
 * @property int $user_id
 * @property string|null $text
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentNews newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentNews newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentNews query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentNews whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentNews whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentNews whereNewsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentNews whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentNews whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentNews whereUserId($value)
 */
	class CommentNews extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $offer_id
 * @property int $user_id
 * @property string|null $text
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentOffer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentOffer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentOffer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentOffer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentOffer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentOffer whereOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentOffer whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentOffer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentOffer whereUserId($value)
 */
	class CommentOffer extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Recipient> $recipients
 * @property-read int|null $recipients_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactList query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactList whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactList whereUpdatedAt($value)
 */
	class ContactList extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryPay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryPay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryPay query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryPay whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryPay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryPay whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryPay whereFromWalletId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryPay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryPay whereToWalletId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryPay whereUpdatedAt($value)
 */
	class HistoryPay extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $template_id
 * @property int $recipient_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $sent_at
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Recipient $recipient
 * @property-read \App\Models\MailTemplate $template
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereUpdatedAt($value)
 */
	class MailLog extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $subject
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailTemplate whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailTemplate whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailTemplate whereUpdatedAt($value)
 */
	class MailTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $chat_id
 * @property int $sender_id
 * @property string $ipfs_cid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Chat $chat
 * @property-read mixed $message
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\User $sender
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereIpfsCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUpdatedAt($value)
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title_ru
 * @property string $title_en
 * @property string $content_ru
 * @property string $content_en
 * @property string|null $img
 * @property int $category_id
 * @property int|null $user_id
 * @property int $views
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CategoryNews $category
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereContentEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereContentRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereTitleEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereTitleRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|News whereViews($value)
 */
	class News extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $message_id
 * @property int $is_read
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Message $message
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUserId($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title_ru
 * @property string|null $title_en
 * @property string $content_ru
 * @property string|null $content_en
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereCoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereContentEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereContentRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereFinish($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers wherePdfIpfsCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereStartVote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereTitleEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereTitleRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offers whereViews($value)
 */
	class Offers extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $imported_from
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactList> $lists
 * @property-read int|null $lists_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recipient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recipient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recipient query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recipient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recipient whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recipient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recipient whereImportedFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recipient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recipient whereUpdatedAt($value)
 */
	class Recipient extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord0($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord10($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord11($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord12($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord13($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord14($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord15($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord16($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord17($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord18($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord19($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord20($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord21($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord22($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord23($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord5($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord6($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord7($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord8($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seed whereWord9($value)
 */
	class Seed extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title_ru
 * @property string $title_en
 * @property string $content_ru
 * @property string $content_en
 * @property string $budget
 * @property string|null $img
 * @property string|null $deadline
 * @property string $status
 * @property int $user_id
 * @property int $category_id
 * @property int $likes
 * @property int $dislikes
 * @property int|null $accepted_bid_id
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $completion_time
 * @property int $completed
 * @property int|null $rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bid|null $acceptedBid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bid> $bids
 * @property-read int|null $bids_count
 * @property-read \App\Models\CategoryTasks $category
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TaskVote> $votes
 * @property-read int|null $votes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereAcceptedBidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereCompletionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereContentEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereContentRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereDislikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereTitleEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereTitleRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task whereUserId($value)
 */
	class Task extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskVote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskVote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskVote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskVote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskVote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskVote whereIsLike($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskVote whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskVote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskVote whereUserId($value)
 */
	class TaskVote extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $password
 * @property string $keyword
 * @property int|null $access_level
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $last_seen_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bid> $bids
 * @property-read int|null $bids_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chat> $chats
 * @property-read int|null $chats_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\UserProfile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Seed> $seeds
 * @property-read int|null $seeds_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAccessLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
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
 * @property int|null $telegram_chat_id Telegram chat_id для уведомлений
 * @property string|null $public_key
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereAchievements($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereActivityLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereEducation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile wherePortfolio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile wherePublicKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereRecommendations($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereResume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereSbtTokens($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereTasksCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereTasksFailed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereTelegramChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereTrustLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereUserId($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereUserId($value)
 */
	class Wallet extends \Eloquent {}
}

