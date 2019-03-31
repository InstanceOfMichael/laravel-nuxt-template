<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
    implements
        JWTSubject,
        MustVerifyEmail
{
    use Notifiable,
        Concerns\SerializesDates,
        Concerns\HasComments;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'handle',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email', 'email_verified_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'photo_url',
    ];

    /**
     * Get the profile photo URL attribute.
     *
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        // return 'https://www.gravatar.com/avatar/'.md5(strtolower($this->email)).'.jpg?s=200&d=mm';
        // lock must be int
        $keyword = ['kitten', 'dog', 'guinea pig', 'cockatoo'][$this->id % 4];
        return 'https://loremflickr.com/160/160/'.$keyword.'?lock='.hexdec(substr(md5(strtolower($this->email)), 0, 6));
    }

    /**
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(OAuthProvider::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @param string email
     * @return void
     **/
    public function setEmailAttribute(string $email) {
        $this->attributes['email'] = strtolower($email);
    }

    /**
     * Get the groups created by this user.
     */
    public function groups()
    {
        return $this->hasMany(Group::class, 'op_id');
    }

    /**
     * Get the answers created by this user.
     */
    public function answers()
    {
        return $this->hasMany(Answer::class, 'op_id');
    }

    /**
     * Get the questions created by this user.
     */
    public function questions()
    {
        return $this->hasMany(Question::class, 'op_id');
    }

    /**
     * Get the comments created by this user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'op_id');
    }

    /**
     * Get the claims created by this user.
     */
    public function claims()
    {
        return $this->hasMany(Claim::class, 'op_id');
    }

    /**
     * Get the claimrelations created by this user.
     */
    public function claimrelations()
    {
        return $this->hasMany(Claimrelation::class, 'op_id');
    }

    /**
     * Get the links created by this user.
     */
    public function links()
    {
        return $this->hasMany(Link::class, 'op_id');
    }

}
