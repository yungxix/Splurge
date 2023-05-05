<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Str;
class RoleQuery {
    private $suffix;
    private $user;

    public function __construct(User $user, $class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }
        $kls = class_basename($class);
        $this->suffix =  Str::plural(Str::kebab($kls));
        $this->user = $user;
    }

    public function has($role) {
        $roles = [
            $this->fullRoleName($role),
            $this->wildcardRoleName($role)
        ];
        return $this->user->roles->contains(fn ($r, $k) => in_array($r->name, $roles));
    }
    
    public function hasSimple($role) {
        return $this->user->roles->contains(fn ($r) => $r->name === $role);
    }

    private function fullRoleName($role) {
        return sprintf('%s-%s', $role, $this->suffix);
    }

    private function wildcardRoleName($role) {
        return sprintf('%s-*', $role);
    }

    public function isAdmin() {
        return $this->hasSimple('admin');
    }

    public function isSuperAdmin() {
        return $this->hasSimple('super') || $this->hasSimple('system');
    }

    public function canManage() {
        return $this->hasSimple(sprintf('manager-%s', $this->suffix));
    }

    public function canEdit() {
        return $this->canAction('edit');
    }

    public function canCreate() {
        return $this->canAction('create');
    }

    public function canDelete() {
        return $this->canAction('delete');
    }

    public function canView() {
        return $this->canAction('view') || $this->canAction('read');
    }


    private function canAction($action) {
        return $this->isSuperAdmin() || $this->has($action) || $this->canManage();
    }
}