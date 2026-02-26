@props([
    'label' => 'Edit',
    'icon' => 'fas fa-edit',
])

<button {{ $attributes->merge(['class' => 'action-btn action-btn-edit', 'type' => 'button', 'title' => $label]) }}>
    <i class="{{ $icon }}"></i> {{ $label }}
</button>
