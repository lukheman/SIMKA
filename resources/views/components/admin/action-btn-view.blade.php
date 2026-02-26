@props([
    'label' => 'Detail',
    'icon' => 'fas fa-eye',
])

<button {{ $attributes->merge(['class' => 'action-btn action-btn-view', 'type' => 'button', 'title' => $label]) }}>
    <i class="{{ $icon }}"></i> {{ $label }}
</button>
