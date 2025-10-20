
document.addEventListener('DOMContentLoaded', () => {

    const packageForm = document.getElementById('packageForm');
    const packageModal = $('#packageModal');
    const packagesTableBody = document.querySelector('#packagesTable tbody');
    const totalDisplay = document.getElementById('totalPrice');
    const totalPriceHidden = document.getElementById('total_price');
    const totalInput = document.getElementById('totalInput');
    const totalWeightHiddenInput = document.getElementById('total_weight');
    const packagesJsonInput = document.getElementById('packages_json');
    const editTotalBtn = document.getElementById('editTotalBtn');
    const consolidationCheckbox = document.getElementById('consolidationCheckbox');
    const form = document.getElementById('shippingForm');
    const totalUsedWeightDisplay = document.getElementById('totalUsedWeight');



    let packages = [];
    try {
        const json = packagesJsonInput.value;
        packages = json ? JSON.parse(json) : [];
    } catch (err) {
        console.error('Invalid packages_json:', err);
        packages = [];
    }


    let editingIndex = null;

    packageForm.addEventListener('submit', handleAddOrUpdatePackage);
    packagesTableBody.addEventListener('click', handleDynamicButtons);
    editTotalBtn.addEventListener('click', handleEditTotalClick);

    ['dimension_p', 'dimension_l', 'dimension_t'].forEach(id =>
        document.getElementById(id).addEventListener('input', updateVolumeAuto)
    );


    form.addEventListener('submit', function(e) {
        const packagesJson = document.getElementById('packages_json').value;
        if (!packagesJson || packagesJson.trim() === '[]') {
            e.preventDefault(); // cancel form submission
            showAlert('Please add at least one package before submitting.', 'danger');
            return;
        }

        // allow form submission if validation passes
    });

    totalInput.addEventListener('blur', handleTotalInputBlur);

    // Prevent form submission when pressing Enter inside totalInput
    totalInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // prevent form submission
            this.blur();        // optional: trigger blur to auto-save
        }
    });

    function getPricePerKg() {
    const shippingPriceSelect = document.getElementById('shippingPrice');
    const selectedOption = shippingPriceSelect.options[shippingPriceSelect.selectedIndex];
    
    if (!selectedOption || !selectedOption.dataset.price) {
        // No option selected or no data-price attribute, return 0 fallback
        return 0;
    }

    const price = selectedOption.dataset.price;
    return parseInt(price, 10) || 0;
}

    function calculateVolume(p, l, t) {
        var selectedCountry = $('#country').val();
        console.log('Selected Country For Calculate Volume :', selectedCountry);

        let serviceValue = document.getElementById('service').value;

        // Configuration for volume divisors
        const volumeRules = {
            default: { 
                Sea: 5000, 
                Air: 6000,
                LCL: 5000,          // ✅ new
                "Cargo Service": 5000 // ✅ new
            },
            KR: { Sea: 5000, Air: 5000 },
            KP: { Sea: 5000, Air: 5000 },
            JP: { Sea: 5000, Air: 5000 }
        };

        // Pick rules: if country exists use it, otherwise use default
        const rules = volumeRules[selectedCountry] || volumeRules.default;

        // Ensure service exists in rules, then calculate
        if (rules[serviceValue]) {
            return p * l * t / rules[serviceValue];
        }

        console.warn('Unknown service type:', serviceValue);
        return null; // or 0 if you prefer
    }


    function renderPackagesTable() {
        packagesTableBody.innerHTML = '';

        if (packages.length === 0) {
            packagesTableBody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">No data available in table</td></tr>`;
            totalDisplay.textContent = 'Rp 0';
            totalWeightHiddenInput.value = 0;
            totalUsedWeightDisplay.textContent = '0';
            return;
        }

        const pricePerKg = getPricePerKg();
        const consolidationCheckbox = document.getElementById('consolidationCheckbox');
        const consolidationEnabled = consolidationCheckbox?.checked; // checked = NO consolidation

        let total = 0;
        let totalUsedWeight = 0;
        let totalPackagesWeight = 0;
        const updatedPackages = [];

        packages.forEach((pkg, i) => {
            const volume = calculateVolume(pkg.p, pkg.l, pkg.t);
            const usedWeight = calculateUsedWeight(volume, pkg.real_weight, consolidationEnabled);

            if (consolidationEnabled) {
                // Tanpa konsolidasi → hitung per paket langsung
                totalUsedWeight += usedWeight;
            } else {
                // Dengan konsolidasi → jumlah total, bulatkan nanti
                totalPackagesWeight += usedWeight;
            }

            updatedPackages.push({ ...pkg, volume, used_weight: usedWeight });

            packagesTableBody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td>${i + 1}</td>
                    <td>${pkg.description}</td>
                    <td>${pkg.p} x ${pkg.l} x ${pkg.t}</td>
                    <td>${volume.toFixed(2)}</td>
                    <td>${pkg.real_weight}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-warning" data-action="edit" data-index="${i}">Edit</button>
                        <button type="button" class="btn btn-sm btn-danger" data-action="delete" data-index="${i}">Delete</button>
                    </td>
                </tr>
            `);
        });

        if (!consolidationEnabled) {
            // Konsolidasi aktif → hitung total semua, lalu pembulatan custom
            const cleanWeight = parseFloat(totalPackagesWeight.toFixed(2));
            const decimalPart = cleanWeight - Math.floor(cleanWeight);

            // Custom rule: naik jika desimal >= 0.1
            totalUsedWeight = decimalPart >= 0.1
                ? Math.ceil(cleanWeight)
                : Math.floor(cleanWeight);
        }

        const override =
            !totalInput.classList.contains('d-none') && !isNaN(parseInt(totalInput.value, 10))
                ? parseInt(totalInput.value, 10)
                : totalUsedWeight;

        total = override * pricePerKg;

        totalDisplay.textContent = `Rp ${total.toLocaleString()}`;
        totalPriceHidden.value = total;
        totalWeightHiddenInput.value = override;
        packagesJsonInput.value = JSON.stringify(updatedPackages);

        totalUsedWeightDisplay.textContent = totalUsedWeight.toFixed(2);
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
        totalInput.value = totalPriceHidden.value;
        totalInput.classList.remove('d-none');
        totalDisplay.classList.add('d-none');
        totalInput.focus();
    }

    function handleTotalInputBlur() {
        const value = parseFloat(totalInput.value) || 0;
        totalDisplay.textContent = `Rp ${value.toLocaleString('id-ID')}`;
        totalPriceHidden.value = value;

        document.getElementById('override_total').value = "1";
        totalInput.classList.add('d-none');
        totalDisplay.classList.remove('d-none');
    }


    function updateVolumeAuto() {
        console.log('Updating volume automatically');
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


    // Fungsi hitung berat dengan logika pembulatan sesuai mode
    function calculateUsedWeight(volume, realWeight, consolidationEnabled) {
        const baseWeight = Math.max(volume, realWeight);
        const clean = parseFloat(baseWeight.toFixed(2));

        if (consolidationEnabled) {
            // Tanpa konsolidasi (checkbox dicentang)
            return Math.ceil(clean); // per paket dibulatkan ke atas
        }

        // Dengan konsolidasi → jangan bulatkan dulu, nanti di total baru dibulatkan
        return clean;
    }

    // Inside your success callback after options are populated
    $('#shippingPrice').off('change').on('change', function () {
        const selectedOption = $(this).find('option:selected');
        const selectedPricePerKg = selectedOption.data('price');
        const selectedService = selectedOption.data('service');
        const selectedCountry = selectedOption.data('country');
        const selectedPriceID = selectedOption.val();


        // Set the data-price into the hidden input
        $('#priceKg').val(selectedPricePerKg);
        $('#service').val(selectedService);
        $('#country').val(selectedCountry);
        $('#defaultPriceID').val(selectedPriceID);

        console.log('Selected price_kg :', selectedPricePerKg); // Debug
        console.log('Selected service :', selectedService); // Debug
        console.log('Selected Country :', selectedCountry); // Debug
        console.log('Selected price_id :', selectedPriceID); // Debug

        console.log("Selected Price Per Kg:", getPricePerKg());
        renderPackagesTable();
    });

});
