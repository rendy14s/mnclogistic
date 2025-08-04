$(document).ready(function() {
    const defaultPriceId = $('#defaultPriceID').val();
    console.log('Default Price ID:', defaultPriceId);

    $('.select2').select2();

    function loadSecondSelect(customerId) {
        if (!customerId) return;

        // Your AJAX or logic to populate second select
        console.log('Load second select for customer:', customerId);
        // e.g., AJAX to get shipping prices and populate #shippingPrice
        console.log('Selected Marking Code:', customerId);
        
        if (customerId) {
            // AJAX request to fetch prices based on the selected marking code
            $.ajax({
                url: '/shipment/api/getCustomerPrice/' + customerId,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    const $select = $('#shippingPrice');

                    $select.empty();  // Clear all options
                    // Clear previous options and re-add the placeholder
                    $('#shippingPrice').html('<option value="" selected disabled>---SELECT SHIPMENT---</option>');
                     $('#defaultPriceID').val('');  // Reset hidden input
                    
                    if (data.length > 0) {
                        // Loop through and add the new options to the select
                        $.each(data, function(index, price) {
                            $('#shippingPrice').append('<option value="' + price.id + '" data-price="' + price.price + '"  data-service="' + price.service + '">' + price.price_code + '</option>');
                        });

                        if(defaultPriceId) {
                            $('#shippingPrice').val(defaultPriceId).trigger('change');
                            $('#defaultPriceID').val(defaultPriceId); // Keep in sync
                        }
                        
                    } else {
                        // If no data is found, display a "No Data" message
                        $('#shippingPrice').append('<option value="" disabled>No data available</option>');
                    }

                    // Reinitialize select2 (if using it)
                    $('#shippingPrice').select2().trigger('change');
                },
                error: function(e) {
                    alert('Error fetching prices', e.responseText);
                }
            });
        }
    }

    // When user selects new option
    $('#markingCodeSelect').on('select2:select', function() {
        const selectedVal = $(this).val();
    
        loadSecondSelect(selectedVal);
    });

    // On page load, check if there is a pre-selected value
    const preSelected = $('#markingCodeSelect').val();
    console.log('Pre-selected Marking Code:', preSelected);
    if (preSelected) {
        loadSecondSelect(preSelected);
    }
});