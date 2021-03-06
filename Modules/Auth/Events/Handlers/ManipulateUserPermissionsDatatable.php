<?php

namespace Modules\Auth\Events\Handlers;

use Modules\Admin\Events\GotDatatableConfig;
use Illuminate\Support\Facades\Request;
use BeatSwitch\Lock\Manager;

class ManipulateUserPermissionsDatatable
{
    protected $manager;

    public function __construct(Manager $lockManager)
    {
        $this->manager = $lockManager;
    }

    /**
     * Handle the event.
     *
     * @param GotDatatableConfig $event
     */
    public function handle(GotDatatableConfig $event)
    {
        if (Request::route()->getName() !== 'admin.user.permissions') {
            return;
        }

        // grab the user
        $authModel = config('cms.auth.config.user_model');
        $user = with(new $authModel())->find(Request::segment(3));

        // reset the title
        $title = 'User: ' . e($user->screenname);
        array_set($event->config, 'page.title', $title);

        array_set($event->config, 'page.alert', [
            'class' => 'info',
            'text' => '<i class="fa fa-info-circle"></i> This panel will show you all the permissions this user has.',
        ]);

        // clear a few options out
        array_set($event->config, 'options.source', null);

        // rejig the columns
        array_set($event->config, 'columns.actions', null);
        array_set($event->config, 'columns.roles', null);

        // rebuild the collection
        $manager = $this->manager;
        array_set($event->config, 'options.collection', function () use ($manager, $user) {
            $model = 'Modules\Auth\Models\Permission';

            return $model::with('roles')->get()
                ->filter(function ($model) use ($manager, $user) {
                    return $manager
                        ->caller($user)
                        ->can($model->action, $model->resource_type, $model->resource_id);
                });
        });

        return $event->config;
    }
}
