<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'display_name',
    ];

    /**
     * Vai trò này sở hữu những quyền hạn nào.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }

    /**
     * Kiểm tra vai trò này có quyền cụ thể hay không.
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions->contains('name', $permissionName);
    }
}
