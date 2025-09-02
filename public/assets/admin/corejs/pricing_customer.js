$(document).ready(function() {
    // Retrieving customer data from localStorage
    if (localStorage.getItem('customerData')) {
        var customerData = JSON.parse(localStorage.getItem('customerData'));
        $('#markingCode').text(customerData.markingCode);
        $('#customerName').text(customerData.customerName);
        $('#phoneNumber').text(customerData.phoneNumber);
        $('#address').text(customerData.address);
    } else {
        console.log("No customer data found in localStorage.");
        $('#customerDetails').html('<p>No customer data found.</p>');
    }

    // Initialize DataTables
    var table = $('#tablepriceCustomer').DataTable();
    var pricingData = [];

    // Add data to the table when the 'Add' button is clicked
    $('#addPricing').click(function() {
        var from = $('select[name="from"]').val();
        var to = $('select[name="to"]').val();
        var service = $('select[name="service"]').val();
        var price = $('input[name="price"]').val();

        if (!from || !to || !service || !price) {
            alert("Please fill out all fields.");
            return;
        }

        // service map
        const serviceMap = {
            1: 'Air',
            2: 'Sea',
            3: 'LCL',
            4: 'Cargo Service'
        };


        table.row.add([
            '#',  // Placeholder for row number
            from,
            to,
            serviceMap[service] || 'Unknown', // fallback if invalid value
            price,
            '<button type="button" class="btn btn-danger btn-delete">Delete</button>'
        ]).draw();

        pricingData.push({ from: from, to: to, service: service, price: price });
        $('#pricingData').val(JSON.stringify(pricingData));
        $('form')[0].reset();
        $('form select').val('');
    });

    // Update hidden pricing data field after row deletion
    function updatePricingData() {
        var data = [];
        table.rows().every(function() {
            var rowData = this.data();
            var row = {
                from: rowData[1],
                to: rowData[2],
                service: rowData[3],
                price: rowData[4]
            };
            data.push(row);
        });
        $('#pricingData').val(JSON.stringify(data));  // Update hidden input field
    }

    // Delete row from table
    $('#tablepriceCustomer').on('click', '.btn-delete', function() {
        var row = table.row($(this).closest('tr'));
        var rowData = row.data();

        // Remove the row from the table
        row.remove().draw();

        // Remove the corresponding data from pricingData
        var indexToRemove = pricingData.findIndex(function(item) {
            return item.from === rowData[1] && item.to === rowData[2] &&
                   item.service === (rowData[3] === 'Air' ? 1 : 2) && item.price === rowData[4];
        });

        if (indexToRemove !== -1) {
            pricingData.splice(indexToRemove, 1);  // Remove from pricingData array
        }

        // Update the hidden input field with the new pricing data
        updatePricingData();
    });


    // Handle form submission when Create button is clicked
    // Handle form submission when Create button is clicked
    $('#createButton').click(function(e) {
        e.preventDefault();  // Prevent default form submission
        e.stopPropagation();  // Stop event bubbling

        console.log("Create button clicked");

        // Get customer data from localStorage and append it as hidden inputs
        var customerData = localStorage.getItem('customerData');
        if (customerData) {
            customerData = JSON.parse(customerData);  // Parse the JSON data from localStorage

            // Create hidden input fields for customer data
            var markingCodeInput = $('<input>').attr({
                type: 'hidden',
                name: 'markingCode',
                value: customerData.markingCode
            });
            var customerNameInput = $('<input>').attr({
                type: 'hidden',
                name: 'customerName',
                value: customerData.customerName
            });
            var customerPhoneInput = $('<input>').attr({
                type: 'hidden',
                name: 'customerPhone',
                value: customerData.phoneNumber
            });
            var customerAddressInput = $('<input>').attr({
                type: 'hidden',
                name: 'customerAddress',
                value: customerData.address
            });

            // Append the hidden fields to the form
            $('form').append(markingCodeInput, customerNameInput, customerPhoneInput, customerAddressInput);
        } else {
            console.log('No customer data found in localStorage.');
        }

        // Get pricing data from the hidden input field
        var pricingDataRaw = $('#pricingData').val();
        var pricingData = [];

        // Check if pricing data is valid
        if (pricingDataRaw) {
            try {
                pricingData = JSON.parse(pricingDataRaw);
            } catch (e) {
                console.error('Invalid JSON data:', e);
                pricingData = [];
            }
        }

        // Check if pricing data exists before submitting
        if (pricingData.length === 0) {
            alert("[Price-Add Customer Page] Please add at least one price row.");
            return false;  // Prevent form submission
        }

        // Update the hidden input before submitting
        updatePricingData();

        // Use setTimeout to delay the form submission by 1 second
        setTimeout(function() {
            console.log("Form will now be submitted after delay...");
            $('form')[0].submit();  // Submit the form to the backend
        }, 1000);  // 1000ms delay to ensure everything is in place

        return false;  // Prevent default form submission behavior (e.g., page reload)
    });


});
