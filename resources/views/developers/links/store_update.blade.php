@php
    $parameters = [
        [
            'name' => 'url',
            'type' => $type,
            'format' => 'string',
            'description' => __('The link to be shortened.')
        ],
        [
            'name' => 'alias',
            'type' => 0,
            'format' => 'string',
            'description' => __('The link alias.')
        ],
        [
            'name' => 'password',
            'type' => 0,
            'format' => 'string',
            'description' => __('The link password.')
        ],
        [
            'name' => 'space',
            'type' => 0,
            'format' => 'integer',
            'description' => __('The space id the link to be saved under.')
        ],
        [
            'name' => 'domain',
            'type' => 0,
            'format' => 'integer',
            'description' => __('The domain id the link to be saved under.')
        ],
        [
            'name' => 'disabled',
            'type' => 0,
            'format' => 'integer',
            'description' => __('Whether the link is disabled or not.') . ' ' . __('Defaults to: :value.', ['value' => '<code>0</code>'])
        ],
        [
            'name' => 'public',
            'type' => 0,
            'format' => 'integer',
            'description' => __('Whether the link stats are public or not.') . ' ' . __('Defaults to: :value.', ['value' => '<code>0</code>'])
        ],
        [
            'name' => 'expiration_url',
            'type' => 0,
            'format' => 'string',
            'description' => __('The link where the user will be redirected once the link has expired.')
        ],
        [
            'name' => 'expiration_date',
            'type' => 0,
            'format' => 'string',
            'description' => __('The link expiration date in :format format.', ['format' => '<code>YYYY-MM-DD</code>'])
        ],
        [
            'name' => 'expiration_time',
            'type' => 0,
            'format' => 'string',
            'description' => __('The link expiration time in :format format.', ['format' => '<code>HH:MM</code>'])
        ],
        [
            'name' => 'expiration_clicks',
            'type' => 0,
            'format' => 'integer',
            'description' => __('The number of clicks after which the link should expire.')
        ],
        [
            'name' => 'target_type',
            'type' => 0,
            'format' => 'integer',
            'description' => __('The type of targeting.') . ' ' . __('Possible values are: :values.', [
                'values' => implode(', ', [
                    __(':value for :name', ['value' => '<code>0</code>', 'name' => '<span class="font-weight-medium">'.__('None').'</span>']),
                    __(':value for :name', ['value' => '<code>1</code>', 'name' => '<span class="font-weight-medium">'.__('Geographic').'</span>']),
                    __(':value for :name', ['value' => '<code>2</code>', 'name' => '<span class="font-weight-medium">'.__('Platform').'</span>']),
                    __(':value for :name', ['value' => '<code>3</code>', 'name' => '<span class="font-weight-medium">'.__('Rotation').'</span>'])
                    ])
                ])
        ],
        [
            'name' => 'geo[index][key]',
            'type' => 0,
            'format' => 'string',
            'description' => __('The code of the targeted country. The code must be in :standard standard.', ['standard' => '<a href="https://wikipedia.org/wiki/ISO_3166-1_alpha-2#Officially_assigned_code_elements" target="_blank" rel="nofollow">ISO 3166-1 alpha-2</a>'])
        ],
        [
            'name' => 'geo[index][value]',
            'type' => 0,
            'format' => 'string',
            'description' => __('The country link where the user will be redirected to.')
        ],
        [
            'name' => 'platform[index][key]',
            'type' => 0,
            'format' => 'string',
            'description' => __('The name of the targeted platform.') . ' ' . __('Possible values are: :values.', ['values' => '<code>'.implode('</code>, <code>', config('platforms')).'</code>'])
        ],
        [
            'name' => 'platform[index][value]',
            'type' => 0,
            'format' => 'string',
            'description' => __('The platform link where the user will be redirected to.')
        ],
        [
            'name' => 'rotation[index][value]',
            'type' => 0,
            'format' => 'string',
            'description' => __('The rotation link where the user will be redirected to.')
        ]
    ];
@endphp

@include('developers.parameters')