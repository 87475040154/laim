<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these projectData have multiple versions such
    | as the size projectData. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute атрибут :должен быть принят.',
    'active_url' => ':attribute не является допустимым URL.',
    'after' => ':attribute должен быть датой меньше :date.',
    'after_or_equal' => ':attribute должен быть датой после или равной :date.',
    'alpha' => ':attribute может содержать только буквы.',
    'alpha_dash' => ':attribute может содержать только буквы, цифры, тире и подчеркивания.',
    'alpha_num' => ':attribute может содержать только буквы и цифры.',
    'array' => ':attribute должен быть массивом.',
    'before' => ':attribute должен быть датой before :date.',
    'before_or_equal' => ':attribute должен быть датой до или равным :date.',
    'between' => [
        'numeric' => ':attribute должен быть между :min и :max.',
        'file' => ':attribute должен быть между :min и :max килобайт.',
        'string' => ':attribute должен быть между :min и :max символами.',
        'array' => ':attribute должен иметь значения между :min и :max.',
    ],
    'boolean' => 'поле :attribute должно быть true или false.',
    'confirmed' => 'подтверждение :attribute не совпадает.',
    'date' => ':attribute не является допустимой датой.',
    'date_equals' => ':attribute должен быть датой, равной :date.',
    'date_format' => ':attribute не соответствует формату :format.',
    'different' => ':attribute and :other должен быть другим.',
    'digits' => ':attribute должен быть :digits десятичные знаки.',
    'digits_between' => ':attribute должен быть между :min и :max цифрами.',
    'dimensions' => ':attribute имеет недопустимые размеры изображения.',
    'distinct' => ':attribute поле имеет повторяющееся значение.',
    'email' => ':attribute должен быть действительным адресом электронной почты.',
    'exists' => 'выбранный :attribute недопустим.',
    'file' => ':attribute должен быть файлом.',
    'filled' => ':attribute поле должно иметь значение.',
    'gt' => [
        'numeric' => ':attribute должен быть больше :value.',
        'file' => ':attribute должен быть больше :value в килобайтах.',
        'string' => ':attribute должен быть больше символов :value.',
        'array' => ':attribute должен содержать больше элементов, чем :value.',
    ],
    'gte' => [
        'numeric' => ':attribute должен быть больше или равен :value.',
        'file' => ':attribute должен быть больше или равен :value в килобайтах.',
        'string' => ':attribute должен быть больше или равен :value значения.',
        'array' => ':attribute должен иметь :value предметы или более.',
    ],
    'image' => ':attribute должен быть изображением.',
    'in' => 'выбранный :attribute недопустим.',
    'in_array' => 'поле :attribute не существует в  :other.',
    'integer' => ':attribute должен быть целым числом.',
    'ip' => ':attribute должен быть допустимым IP-адресом.',
    'ipv4' => ':attribute должен быть допустимым IPv4-адресом.',
    'ipv6' => ':attribute должен быть допустимым IPv6-адресом.',
    'json' => ':attribute должен быть допустимой строкой JSON.',
    'lt' => [
        'numeric' => ':attribute должен быть меньше :value.',
        'file' => ':attribute должен быть меньше :value в килобайтах.',
        'string' => ':attribute должен быть меньше символов :value.',
        'array' => ':attribute должен содержать элементы меньше :value.',
    ],
    'lte' => [
        'numeric' => ':attribute должен быть меньше или равен :value.',
        'file' => ':attribute должен быть меньше или равен :value в килобайтах.',
        'string' => ':attribute должен быть меньше или равен :value символов.',
        'array' => ':attribute должен иметь не более :value элементов.',
    ],
    'max' => [
        'numeric' => ':attribute не может быть больше :max.',
        'file' => ':attribute не может быть больше :max килобайтов.',
        'string' => ':attribute не может быть больше :max символов.',
        'array' => ':attribute может содержать не более :max элементов.',
    ],
    'mimes' => ':attribute должен быть файлом type: :values.',
    'mimetypes' => ':attribute должен быть файлом тype: :values.',
    'min' => [
        'numeric' => ':attribute должен быть не менее :min.',
        'file' => ':attribute должен быть не менее :min килобайт.',
        'string' => ':attribute должен быть не менее :min символов.',
        'array' => ':attribute должен иметь по крайней мере :min элементов.',
    ],
    'not_in' => 'выбранный :attribute недопустим.',
    'not_regex' => 'недопустимый формат :attribute.',
    'numeric' => ':attribute должен быть числом.',
    'present' => ':должно присутствовать поле :attribute.',
    'regex' => ':недопустимый формат :attribute.',
    'required' => ' поле :attribute обязательно.',
    'required_if' => ' поле :attribute требуется, когда :other is :value.',
    'required_unless' => ' поле :attribute требуется, если :other is in :values.',
    'required_with' => ' поле :attribute требуется, когда :values присутствует.',
    'required_with_all' => ' поле :attribute требуется, когда :values присутствуют.',
    'required_without' => ' поле :attribute требуется, если :values нет.',
    'required_without_all' => ' поле :attribute требуется, когда ни одно из :values не присутствуют.',
    'same' => ' :attribute и :other должен совпадать.',
    'size' => [
        'numeric' => ':attribute должен быть :size.',
        'file' => ':attribute должен быть :size килобайт.',
        'string' => ':attribute должен быть :size символов.',
        'array' => ':attribute должен содержать элементы :size.',
    ],
    'starts_with' => ':attribute должен начинаться с одного из following: :values',
    'string' => ':attribute должен быть строкой.',
    'timezone' => ':attribute должен быть допустимой зоной.',
    'unique' => ':attribute уже взят.',
    'uploaded' => ':attribute не удалось загрузить.',
    'url' => 'недопустимый формат :attribute.',
    'UUID' => ':attribute должен быть допустимым UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'name' => [
            'required' => 'Введите имя.',
            'string' => 'Поле имя должн быть строкой.',
            'max' => 'Максимальная длина имени 255 символов.',
        ],
        'email' => [
            'required' => 'Введите e-mail.',
            'string' => 'Поле e-mail должн быть строкой.',
            'max' => 'Максимальная длина e-mail 255 символов.',
            'unique' => 'Данный email уже зарегистрирован.',
            'email' => 'E-mail должен быть адресом электронной почты..',
        ],
        'password' => [
            'required' => 'Введите пароль.',
            'string' => 'Поле пароля должно быть строкой.',
            'max' => 'Максимальная длина пароля :max символов.',
            'min' => 'Минимальная длина пароля :min символов.',
            'confirmed' => 'Павторный пароль введен не верно.'
        ],
        'password_confirmation' => [
            'same' => 'Павторный пароль введен неверно.',
        ],
        'agree' => [
            'accepted' => 'Вы должны согласиться',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
