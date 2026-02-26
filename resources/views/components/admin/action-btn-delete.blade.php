@props([
    'label' => 'Hapus',
    'icon' => 'fas fa-trash-alt',
])

<button {{ $attributes->merge(['class' => 'action-btn action-btn-delete', 'type' => 'button', 'title' => $label]) }}>
    <i class="{{ $icon }}"></i> {{ $label }}
</button>
