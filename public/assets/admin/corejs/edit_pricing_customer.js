$(document).ready(function() {
    // Fetch the data from the hidden input field (editpricingData)
    var pricingData = JSON.parse($('#editpricingData').val());

    // Check if there is any data
    if (pricingData.length === 0) {
        var noDataRow = `
            <tr id="noDataRow">
                <td colspan="6" class="text-center">No Data Available</td>
            </tr>
        `;
        $('#editTablePricing tbody').append(noDataRow);
    } else {
        pricingData.forEach(function(pricing, index) {

            var row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${pricing.from}</td>
                    <td>${pricing.to}</td>
                    <td>${pricing.service}</td>
                    <td>${pricing.price}</td>
                    <td><button type="button" class="btn btn-danger btn-sm delete-row">Delete</button></td>
                </tr>
            `;
            $('#editTablePricing tbody').append(row);
        });
    }

    // Handle row deletion
    $(document).on('click', '.delete-row', function() {
        var row = $(this).closest('tr');
        if (confirm("Are you sure you want to delete this row?")) {
            row.remove();
            updateEditPricingData();
        }

        // Check if the table becomes empty after removal
        if ($('#editTablePricing tbody tr').length === 0) {
            var noDataRow = `
                <tr id="noDataRow">
                    <td colspan="6" class="text-center">No Data Available</td>
                </tr>
            `;
            $('#editTablePricing tbody').append(noDataRow);
        }
    });

    // Function to update the hidden input with the table data
    function updateEditPricingData() {
        var tableData = [];
        $('#editTablePricing tbody tr').each(function() {
            if ($(this).attr('id') === 'noDataRow') return; // Skip the "No Data" row

            var row = {
                from: $(this).find('td:nth-child(2)').text(),
                to: $(this).find('td:nth-child(3)').text(),
                service: $(this).find('td:nth-child(4)').text(),
                price: $(this).find('td:nth-child(5)').text()
            };
            tableData.push(row);
        });

        $('#editpricingData').val(JSON.stringify(tableData));

        // If the table is empty, show the "No Data" row
        if (tableData.length === 0) {
            var noDataRow = `
                <tr id="noDataRow">
                    <td colspan="6" class="text-center">No Data Available</td>
                </tr>
            `;
            $('#editTablePricing tbody').append(noDataRow);
        } else {
            $('#noDataRow').remove(); // Remove the "No Data" row if data exists
        }
    }

    // When the Add button is clicked
    $('#addEditPricing').click(function() {
        // Get form input values
        var from = $('select[name="from"]').val();
        var to = $('select[name="to"]').val();
        var service = $('select[name="service"]').val();
        var price = $('input[name="price"]').val();

        // Check if all fields are filled
        if (!from || !to || !service || !price) {
            alert('Please fill in all fields add edit.');
            return; // Exit if fields are empty
        }

        // Determine service name (Air or Sea)
        var serviceName = (service == 1) ? 'Air' : 'Sea';

        // Add a new row to the table
        var row = `
            <tr>
                <td>${$('#editTablePricing tbody tr').length + 1}</td>
                <td>${from}</td>
                <td>${to}</td>
                <td>${serviceName}</td>
                <td>${price}</td>
                <td><button type="button" class="btn btn-danger btn-sm delete-row">Delete</button></td>
            </tr>
        `;
        $('#editTablePricing tbody').append(row);

        // Add the new data to the pricingData array
        pricingData.push({ from: from, to: to, service: service, price: price });
        
        // Update the hidden input with the latest pricing data
        updateEditPricingData();

        // Clear the form inputs
        $('select[name="from"]').val('');
        $('select[name="to"]').val('');
        $('select[name="service"]').val('');
        $('input[name="price"]').val('');
        
        // Remove "No Data Available" row if it exists
        $('#noDataRow').remove();
    });

    $('#EditcreateButton').click(function(e) {
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
        var EditpricingDataRaw = $('#editpricingData').val();
        var editpricingData = [];

        // Check if pricing data is valid
        if (EditpricingDataRaw) {
            try {
                editpricingData = JSON.parse(EditpricingDataRaw);
            } catch (e) {
                console.error('Invalid JSON data:', e);
                editpricingData = [];
            }
        }

        // Check if pricing data exists before submitting
        if (editpricingData.length === 0) {
            alert("[Price-Edit Customer Page] Please add at least one price row.");
            return false;  // Prevent form submission
        }

        // Update the hidden input before submitting
        updateEditPricingData();

        // Use setTimeout to delay the form submission by 1 second
        setTimeout(function() {
            console.log("Form will now be submitted after delay...");
            $('form')[0].submit();  // Submit the form to the backend
        }, 1000);  // 1000ms delay to ensure everything is in place

        return false;  // Prevent default form submission behavior (e.g., page reload)
    });

});
