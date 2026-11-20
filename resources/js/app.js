const menuToggle = document.querySelector('[data-menu-toggle]');
const publicMenu = document.querySelector('#public-mobile-menu');

menuToggle?.addEventListener('click', () => {
    const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
    menuToggle.setAttribute('aria-expanded', String(!isOpen));
    menuToggle.setAttribute('aria-label', isOpen ? 'Open menu' : 'Close menu');
    menuToggle.querySelector('[data-menu-icon-open]')?.classList.toggle('hidden', !isOpen);
    menuToggle.querySelector('[data-menu-icon-close]')?.classList.toggle('hidden', isOpen);
    publicMenu?.classList.toggle('hidden', isOpen);
    publicMenu?.classList.toggle('flex', !isOpen);
});

const adminMenuToggle = document.querySelector('[data-admin-menu-toggle]');
const adminSidebar = document.querySelector('#admin-sidebar');

adminMenuToggle?.addEventListener('click', () => {
    const isOpen = adminMenuToggle.getAttribute('aria-expanded') === 'true';
    adminMenuToggle.setAttribute('aria-expanded', String(!isOpen));
    adminSidebar?.classList.toggle('d-none', isOpen);
    adminSidebar?.classList.toggle('d-block', !isOpen);
});

const confirmationDialog = document.querySelector('[data-confirmation-dialog]');

if (confirmationDialog) {
    const message = confirmationDialog.querySelector('[data-confirmation-message]');
    const confirmButton = confirmationDialog.querySelector('[data-confirmation-confirm]');
    let activeForm = null;
    let returnFocus = null;

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-confirm]');
        if (!trigger) {
            return;
        }

        event.preventDefault();
        activeForm = trigger.closest('form');
        returnFocus = trigger;
        message.textContent = trigger.dataset.confirm;
        confirmButton.textContent = trigger.dataset.confirmLabel || 'Confirm';
        confirmationDialog.showModal();
    });

    confirmButton.addEventListener('click', () => {
        if (!activeForm || activeForm.dataset.submitting === 'true') {
            return;
        }

        activeForm.dataset.submitting = 'true';
        confirmButton.disabled = true;
        activeForm.submit();
    });

    confirmationDialog.addEventListener('close', () => {
        confirmButton.disabled = false;
        activeForm = null;
        returnFocus?.focus();
    });

    confirmationDialog.querySelector('[data-confirmation-cancel]').addEventListener('click', () => {
        confirmationDialog.close();
    });
}
