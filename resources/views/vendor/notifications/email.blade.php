@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'green';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
<div style="font-size: 12px">
    @foreach ($outroLines as $line)
        {{ $line }}
    @endforeach
</div>


{{-- Salutation --}}
<div style="font-size: 12px; margin-top: 15px">

@if (! empty($salutation))
{{ $salutation }}
@else
        @lang('C уважением'),<br>
        {{ config('app.name') }}
        @endif
</div>


{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
    "Если у вас возникли проблемы с нажатием кнопки \":actionText\" скопируйте и вставьте URL-адрес ниже\n".
    'в ваш веб-браузер: [:actionURL](:actionURL)',
    [
        'actionText' => $actionText,
        'actionURL' => $actionUrl,
    ]
)
@endslot
@endisset
@endcomponent
