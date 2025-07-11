$(document).ready(function() {
    // When the marking code is selected
    $('#markingCodeSelect').change(function() {
        var customer_id = $(this).val();
        console.log('Selected Marking Code:', customer_id);
        
        if (customer_id) {
            // AJAX request to fetch prices based on the selected marking code
            $.ajax({
                url: '/shipment/api/getCustomerPrice/' + customer_id,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Clear previous options and re-add the placeholder
                    $('#shippingPrice').html('<option value="" selected disabled>---SELECT SHIPMENT---</option>');
                    
                    if (data.length > 0) {
                        // Loop through and add the new options to the select
                        $.each(data, function(index, price) {
                            $('#shippingPrice').append('<option value="' + price.price + '" data-price="' + price.price + '">' + price.price_code + '</option>');
                        });
                    } else {
                        // If no data is found, display a "No Data" message
                        $('#shippingPrice').append('<option value="" disabled>No data available</option>');
                    }

                    // Reinitialize select2 (if using it)
                    $('#shippingPrice').select2().trigger('change');
                },
                error: function() {
                    alert('Error fetching prices');
                }
            });
        }
    });
});
