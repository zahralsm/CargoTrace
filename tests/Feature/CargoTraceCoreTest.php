<?php

namespace Tests\Feature;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\User;
use App\Services\EtaService;
use App\Services\ShipmentService;
use App\Services\TrackingService;
use Tests\TestCase;

class CargoTraceCoreTest extends TestCase
{
    /**
     * TEST 1: Menguji Rumus Keterlambatan & Tingkat Severity (Slide 08)
     */
    public function test_eta_service_calculates_delay_and_severity_correctly(): void
    {
        $etaService = new EtaService();

        // 0 - 6 jam = LOW
        $this->assertEquals('LOW', $etaService->determineSeverity(4));
        
        // >6 - 24 jam = MEDIUM
        $this->assertEquals('MEDIUM', $etaService->determineSeverity(14));
        
        // >24 jam = HIGH
        $this->assertEquals('HIGH', $etaService->determineSeverity(36));
        
        // Tepat waktu = NONE
        $this->assertEquals('NONE', $etaService->determineSeverity(0));
    }

    /**
     * TEST 2: Menguji State Machine Mencegah Transisi Status Ilegal (Slide 06)
     */
    public function test_shipment_service_prevents_invalid_lifecycle_transition(): void
    {
        $etaService = new EtaService();
        $shipmentService = new ShipmentService($etaService);

        $shipment = new Shipment(['current_status' => 'CREATED']);

        // Transisi yang sah: CREATED -> BOOKED
        $this->assertTrue($shipmentService->canTransitionTo($shipment, 'BOOKED'));

        // Transisi ILEGAL: CREATED langsung melompat ke DELIVERED
        $this->assertFalse($shipmentService->canTransitionTo($shipment, 'DELIVERED'));

        // Status DELIVERED adalah terminal, tidak boleh berpindah lagi
        $deliveredShipment = new Shipment(['current_status' => 'DELIVERED']);
        $this->assertFalse($shipmentService->canTransitionTo($deliveredShipment, 'BOOKED'));
    }

    /**
     * TEST 3: Menguji Proteksi IDOR (Customer Tidak Boleh Mengintip Kargo Orang Lain) - Slide 05 & 16
     */
    public function test_customer_cannot_view_other_customers_shipment(): void
    {
        $customerA = User::create([
            'name'     => 'Customer Alpha',
            'email'    => 'alpha_' . uniqid() . '@test.com',
            'password' => bcrypt('password'),
            'role'     => 'customer',
        ]);

        $customerB = User::create([
            'name'     => 'Customer Beta',
            'email'    => 'beta_' . uniqid() . '@test.com',
            'password' => bcrypt('password'),
            'role'     => 'customer',
        ]);

        $carrier = Carrier::firstOrCreate(
            ['code' => 'TEST'],
            ['name' => 'Test Carrier Line', 'service_type' => 'SEA_FREIGHT']
        );

        $origin = Location::firstOrCreate(
            ['code' => 'T1'],
            ['name' => 'Port 1', 'city' => 'Origin City', 'country' => 'ID', 'country_code' => 'ID', 'latitude' => -6.1, 'longitude' => 106.8]
        );

        $dest = Location::firstOrCreate(
            ['code' => 'T2'],
            ['name' => 'Port 2', 'city' => 'Dest City', 'country' => 'SG', 'country_code' => 'SG', 'latitude' => 1.2, 'longitude' => 103.8]
        );

        $shipmentB = Shipment::create([
            'shipment_number'    => 'TEST-IDOR-' . uniqid(),
            'customer_id'        => $customerB->id,
            'carrier_id'         => $carrier->id,
            'origin_id'          => $origin->id,
            'destination_id'     => $dest->id,
            'cargo_type'         => 'Confidential Cargo',
            'weight_kg'          => 1000,
            'volume_cbm'         => 10,
            'current_status'     => 'BOOKED',
            'expected_departure' => now(),
            'expected_arrival'   => now()->addDays(5),
            'estimated_arrival'  => now()->addDays(5),
        ]);

        // Customer A mencoba mengakses detail kargo milik Customer B
        $response = $this->actingAs($customerA)->get('/shipments/' . $shipmentB->id);
        
        // Sistem WAJIB menolak dengan HTTP 403 Forbidden
        $response->assertStatus(403);
    }

    /**
     * TEST 4: Menguji Login REST API Menghasilkan Bearer Token Sanctum (Slide 12)
     */
    public function test_api_login_returns_sanctum_bearer_token(): void
    {
        $testEmail = 'api_operator_' . uniqid() . '@cargotrace.com';

        User::create([
            'name'     => 'API Operator Tester',
            'email'    => $testEmail,
            'password' => bcrypt('secret123'),
            'role'     => 'operator',
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => $testEmail,
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'token',
                    'token_type',
                    'user' => ['id', 'name', 'email', 'role']
                ]
            ]);
    }

    /**
     * TEST 5: Menguji Endpoint API Shipments Dilindungi Autentikasi Token (Slide 12 & 13)
     */
    public function test_api_shipments_requires_authentication(): void
    {
        // Akses tanpa token harus ditolak dengan 401 Unauthorized
        $response = $this->getJson('/api/shipments');
        $response->assertStatus(401);
    }
}
