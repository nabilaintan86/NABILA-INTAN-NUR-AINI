<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;

class NotePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['staff', 'super_admin'], true);
    }

    public function view(User $user, Note $note): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['staff', 'super_admin'], true);
    }

    public function update(User $user, Note $note): bool
    {
        return $user->role === 'super_admin'
            || ($user->role === 'staff' && $note->user_id === $user->id);
    }

    public function delete(User $user, Note $note): bool
    {
        return $this->update($user, $note);
    }
}
