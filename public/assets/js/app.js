// shared JS helpers

// Send the CSRF token with every fetch() POST:
//   fetch(url, { method: 'POST', headers: csrfHeaders(), body: formData })
function csrfHeaders(extra = {}) {
    const token = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    return { 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest', ...extra };
}
