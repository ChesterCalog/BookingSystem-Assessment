<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['user_id', 'actor_type', 'action', 'target', 'details'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public static function log(?User $actor, string $actorType, string $action, ?string $target = null, ?string $details = null): self
    {
        return self::create([
            'user_id' => $actor?->id,
            'actor_type' => $actorType,
            'action' => $action,
            'target' => $target,
            'details' => $details,
        ]);
    }
}
