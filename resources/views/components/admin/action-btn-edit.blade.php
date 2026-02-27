@props([
    'label' => 'Edit',
    'icon' => 'fas fa-edit',
    'href' => null,
])
@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'action-btn action-btn-edit', 'title' => $label, 'style' => 'text-decoration: none;']) }}>
        <i class="{{ $icon }}"></i> {{ $label }}
    </a>
@else
    <button {{ $attributes->merge(['class' => 'action-btn action-btn-edit', 'type' => 'button', 'title' => $label]) }}>
        <i class="{{ $icon }}"></i> {{ $label }}
    </button>
@endif
