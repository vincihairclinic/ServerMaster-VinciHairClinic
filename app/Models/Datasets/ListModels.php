<?php

namespace App\Models\Datasets;

use App\Models\Subscription;

/**
 * Class UserRole
 */
class ListModels extends Dataset
{
    const SUBSCRIPTIONS = 'subscriptions';
    static $data = [
        self::SUBSCRIPTIONS => [
            'id' => self::SUBSCRIPTIONS,
            'model' => Subscription::class,
            'name' => 'Subscription',
            'plural' => 'Subscriptions',
            'Modal' => true,
            'dataTable' => [
                'description_short' => [
                    'data' => 'description_short',
                    'title' => 'Description short',
                    'width' => '100%',
                    'className' => 'text-truncate',
                ]
            ],
            'fields' => [
                'description_short' => [
                    'type' => 'text',
                    'id' => 'description_short',
                    'title' => 'Description short',
                ],
                'description' => [
                    'type' => 'text',
                    'id' => 'description',
                    'title' => 'Description',
                ],
                'purchase_id' => [
                    'type' => 'text',
                    'id' => 'purchase_id',
                    'title' => 'Purchase id',
                ],
                'price' => [
                    'type' => 'text',
                    'id' => 'price',
                    'title' => 'Price',
                ],
                'type_id' => [
                    'type' => 'select',
                    'id' => 'type_id',
                    'title' => 'Type',
                    'options' => '\App\Models\Datasets\SubscriptionType'
                ],
            ],
        ],
    ];
}
