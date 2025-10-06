@param(['type' => 'secondary'])
<?php
// No whitespace before opening PHP tag
$typeClasses = [
    'info' => 'bg-info text-dark',
    'warning' => 'bg-warning text-dark',
    'danger' => 'bg-danger text-white',
    'success' => 'bg-success text-white',
    'primary' => 'bg-primary text-white',
    'secondary' => 'bg-secondary text-white',
];
$badgeClass = $typeClasses[$type] ?? 'bg-secondary text-white';
?>
<span class="badge {{ $badgeClass}} }} rounded-pill">{{ $slotContent }}</span>