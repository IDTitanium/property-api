<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Can create properties
     *
     * @return void
     */
    public function test_can_create_properties_singly()
    {
        $requestBody = [
            'address' => [
                'line_1' => "19",
                'line_2' => "Goodway Drive",
                'postcode' => 17261
            ]
        ];

        $response = $this->post('/api/properties', $requestBody);

        $response->assertStatus(201);

        $response->assertSeeText("Property stored successfully");

        $response->assertJson([
            'data' => [
                'address_line_1' => "19",
                'address_line_2' => 'Goodway Drive',
                'postcode' => 17261
            ]
        ]);
    }

    /**
     * can create properties by bulk upload
     * @return void
     */
    public function test_can_create_properties_by_bulk_upload()
    {
        $response = $this->post('/api/properties', [
            'properties' => new UploadedFile(public_path('properties.csv'), 'properties.csv', 'csv', null, true)
        ]);

        $response->assertStatus(201);

        $response->assertSeeText("Properties uploaded successfully");

        $this->assertDatabaseHas('properties', [
            'address_line_1' => "12",
            'address_line_2' => "Babcock street",
            'postcode' => 1234
        ]);
    }

    /**
     * cannot create properties with failing validation
     * @return void
     */
    public function test_cannot_create_properties_with_failing_validation() {
        $wrongRequestBody = [
            [
                'line_1' => "19",
                'line_2' => "Goodway Drive",
                'postcode' => 17261
            ]
        ];

        $response = $this->post('/api/properties', $wrongRequestBody);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('properties', [
            'address_line_1' => "19",
            'address_line_2' => "Goodway Drive",
            'postcode' => 17261
        ]);
    }

    /**
     * can get a list of properties
     * @return void
     */
    public function test_can_get_a_list_of_properties() {
        // Create the properties

        $response = $this->post('/api/properties', [
            'properties' => new UploadedFile(public_path('properties.csv'), 'properties.csv', 'csv', null, true)
        ]);

        // Fetch the properties
        $response = $this->get('/api/properties');

        $response->assertStatus(200);

        $response->assertSeeText("Properties fetched successfully");

        $responseBody = $response->decodeResponseJson();

        $firstItem = $responseBody['data']['data'][0];

        $response->assertJson([
            'data' => [
                'current_page' => 1,
                'data' => [
                    [
                        'id' => $firstItem['id'],
                        'address_line_1' => $firstItem['address_line_1'],
                        'address_line_2' => $firstItem['address_line_2'],
                        'postcode' => $firstItem['postcode']
                    ]
                ]
            ]
        ]);
    }

    /**
     * can download sample csv
     * @return void
     */
    public function test_can_download_sample_csv() {
        $response = $this->get('/api/properties/sample-csv');

        $response->assertDownload('properties.csv');
    }
}
