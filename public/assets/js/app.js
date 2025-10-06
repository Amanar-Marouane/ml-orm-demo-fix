document.addEventListener('DOMContentLoaded', function () {
    // Initialize accordion functionality (keep existing code)
    const accordionButtons = document.querySelectorAll('.accordion-button');

    accordionButtons.forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            // Get the target collapse element
            const targetId = this.getAttribute('data-bs-target') || this.getAttribute('data-target');
            const targetElement = document.querySelector(targetId);

            if (!targetElement) return;

            // Check if currently collapsed
            const isCollapsed = !targetElement.classList.contains('show');

            // If we're opening this one, close all others first
            if (isCollapsed) {
                // Close all other open accordions in this group
                const parentId = targetElement.getAttribute('data-bs-parent') ||
                    targetElement.getAttribute('data-parent');

                if (parentId) {
                    const parent = document.querySelector(parentId);
                    if (parent) {
                        const openItems = parent.querySelectorAll('.accordion-collapse.show');
                        openItems.forEach(function (item) {
                            item.classList.remove('show');

                            // Find and update the associated button
                            const itemId = item.id;
                            const itemButton = document.querySelector('[data-bs-target="#' + itemId + '"], [data-target="#' + itemId + '"]');
                            if (itemButton) {
                                itemButton.classList.add('collapsed');
                                itemButton.setAttribute('aria-expanded', 'false');
                            }
                        });
                    }
                }

                // Open this one
                targetElement.classList.add('show');
                this.classList.remove('collapsed');
                this.setAttribute('aria-expanded', 'true');
            } else {
                // Close this one
                targetElement.classList.remove('show');
                this.classList.add('collapsed');
                this.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // Also try to use Bootstrap's native collapse if available (keep existing code)
    if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
        const collapseElements = document.querySelectorAll('.accordion-collapse');
        collapseElements.forEach(function (collapseEl) {
            new bootstrap.Collapse(collapseEl, {
                toggle: false
            });
        });
    } else if (typeof $ !== 'undefined' && $.fn && $.fn.collapse) {
        $('.accordion-collapse').collapse({
            toggle: false
        });
    }

    // Add React-like loading animation for refresh button
    const refreshButton = document.querySelector('a[href="/"]');
    if (refreshButton) {
        refreshButton.addEventListener('click', function (e) {
            const icon = this.querySelector('.fa-redo');
            if (icon) {
                icon.classList.add('fa-spin');
                this.classList.add('disabled');
            }
        });
    }

    // Animate progress bars on page load
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const finalWidth = bar.style.width;
        bar.style.width = '0%';

        setTimeout(() => {
            bar.style.transition = 'width 1s ease-out';
            bar.style.width = finalWidth;
        }, 300);
    });

    console.log('MonkeysLegion Dashboard initialized (Dark Mode Only)');
});
console.log('Custom app.js loaded');