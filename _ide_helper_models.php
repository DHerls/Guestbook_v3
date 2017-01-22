<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Adult
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $first_name
 * @property string $last_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Member $member
 * @method static \Illuminate\Database\Query\Builder|\App\Adult whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adult whereMemberId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adult whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adult whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adult whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adult whereUpdatedAt($value)
 */
	class Adult extends \Eloquent {}
}

namespace App{
/**
 * App\BalanceUpdate
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $user_id
 * @property float $change_amount
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Member $member
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\BalanceUpdate whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BalanceUpdate whereMemberId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BalanceUpdate whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BalanceUpdate whereChangeAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BalanceUpdate whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BalanceUpdate whereUpdatedAt($value)
 */
	class BalanceUpdate extends \Eloquent {}
}

namespace App{
/**
 * App\Child
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $first_name
 * @property string $last_name
 * @property integer $birth_year
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Member $member
 * @method static \Illuminate\Database\Query\Builder|\App\Child whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Child whereMemberId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Child whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Child whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Child whereBirthYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Child whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Child whereUpdatedAt($value)
 */
	class Child extends \Eloquent {}
}

namespace App{
/**
 * App\Email
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $address
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Member $member
 * @method static \Illuminate\Database\Query\Builder|\App\Email whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Email whereMemberId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Email whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Email whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Email whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Email whereUpdatedAt($value)
 */
	class Email extends \Eloquent {}
}

namespace App{
/**
 * App\Guest
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $city
 * @property boolean $out_of_state
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GuestRecord[] $guestRecords
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GuestVisit[] $guestVisits
 * @method static \Illuminate\Database\Query\Builder|\App\Guest whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Guest whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Guest whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Guest whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Guest whereOutOfState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Guest whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Guest whereUpdatedAt($value)
 */
	class Guest extends \Eloquent {}
}

namespace App{
/**
 * App\GuestRecord
 *
 * @property integer $id
 * @property integer $guest_id
 * @property integer $member_id
 * @property integer $user_id
 * @property boolean $num_adults
 * @property boolean $payment_method
 * @property boolean $num_children
 * @property mixed $member_signature
 * @property mixed $guest_signature
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \App\Member $member
 * @property-read \App\Guest $guest
 * @method static \Illuminate\Database\Query\Builder|\App\GuestRecord whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestRecord whereGuestId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestRecord whereMemberId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestRecord whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestRecord whereNumAdults($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestRecord wherePaymentMethod($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestRecord whereNumChildren($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestRecord whereMemberSignature($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestRecord whereGuestSignature($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestRecord whereUpdatedAt($value)
 */
	class GuestRecord extends \Eloquent {}
}

namespace App{
/**
 * App\GuestVisit
 *
 * @property integer $id
 * @property integer $guest_id
 * @property integer $year
 * @property boolean $num_visits
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Guest $guest
 * @method static \Illuminate\Database\Query\Builder|\App\GuestVisit whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestVisit whereGuestId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestVisit whereYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestVisit whereNumVisits($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestVisit whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GuestVisit whereUpdatedAt($value)
 */
	class GuestVisit extends \Eloquent {}
}

namespace App{
/**
 * App\Member
 *
 * @property integer $id
 * @property string $address_line_1
 * @property string $address_line_2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property float $current_balance
 * @property boolean $disabled
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Adult[] $adults
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Child[] $children
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Phone[] $phones
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Email[] $emails
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GuestRecord[] $guestRecords
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MemberRecord[] $memberRecords
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\BalanceUpdate[] $balanceUpdates
 * @method static \Illuminate\Database\Query\Builder|\App\Member whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Member whereAddressLine1($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Member whereAddressLine2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Member whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Member whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Member whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Member whereCurrentBalance($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Member whereDisabled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Member whereUpdatedAt($value)
 */
	class Member extends \Eloquent {}
}

namespace App{
/**
 * App\MemberRecord
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $user_id
 * @property boolean $num_members
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \App\Member $member
 * @method static \Illuminate\Database\Query\Builder|\App\MemberRecord whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MemberRecord whereMemberId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MemberRecord whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MemberRecord whereNumMembers($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MemberRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MemberRecord whereUpdatedAt($value)
 */
	class MemberRecord extends \Eloquent {}
}

namespace App{
/**
 * App\Comment
 *
 * @property-read \App\Member $member
 * @property-read \App\User $user
 */
	class Comment extends \Eloquent {}
}

namespace App{
/**
 * App\Phone
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $number
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Member $member
 * @method static \Illuminate\Database\Query\Builder|\App\Phone whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Phone whereMemberId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Phone whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Phone whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Phone whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Phone whereUpdatedAt($value)
 */
	class Phone extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property boolean $disabled
 * @property boolean $admin
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GuestRecord[] $guestRecords
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MemberRecord[] $memberRecords
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\BalanceUpdate[] $balanceUpdate
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereDisabled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereAdmin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

