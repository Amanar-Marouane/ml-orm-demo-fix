@param(['name' => 'No header title', 'class' => '', 'id' => ''])
<div class="ml-card shadow-sm border-0 rounded-3 mb-3 transition-all <?= $class ?>" <?= !empty($id) ? "id=\"{$id}\"" : "" ?>>
    <?php if (isset($slots['header'])): ?>
        <div class="ml-card-header p-3 d-flex justify-content-between align-items-center">
            <?= $slots['header']() ?>
        </div>
    <?php endif; ?>

    <div class="ml-card-body p-3">
        <?= $slotContent ?>
    </div>

    <?php if (isset($slots['footer'])): ?>
        <div class="ml-card-footer p-3 border-top">
            <?= $slots['footer']() ?>
        </div>
    <?php endif; ?>
</div>