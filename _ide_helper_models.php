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
 * @method string getUrl()
 * @property int $id
 * @property string $uuid
 * @property int $customer_id
 * @property int|null $employee_id
 * @property string $product
 * @property string $comments
 * @property string|null $admin_comments
 * @property \App\Enums\ComplaintStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\User|null $employee
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ComplaintStatusChange> $statusChanges
 * @property-read int|null $status_changes_count
 * @method static \Database\Factories\ComplaintFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint query()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereAdminComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereUuid($value)
 */
	class Complaint extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $complaint_id
 * @property \App\Enums\ComplaintStatus $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property int|null $employee_id
 * @property-read \App\Models\Complaint $complaint
 * @property-read \App\Models\User|null $employee
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintStatusChange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintStatusChange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintStatusChange query()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintStatusChange whereComplaintId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintStatusChange whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintStatusChange whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintStatusChange whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintStatusChange whereStatus($value)
 */
	class ComplaintStatusChange extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $original_password
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $phone
 * @property string|null $email
 * @property mixed $password
 * @property string|null $alert_phone
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Complaint> $complaints
 * @property-read int|null $complaints_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Query> $queries
 * @property-read int|null $queries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\CustomerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAlertPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUuid($value)
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $uuid
 * @property int $customer_id
 * @property int|null $employee_id
 * @property string $product
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Enums\QueryStatus $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QueryComment> $adminComments
 * @property-read int|null $admin_comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QueryComment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QueryComment> $customerComments
 * @property-read int|null $customer_comments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Query newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Query newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Query query()
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereUuid($value)
 */
	class Query extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method string getUrl()
 * @property int $id
 * @property int $query_id
 * @property string $comments
 * @property bool $by_customer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Query|null $customerQuery
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|QueryComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QueryComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QueryComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|QueryComment whereByCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueryComment whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueryComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueryComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueryComment whereQueryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueryComment whereUpdatedAt($value)
 */
	class QueryComment extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $original_password
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $phone
 * @property mixed $password
 * @property \App\Enums\UserRole $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User isAdmin()
 * @method static \Illuminate\Database\Eloquent\Builder|User isemployee()
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUuid($value)
 */
	class User extends \Eloquent {}
}

