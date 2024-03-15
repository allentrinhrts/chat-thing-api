<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RagResource>
 */
class RagResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $json = '{
            "name": "Creating a Load/Shipment on Agent Route Board",
            "summary": "This document provides step-by-step instructions for creating a load or shipment using the Route/Load option on the Agent Route Board.",
            "steps": [
                {
                    "step": 1,
                    "header": "Enter Load Details",
                    "instruction": "Enter the load details in the appropriate fields, ensuring that all required fields are filled. Use the auto-suggest feature for field entries.",
                    "summary": "Enter the load details and ensure all required fields are filled."
                },
                {
                    "step": 2,
                    "header": "Select Template or Customer",
                    "instruction": "If you have a load template, search for it in the Search Templates field. Otherwise, enter the customer name in the Search Customer field and select the appropriate customer from the list. This will populate the Bill To Location and Payment Terms.",
                    "summary": "Select a template or customer for the load."
                },
                {
                    "step": 3,
                    "header": "Enter Stop and Commodity Details",
                    "instruction": "Enter the next stop or final destination details in the appropriate fields in the Drop #2 section. In the Items section, enter the commodity details. Use the Enter New Item button to add multiple items if needed.",
                    "summary": "Enter stop and commodity details for the load."
                },
                {
                    "step": 4,
                    "header": "Enter Pickup Details",
                    "instruction": "Enter the pickup (origin) load details in the Pickup #1 section. Use the Search Location field to find the location and select it from the list. Additional pickups can be added using the Add Pickup button. Remove any incorrectly entered locations by clicking on the icon next to Location Info.",
                    "summary": "Enter pickup details for the load."
                },
                {
                    "step": 5,
                    "header": "Enter Item Details",
                    "instruction": "Enter the item details such as freight description, dimensions, and commodity details in the Items section. Use the Enter New Item button to add multiple items. Additional fields may be required for hazmat loads.",
                    "summary": "Enter item details for the load."
                },
                {
                    "step": 6,
                    "header": "Enter Final Stop Details",
                    "instruction": "Enter the final stop details in the appropriate fields in the Drop #2 section.",
                    "summary": "Enter final stop details for the load."
                },
                {
                    "step": 7,
                    "header": "Review Stop Summary",
                    "instruction": "As you enter pickup and drop locations, a static list will generate on the right side of the screen. This list provides a summary of the stop information, including mileage, shipper/consignee names, dates, and the number of items.",
                    "summary": "Review the stop summary of the load."
                },
                {
                    "step": 8,
                    "header": "Enter References",
                    "instruction": "Verify and update any pre-filled information in the References section. Add additional references by clicking the Add button and selecting the reference type and entering the information.",
                    "summary": "Enter references for the load."
                },
                {
                    "step": 9,
                    "header": "Enter Services and Equipment",
                    "instruction": "Select any additional services for the load by clicking in the Select Services field and choosing from the list. Select the applicable equipment by clicking in the Select Equipment field. You can also indicate tax exemption in the Reference Type and Value fields.",
                    "summary": "Select services and equipment for the load."
                },
                {
                    "step": 10,
                    "header": "Review Summary",
                    "instruction": "Review the summary of the load, which displays important information such as mileage, shipper/consignee names, dates, and the number of items. Make any necessary changes before proceeding.",
                    "summary": "Review the summary of the load."
                },
                {
                    "step": 11,
                    "header": "Adding Instructions",
                    "instruction": "If necessary, enter any instructions in the appropriate field. These instructions can be specific to the customer, BCO, or carrier.",
                    "summary": "Add instructions to the load."
                },
                {
                    "step": 12,
                    "header": "Save or Choose Options",
                    "instruction": "Click the Toggle button to either save the load or choose from options such as creating copies, creating a template, modifying and copying, or adding a customer rate and accessorial charges.",
                    "summary": "Choose to save the load or select additional options."
                },
                {
                    "step": 13,
                    "header": "Rates",
                    "instruction": "If needed, click to add a customer rate and applicable accessorial charges. This is helpful for quickly posting the load.",
                    "summary": "Add customer rate and accessorial charges if necessary."
                },
                {
                    "step": 14,
                    "header": "System Messages",
                    "instruction": "Pay attention to any system messages displayed in red at the top of the screen. These messages indicate unresolved issues or missing information that need to be addressed.",
                    "summary": "Check for system messages and resolve any issues or missing information."
                },
                {
                    "step": 15,
                    "header": "Successful Confirmation",
                    "instruction": "After successfully saving the load, a confirmation message will be displayed with a Customer Load (CL) number. The load will appear on the Agent Route Board and the Assign Charges screen.",
                    "summary": "Review the successful confirmation message and note the CL number."
                }
            ]
        }';

        return [
            'company_id' => 1,
            'docs' => json_encode(json_decode($json)),
        ];
    }
}
