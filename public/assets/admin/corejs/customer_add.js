document.getElementById('createCustomerBtn').addEventListener('click', function(event) {
    event.preventDefault();  // Prevent default form submission

    // Collect form data
    var markingCode = document.getElementById('inputMarkingCode').value;
    var customerName = document.getElementById('inputCustomerName').value;
    var phoneNumber = document.getElementById('inputPhoneNumber').value;
    var address = document.getElementById('InputAddress').value;

    console.log(markingCode, customerName, phoneNumber, address); // Log form values

    // Get the error message container
    var errorMessageContainer = document.getElementById('error-message');
    console.log(errorMessageContainer); // Log the container to check if it exists

    // Validation flag
    var isValid = true;
    
    // Reset the error message
    if (errorMessageContainer) {
        errorMessageContainer.innerText = ''; 
    }

    // Validate all fields
    if (!markingCode || !customerName || !phoneNumber || !address) {
        isValid = false;
        if (errorMessageContainer) {
            errorMessageContainer.innerText = 'Please fill out all fields.'; // Show validation error
        }
    }

    if (isValid) {
        // Create an object to hold the customer data
        var customerData = {
            markingCode: markingCode,
            customerName: customerName,
            phoneNumber: phoneNumber,
            address: address
        };

        // First, delete any existing customer data in localStorage
        localStorage.removeItem('customerData'); 

        // Now, save the new customer data to localStorage
        localStorage.setItem('customerData', JSON.stringify(customerData));

        var redirectUrl = document.getElementById('createCustomerBtn').getAttribute('data-url');
        var encodedRedirectUrl = encodeURI(redirectUrl); // Ensure the URL is properly encoded

        // Redirect to the encoded URL
        window.location.href = encodedRedirectUrl;
    }
});
