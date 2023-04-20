<?php

namespace App\Policies;

use App\Models\CalendarEvent;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalendarEventPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CalendarEvent $calendarEvent)
    {
        // return $calendarEvent->acl($user)->read;
        return PermissionService::can($user, $calendarEvent, 'read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CalendarEvent $calendarEvent)
    {
        return PermissionService::can($user, $calendarEvent, 'update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CalendarEvent $calendarEvent)
    {
        return PermissionService::can($user, $calendarEvent, 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CalendarEvent $calendarEvent)
    {
        return PermissionService::can($user, $calendarEvent, 'delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CalendarEvent $calendarEvent)
    {
        return false;
    }
}
