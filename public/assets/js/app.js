// shared JS helpers

// Send the CSRF token with every fetch() POST:
//   fetch(url, { method: 'POST', headers: csrfHeaders(), body: formData })
function csrfHeaders(extra = {}) {
    const token = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    return { 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest', ...extra };
}

// Filter bars: dropdowns submit immediately; the search box submits on Enter.
// Empty fields are left out of the URL so shared links stay short (?status=active).
document.querySelectorAll('form[data-auto-submit]').forEach((form) => {
    form.addEventListener('submit', () => {
        form.querySelectorAll('input[name], select[name]').forEach((el) => { if (el.value === '') { el.disabled = true; } });
    });
    form.querySelectorAll('select').forEach((sel) => sel.addEventListener('change', () => form.requestSubmit()));
});
