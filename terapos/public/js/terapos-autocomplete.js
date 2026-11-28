(function () {
    'use strict';

    function normalize(value) {
        return String(value || '').toLocaleLowerCase('id-ID').trim();
    }

    function rank(item, term, fields) {
        const values = fields.map(function (field) { return normalize(item[field]); });
        if (values.some(function (value) { return value === term; })) return 0;
        if (values.some(function (value) { return value.startsWith(term); })) return 1;
        if (values.some(function (value) { return value.includes(term); })) return 2;
        return 99;
    }

    function initAutocomplete(input) {
        if (input.dataset.autocompleteReady) return;
        input.dataset.autocompleteReady = 'true';
        let items = [];
        try { items = JSON.parse(input.dataset.autocompleteItems || '[]'); } catch (_) { items = []; }
        const fields = (input.dataset.autocompleteFields || 'code,name').split(',');
        const labelField = input.dataset.autocompleteLabel || 'name';
        const valueField = input.dataset.autocompleteValue || 'id';
        const asyncUrl = input.dataset.autocompleteUrl || '';
        let debounceTimer = null;
        let requestSequence = 0;
        let matches = [];
        let activeIndex = -1;

        const dropdown = document.createElement('div');
        dropdown.className = 'terapos-autocomplete dropdown-menu show w-100';
        dropdown.setAttribute('role', 'listbox');
        dropdown.style.display = 'none';
        dropdown.style.maxHeight = '280px';
        dropdown.style.overflowY = 'auto';
        dropdown.style.zIndex = '1080';
        const wrapper = document.createElement('div');
        wrapper.className = 'position-relative';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        wrapper.appendChild(dropdown);

        function close() {
            dropdown.style.display = 'none';
            activeIndex = -1;
        }

        function render() {
            const term = normalize(input.value);
            if (asyncUrl) {
                window.clearTimeout(debounceTimer);
                const sequence = ++requestSequence;
                dropdown.replaceChildren();
                const loading = document.createElement('button');
                loading.type = 'button';
                loading.className = 'dropdown-item text-muted disabled';
                loading.textContent = term ? 'Memuat rekomendasi...' : 'Memuat rekomendasi awal...';
                dropdown.appendChild(loading);
                dropdown.style.display = 'block';
                debounceTimer = window.setTimeout(function () {
                    const url = new URL(asyncUrl, window.location.origin);
                    url.searchParams.set('q', input.value);
                    if (input.dataset.autocompleteIncludeInactive) url.searchParams.set('include_inactive', input.dataset.autocompleteIncludeInactive);
                    fetch(url.toString(), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(function (response) { if (!response.ok) throw new Error('Autocomplete request failed'); return response.json(); })
                        .then(function (serverItems) {
                            if (sequence !== requestSequence) return;
                            items = serverItems;
                            renderServerMatches(term);
                        })
                        .catch(function () {
                            if (sequence !== requestSequence) return;
                            matches = [];
                            renderEmpty('Gagal memuat rekomendasi');
                        });
                }, 120);
                return;
            }
            matches = items
                .map(function (item) { return { item: item, rank: rank(item, term, fields) }; })
                .filter(function (entry) { return !term || entry.rank < 99; })
                .sort(function (a, b) { return a.rank - b.rank || String(a.item[labelField]).localeCompare(String(b.item[labelField]), 'id'); })
                .slice(0, 10)
                .map(function (entry) { return entry.item; });
            renderMatches();
        }

        function renderServerMatches(term) {
            matches = items
                .map(function (item) { return { item: item, rank: rank(item, term, fields) }; })
                .sort(function (a, b) { return a.rank - b.rank || String(a.item[labelField]).localeCompare(String(b.item[labelField]), 'id'); })
                .slice(0, 10)
                .map(function (entry) { return entry.item; });
            renderMatches();
        }

        function renderEmpty(message) {
            dropdown.replaceChildren();
            const empty = document.createElement('button');
            empty.type = 'button';
            empty.className = 'dropdown-item text-muted disabled';
            empty.textContent = message;
            dropdown.appendChild(empty);
            dropdown.style.display = 'block';
        }

        function renderMatches() {
            dropdown.replaceChildren();
            activeIndex = -1;
            if (!matches.length) {
                const empty = document.createElement('button');
                empty.type = 'button';
                empty.className = 'dropdown-item text-muted disabled';
                empty.textContent = 'Tidak ada rekomendasi ditemukan';
                dropdown.appendChild(empty);
            } else {
                matches.forEach(function (item, index) {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'dropdown-item py-2 text-wrap';
                    button.setAttribute('role', 'option');
                    button.dataset.index = String(index);
                    const code = item.code ? String(item.code) + ' - ' : '';
                    const extra = item.extra ? ' <small class="text-muted d-block">' + item.extra + '</small>' : '';
                    button.innerHTML = '<span>' + code + String(item[labelField] || '') + '</span>' + extra;
                    dropdown.appendChild(button);
                });
            }
            dropdown.style.display = 'block';
        }

        function select(index) {
            const item = matches[index];
            if (!item) return;
            if (input.dataset.autocompleteTarget) {
                const target = document.querySelector(input.dataset.autocompleteTarget);
                if (target) target.value = item[valueField];
            }
            input.value = item[labelField] || item.code || '';
            input.dispatchEvent(new CustomEvent('teraposautocomplete:select', { detail: item }));
            close();
        }

        input.addEventListener('focus', render);
        input.addEventListener('click', render);
        input.addEventListener('input', render);
        input.addEventListener('keydown', function (event) {
            const buttons = Array.from(dropdown.querySelectorAll('[role="option"]'));
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                activeIndex = Math.min(activeIndex + 1, buttons.length - 1);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                activeIndex = Math.max(activeIndex - 1, 0);
            } else if (event.key === 'Enter' && dropdown.style.display !== 'none' && matches.length) {
                event.preventDefault();
                select(activeIndex >= 0 ? activeIndex : 0);
                return;
            } else if (event.key === 'Escape') {
                close();
                return;
            } else {
                return;
            }
            buttons.forEach(function (button, index) { button.classList.toggle('active', index === activeIndex); });
        });
        dropdown.addEventListener('mousedown', function (event) {
            const button = event.target.closest('[role="option"]');
            if (button) {
                event.preventDefault();
                select(Number(button.dataset.index));
            }
        });
        document.addEventListener('mousedown', function (event) {
            if (!wrapper.contains(event.target)) close();
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-terapos-autocomplete]').forEach(initAutocomplete);
    });

    window.TeraposAutocomplete = { init: initAutocomplete };
})();
