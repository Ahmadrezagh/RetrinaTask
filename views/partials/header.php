<div class="component-header" style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #667eea;">
    <h3 style="margin: 0; color: #333; font-size: 1.2rem;">
        <?= $this->escape($title ?? 'Component Header') ?>
    </h3>
    <?php if (isset($subtitle)): ?>
        <p style="margin: 0.5rem 0 0 0; color: #666; font-size: 0.9rem;">
            <?= $this->escape($subtitle) ?>
        </p>
    <?php endif; ?>
</div> 