<?php

namespace Modules\Invoices\Datatables;

use Illuminate\Support\Collection;
use Lock;

class InvoiceManager
{
    public function boot()
    {
        return [
            /*
             * Page Decoration Values
             */
            'page' => [
                'title' => '<i class="fa fa-fw fa-puzzle-piece"></i> Invoice Manager',
                'alert' => [
                    'class' => 'info',
                    'text' => '<i class="fa fa-info-circle"></i> The Invoices',
                ],
                'header' => [
                    [
                        /*
                        'btn-text' => 'Check for Updates',
                        'btn-link' => 'admin.invoices.update',
                        'btn-class' => 'btn btn-info btn-labeled',
                        'btn-icon' => 'fa fa-fw fa-refresh',
                        */
                    ],
                ],
            ],

            /*
             * Set up some table options, these will be passed back to the view
             */
            'options' => [
                'pagination' => false,
                'searching' => false,
                'ordering' => false,
                'sort_column' => 'order',
                'sort_order' => 'desc',
                'source' => null,
                'collection' => function () {
                    $model = 'Modules\Invoices\Models\Invoice';

                    return $model::all();
                },
            ],

            /*
             * Lists the tables columns
             */
            'columns' => [
                // 'Debug' => [
                //     'th' => 'Debug',
                //     'tr' => function ($model) {
                //         return \Debug::dump($model);
                //     },
                //     'width'     => '80%',
                // ],

                'invoice_number' => [
                    'th' => 'Number',
                    'tr' => function ($model) {
                        return array_get($model, 'invoice_number', null);
                    },
                    'orderable' => true,
                    'width' => '10%',
                ],

                'name' => [
                    'th' => 'Title',
                    'tr' => function ($model) {
                        return array_get($model, 'title', null);
                    },
                    'orderable' => true,
                    'searchable' => true,
                    'width' => '35%',
                ],

                /*'alias' => [
                    'th' => 'Namespace',
                    'tr' => function ($model) {
                        return array_get($model, 'alias', null);
                    },
                    'orderable' => true,
                    'searchable' => true,
                    'width' => '10%',
                ],

                'author' => [
                    'th' => 'Author',
                    'tr' => function ($model) {
                        $authors = null;
                        if (empty(array_get($model, 'authors', null))) {
                            return $authors;
                        }

                        foreach (array_get($model, 'authors', null) as $author) {
                            $authors .= sprintf('%s<br />', $author->name);
                        }

                        return $authors;
                    },
                    'orderable' => true,
                    'searchable' => true,
                    'width' => '15%',
                ],

                'version' => [
                    'th' => 'Version',
                    'tr' => function ($model) {
                        return array_get($model, 'version', null);
                    },
                    'orderable' => true,
                    'searchable' => true,
                    'width' => '7%',
                ],

                'keywords' => [
                    'th' => 'Keywords',
                    'tr' => function ($model) {
                        $keywords = null;
                        if (empty(array_get($model, 'keywords', null))) {
                            return $keywords;
                        }

                        $tpl = '<span class="label label-default">%s</span>&nbsp;';
                        foreach (array_get($model, 'keywords', null) as $keyword) {
                            $keywords .= sprintf($tpl, $keyword);
                        }

                        return $keywords;
                    },
                    'width' => '25%',
                ],*/

                /*
                'active' => [
                    'th' => 'Active',
                    'tr' => function ($model) {
                        return array_get($model, 'is_active', null)
                            ? '<div class="label label-success">Active</div>'
                            : '<div class="label label-danger">Not Active</div>';
                    },
                    'width' => '7%',
                ],
                */

                'actions' => [
                    'th' => 'Actions',
                    'tr' => function ($model) {
                        $return = [];

                        //if (Lock::can('manage.read', 'auth_user')) {
                        $return[] = [
                            'btn-title' => 'View Invoice',
                            'btn-link' => route('admin.invoice.view', $model->id),
                            'btn-class' => 'btn btn-default btn-xs btn-labeled',
                            'btn-icon' => 'fa fa-file-text-o',
                        ];
                        //}

                        //if (Lock::can('manage.update', 'auth_user')) {
                        $return[] = [
                            'btn-title' => 'Edit',
                            'btn-link' => route('admin.invoice.edit', $model->id),
                            'btn-class' => 'btn btn-warning btn-xs btn-labeled',
                            'btn-icon' => 'fa fa-pencil',
                        ];
                        //}

                        return $return;
                    },
                ],
            ],
        ];
    }
}
