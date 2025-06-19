
document.addEventListener('DOMContentLoaded', () => {
    const packageForm = document.getElementById('packageForm');
    const packageModal = $('#packageModal');
    const packagesTableBody = document.querySelector('#packagesTable tbody');
    const totalDisplay = document.getElementById('totalPrice');
    const totalInput = document.getElementById('totalInput');
    const totalHiddenInput = document.getElementById('total_price');
    const packagesJsonInput = document.getElementById('packages_json');
    const shippingPriceSelect = document.getElementById('shippingPrice');
    const editTotalBtn = document.getElementById('editTotalBtn');
    const consolidationCheckbox = document.getElementById('consolidationCheckbox');


    let packages = [];
    let editingIndex = null;

    packageForm.addEventListener('submit', handleAddOrUpdatePackage);
    packagesTableBody.addEventListener('click', handleDynamicButtons);
    editTotalBtn.addEventListener('click', handleEditTotalClick);
    ['dimension_p', 'dimension_l', 'dimension_t'].forEach(id =>
        document.getElementById(id).addEventListener('input', updateVolumeAuto)
    );

    totalInput.addEventListener('blur', handleTotalInputBlur);

    // Prevent form submission when pressing Enter inside totalInput
    totalInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // prevent form submission
            this.blur();        // optional: trigger blur to auto-save
        }
    });

    shippingPriceSelect.addEventListener('change', () => {
        console.log("Changed Price Code:", shippingPriceSelect.value);
        console.log("Selected Price Per Kg:", getPricePerKg());
        renderPackagesTable();
    });


    function getPricePerKg() {
        const selectedOption = shippingPriceSelect.options[shippingPriceSelect.selectedIndex];
        return parseInt(selectedOption.dataset.price || '0', 10);
    }

    function calculateUsedWeight(volume, realWeight, consolidationEnabled = false) {
        return consolidationEnabled
            ? Math.ceil(Math.max(volume, realWeight))
            : Math.ceil(volume + realWeight);
    }

    function calculateVolume(p, l, t) {
        return (p * l * t) / 6000;
    }

    function renderPackagesTable() {
        packagesTableBody.innerHTML = '';

        if (packages.length === 0) {
            packagesTableBody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">No data available in table</td></tr>`;
            totalDisplay.textContent = 'Rp 0';
            totalHiddenInput.value = 0;
            return;
        }

        const pricePerKg = getPricePerKg();
        const updatedPackages = [];
        let total = 0;

        packages.forEach((pkg, i) => {
            const volume = calculateVolume(pkg.p, pkg.l, pkg.t);
            const usedWeight = calculateUsedWeight(volume, pkg.real_weight);
            const rowTotal = usedWeight * pricePerKg;
            total += rowTotal;

            updatedPackages.push({ ...pkg, volume, used_weight: usedWeight });

            packagesTableBody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td>${i + 1}</td>
                    <td>${pkg.description}</td>
                    <td>${pkg.p} x ${pkg.l} x ${pkg.t}</td>
                    <td>${volume.toFixed(2)}</td>
                    <td>${pkg.real_weight}</td>
                    <td>${usedWeight}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-warning" data-action="edit" data-index="${i}">Edit</button>
                        <button type="button" class="btn btn-sm btn-danger" data-action="delete" data-index="${i}">Delete</button>
                    </td>
                </tr>
            `);
        });

        // Check manual override
        const override = !totalInput.classList.contains('d-none') && !isNaN(parseInt(totalInput.value, 10))
            ? parseInt(totalInput.value, 10)
            : total;

        totalDisplay.textContent = `Rp ${override.toLocaleString()}`;
        totalHiddenInput.value = override;
        packagesJsonInput.value = JSON.stringify(updatedPackages);

        document.getElementById('override_total').value = "0";
    }

    function resetForm() {
        packageForm.reset();
        editingIndex = null;
    }

    packageModal.on('hidden.bs.modal', function () {
        resetForm();
        editingIndex = null;
        document.getElementById('dimension_v').value = '';
    });

    function handleAddOrUpdatePackage(e) {
        e.preventDefault();
        const formData = new FormData(packageForm);

        const pkg = {
            description: formData.get('description'),
            p: parseFloat(formData.get('dimension_p')),
            l: parseFloat(formData.get('dimension_l')),
            t: parseFloat(formData.get('dimension_t')),
            real_weight: parseFloat(formData.get('realWeight')),
        };

        if (editingIndex !== null) {
            packages[editingIndex] = pkg;
        } else {
            packages.push(pkg);
        }

        packageModal.modal('hide');
        renderPackagesTable();
        resetForm();
    }

    function handleEdit(index) {
        const pkg = packages[index];
        document.getElementById('inputDescription').value = pkg.description;
        document.getElementById('dimension_p').value = pkg.p;
        document.getElementById('dimension_l').value = pkg.l;
        document.getElementById('dimension_t').value = pkg.t;
        document.getElementById('dimension_v').value = pkg.v;
        document.getElementById('realWeight').value = pkg.real_weight;

        const volume = calculateVolume(pkg.p, pkg.l, pkg.t);
        document.getElementById('dimension_v').value = volume.toFixed(2);

        editingIndex = index;
        packageModal.modal('show');
    }

    function handleDelete(index) {
        if (confirm('Delete this package?')) {
            packages.splice(index, 1);
            renderPackagesTable();
        }
    }

    function handleDynamicButtons(e) {
        const button = e.target.closest('button'); // safer way
        if (!button) return;

        const action = button.dataset.action;
        const index = parseInt(button.dataset.index, 10);

        if (action === 'edit') {
            handleEdit(index);
        } else if (action === 'delete') {
            handleDelete(index);
        }
    }


    function handleEditTotalClick() {
        totalInput.value = totalHiddenInput.value;
        totalInput.classList.remove('d-none');
        totalDisplay.classList.add('d-none');
        totalInput.focus();
    }

    function handleTotalInputBlur() {
        const value = parseFloat(totalInput.value) || 0;
        totalDisplay.textContent = `Rp ${value.toLocaleString('id-ID')}`;
        totalHiddenInput.value = value;

        document.getElementById('override_total').value = "1";
        totalInput.classList.add('d-none');
        totalDisplay.classList.remove('d-none');
    }


    function updateVolumeAuto() {
        const p = parseFloat(document.getElementById('dimension_p').value) || 0;
        const l = parseFloat(document.getElementById('dimension_l').value) || 0;
        const t = parseFloat(document.getElementById('dimension_t').value) || 0;
        const volumeField = document.getElementById('dimension_v');

        if (p && l && t) {
            volumeField.value = calculateVolume(p, l, t).toFixed(2);
        } else {
            volumeField.value = '';
        }
    }

    if (consolidationCheckbox) {
        consolidationCheckbox.addEventListener('change', () => {
            renderPackagesTable(); // or refreshTable(), depending on your code
        });
    }

    function calculateUsedWeight(volume, realWeight) {
        const consolidationCheckbox = document.getElementById('consolidationCheckbox');
        const consolidationEnabled = consolidationCheckbox?.checked;

        return consolidationEnabled
            ? Math.ceil(Math.max(volume, realWeight))
            : Math.ceil(volume + realWeight);
    }

    window.formatTextInput = function (el) {
        el.value = el.value
            .toLowerCase()
            .replace(/\b\w/g, char => char.toUpperCase())
            .trim()
            .replace(/\s+/g, ' ');
    };
});
