(function () {
    const form = document.getElementById('login-form');
    const identifier = document.getElementById('identifier');
    const password = document.getElementById('password');
    const toggle = document.getElementById('toggle-password');

    function setError(id, message) {
        const el = document.querySelector(`small[data-error-for="${id}"]`);
        if (el) el.textContent = message || '';
    }

    function validateIdentifier(value) {
        return value.trim().length >= 3;
    }

    function validatePassword(value) {
        return value.trim().length >= 6;
    }

    // Toggle show/hide password
    toggle?.addEventListener('click', () => {
        const isHidden = password.type === 'password';
        password.type = isHidden ? 'text' : 'password';
        toggle.textContent = isHidden ? 'Hide' : 'Show';
        toggle.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
    });

    // Realtime validation hints
    identifier?.addEventListener('blur', () => {
        setError('identifier', validateIdentifier(identifier.value) ? '' : 'Enter a valid email, phone, or username.');
    });
    password?.addEventListener('blur', () => {
        setError('password', validatePassword(password.value) ? '' : 'Password must be at least 6 characters.');
    });

    form?.addEventListener('submit', (e) => {
        e.preventDefault();
        const idOk = validateIdentifier(identifier.value);
        const pwOk = validatePassword(password.value);

        setError('identifier', idOk ? '' : 'Enter a valid email, phone, or username.');
        setError('password', pwOk ? '' : 'Password must be at least 6 characters.');

        if (!idOk || !pwOk) return;

        // Replace with real login request (AJAX or form POST)
        // Example payload:
        const payload = {
            identifier: identifier.value.trim(),
            password: password.value,
        };
        console.log('Login payload ready:', payload);

        // Demo feedback
        alert('Login form submitted. Wire this up to your backend.');
    });
})();
