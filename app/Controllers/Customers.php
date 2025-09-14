<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCCustomer;
use App\Models\MNCConstantCity;
use App\Models\MNCConstantCountry;
use App\Models\MNCCustomerPrice;

class Customers extends BaseController
{
    public function index()
    {
        //
        $customerModel = new MNCCustomer();
        $data['customers'] = $customerModel->where('status', 1)->findAll();
        
        return view('admin/pages/customers/index', $data);
    }

    public function form_add()
    {
        //

        return view('admin/pages/customers/create/index');
    }

    public function form_add_pricing()
    {
        $countryModel = new MNCConstantCountry();
        $cityModel = new MNCConstantCity();

        $data['countries'] = $countryModel->findAll();
        $data['citys'] = $cityModel->findAll();

        return view('admin/pages/customers/create/pricing', $data);
    }

    public function create()
    {
        // dd($this->request->getPost());exit;
        $customerModel = new MNCCustomer();
        $customerPricing = new MNCCustomerPrice();

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Prepare data Customer
            $dataCustomer = [
                'marking_code'      => $this->request->getPost('markingCode'),
                'customer_name'     => $this->request->getPost('customerName'),
                'phone_number'      => $this->request->getPost('customerPhone'),
                'address'           => $this->request->getPost('customerAddress'),
                'status'            => '1'
            ];

            // Insert customer data
            $customerModel->insert($dataCustomer);
            $customerId = $customerModel->insertID(); // Get the last inserted ID

            if($customerId) {
                // Prepare data for pricing
                $prices = json_decode($this->request->getPost('pricingData'), true);
                foreach ($prices as $price) {
                    // Prepare pricing data
                    $customerPricing->insert([
                        'customer_id' => $customerId,
                        'price_code'      => 'MNC-' . $price['from'] . '-' . $price['to'] . '-' . $price['service'],
                        'from' => $price['from'],
                        'to' => $price['to'],
                        'service' => $price['service'],
                        'price' => $price['price']
                    ]);
                }
            } else {
                // If customer insertion fails, throw an exception
                throw new \Exception('Failed to create customer.');
            }

            $db->transComplete(); // COMMIT TRANSACTION

            if ($db->transStatus() === false) {
                log_message('error', print_r($dataCustomer, true));
                log_message('error', print_r($customerModel->errors(), true));
                throw new \Exception("Transaction failed");
            }

            return redirect()->to('/customers')->with('success', 'Shipping created successfully.');
        } catch (\Exception $e) {
            $db->transRollback(); // ROLLBACK TRANSACTION
            log_message('error', 'Error creating customer: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create customer: ' . $e->getMessage());
        }

        return redirect()->to('/customers')->with('message', 'Customer created successfully!');
    }

    public function edit($id) {
        $customerModel = new MNCCustomer();
        $customers = $customerModel->find($id); // Fetch customers by ID

        if (!$customers) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("customers not found.");
        }

        if ($this->request->getMethod() === 'POST') {
            // Handle form submission

            $data = [
                'marking_code'    => $this->request->getPost('marking_code'),
                'customer_name'     => $this->request->getPost('customer_name'),
                'phone_number'     => $this->request->getPost('phone_number'),
                'address'      => $this->request->getPost('address')
            ];

            // Debug: Check data before update
            log_message('debug', 'Form Data: ' . json_encode($data));

            // Update the customers data
            if ($customerModel->update($id, $data)) {
                log_message('debug', 'customers updated successfully.');
                return redirect()->to('/customers'); // Redirect to the customerss list page
            } else {
                // If the update fails, log the error
                log_message('error', 'Failed to update customers with ID: ' . $id);
            }
        }

        // Pass customers data to the view
        return view('admin/pages/customers/edit/index', ['customer' => $customers]);
    }


    public function softDelete($id)
    {
        // Get the customer model
        $customerModel = new MNCCustomer();

        // Find the customer
        $customer = $customerModel->find($id);

        // Check if the customer exists
        if (!$customer) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('customer not found');
        }

        // Update the customer's status to 0 (soft delete)
        $data = [
            'status' => 0
        ];

        if ($customerModel->update($id, $data)) {
            // Redirect to customers list with a success message
            return redirect()->to('/customers')->with('message', 'customer soft-deleted successfully.');
        } else {
            // If update fails
            return redirect()->back()->with('error', 'Failed to soft delete the customer.');
        }
    }

    public function pricing($id)
    {
        // Get the customer model
        $customerModel = new MNCCustomer();
        $customerPricing = new MNCCustomerPrice();
        $countryModel = new MNCConstantCountry();
        $cityModel = new MNCConstantCity();

        // Find the customer
        $data['customer']   = $customerModel->find($id);
        $data['countries']  = $countryModel->findAll();
        $data['citys']      = $cityModel->findAll();
        $data['pricing']    = $customerPricing->where('customer_id', $id)->findAll();


        // Check if the customer exists
        if (!$data['customer']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('customer not found');
        }

        // Pass customer data to the view
        return view('admin/pages/customers/edit/pricing', [
            'customers' => $data['customer'], 
            'countries' => $data['countries'], 
            'citys' => $data['citys'],
            'pricing' => json_encode($data['pricing'])
        ]);
    }   

    public function editPricing()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Get the customer ID
            $customerId = $this->request->getPost('id');
            if (!$customerId) {
                throw new \Exception('Customer ID is required.');
            }

            // Get pricing data from POST
            $prices = json_decode($this->request->getPost('editpricingData'), true);
            if (empty($prices)) {
                throw new \Exception('No pricing data provided.');
            }

            foreach ($prices as $price) {
                $data = [
                    'customer_id' => $customerId,
                    'price_code' => 'MNC-' . $price['from'] . '-' . $price['to'] . '-' . $price['service'],
                    'from' => $price['from'],
                    'to' => $price['to'],
                    'service' => $price['service'],
                    'price' => $price['price']
                ];

                // Gunakan raw SQL upsert (insert or update)
                $db->query("
                    INSERT INTO mnc_customers_price 
                        (customer_id, price_code, `from`, `to`, service, price)
                    VALUES 
                        (:customer_id:, :price_code:, :from:, :to:, :service:, :price:)
                    ON DUPLICATE KEY UPDATE 
                        price_code = VALUES(price_code),
                        price = VALUES(price)
                ", $data);
            }
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error updating customer pricing: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update customer pricing: ' . $e->getMessage());
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            log_message('error', 'Transaction failed while updating customer pricing.');
            return redirect()->back()->with('error', 'Failed to update customer pricing.');
        }

        return redirect()->to('/customers')->with('success', 'Customer pricing updated successfully.');
    }

           
}
