@props(['title', 'count', 'icon', 'color' => 'blue'])

@php
    $colorClasses = [
        'yellow' => 'from-yellow-400 to-yellow-600 text-yellow-100 bg-yellow-500',
        'blue' => 'from-blue-400 to-blue-600 text-blue-100 bg-blue-500',
        'green' => 'from-green-400 to-green-600 text-green-100 bg-green-500',
        'purple' => 'from-purple-400 to-purple-600 text-purple-100 bg-purple-500',
        'red' => 'from-red-400 to-red-600 text-red-100 bg-red-500',
    ];

    $gradientClass = $colorClasses[$color] ?? $colorClasses['blue'];
    [$fromTo, $textClass, $bgClass] = explode(' ', $gradientClass);
@endphp

<div class="bg-gradient-to-br {{ $fromTo }} rounded-lg shadow-lg p-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <p class="{{ $textClass }} text-sm font-medium">{{ $title }}</p>
            <p class="text-3xl font-bold">{{ $count }}</p>
        </div>
        <div class="{{ $bgClass }} bg-opacity-30 rounded-full p-3">
            {!! $icon !!}
        </div>
    </div>
</div>
