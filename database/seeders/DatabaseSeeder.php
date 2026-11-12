<?php

namespace Database\Seeders;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\ShipmentException;
use App\Models\ShipmentItem;
use App\Models\TrackingEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Users (3 Roles: Admin, Operator, Customer)
        $admin = User::firstOrCreate(
            ['email' => 'admin@cargotrace.com'],
            [
                'name' => 'Commander Admin',
                'role' => 'admin',
                'phone' => '+628110000001',
                'password' => Hash::make('password'),
            ]
        );

        $operator = User::firstOrCreate(
            ['email' => 'operator@cargotrace.com'],
            [
                'name' => 'Chief Dispatcher',
                'role' => 'operator',
                'phone' => '+628110000002',
                'password' => Hash::make('password'),
            ]
        );

        $customer1 = User::firstOrCreate(
            ['email' => 'budi@agrologistics.com'],
            [
                'name' => 'Budi Santoso (Nusantara Agri Export)',
                'role' => 'customer',
                'phone' => '+628110000003',
                'password' => Hash::make('password'),
            ]
        );

        $customer2 = User::firstOrCreate(
            ['email' => 'siti@globaltrade.com'],
            [
                'name' => 'Siti Rahma (Global Commodity Hub)',
                'role' => 'customer',
                'phone' => '+628110000004',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Create Carriers
        $maersk = Carrier::create([
            'name' => 'Maersk Line Intercontinental',
            'code' => 'MSK',
            'contact_email' => 'dispatch@maersk.com',
            'contact_phone' => '+45 33 63 33 63',
            'service_type' => 'SEA_FREIGHT',
            'is_active' => true,
        ]);

        $dhl = Carrier::create([
            'name' => 'DHL Global Forwarding Ocean',
            'code' => 'DHL',
            'contact_email' => 'ocean@dhl.com',
            'contact_phone' => '+49 228 1820',
            'service_type' => 'MULTIMODAL',
            'is_active' => true,
        ]);

        $one = Carrier::create([
            'name' => 'Ocean Network Express (ONE)',
            'code' => 'ONE',
            'contact_email' => 'support@one-line.com',
            'contact_phone' => '+65 6890 1200',
            'service_type' => 'SEA_FREIGHT',
            'is_active' => true,
        ]);

        // 3. Create Strategic Locations (Ports & Hubs with GPS)
        $jakarta = Location::create([
            'name' => 'Port of Tanjung Priok',
            'code' => 'IDTPP',
            'type' => 'PORT',
            'city' => 'Jakarta',
            'country' => 'Indonesia',
            'country_code' => 'ID',
            'latitude' => -6.1038190,
            'longitude' => 106.8827050,
        ]);

        $surabaya = Location::create([
            'name' => 'Port of Tanjung Perak',
            'code' => 'IDSUB',
            'type' => 'PORT',
            'city' => 'Surabaya',
            'country' => 'Indonesia',
            'country_code' => 'ID',
            'latitude' => -7.1982000,
            'longitude' => 112.7339000,
        ]);

        $singapore = Location::create([
            'name' => 'Port of Singapore (PSA)',
            'code' => 'SGSIN',
            'type' => 'PORT',
            'city' => 'Singapore',
            'country' => 'Singapore',
            'country_code' => 'SG',
            'latitude' => 1.2644000,
            'longitude' => 103.8228000,
        ]);

        $rotterdam = Location::create([
            'name' => 'Port of Rotterdam',
            'code' => 'NLRTM',
            'type' => 'PORT',
            'city' => 'Rotterdam',
            'country' => 'Netherlands',
            'country_code' => 'NL',
            'latitude' => 51.9496000,
            'longitude' => 4.1453000,
        ]);

        $hamburg = Location::create([
            'name' => 'Port of Hamburg',
            'code' => 'DEHAM',
            'type' => 'PORT',
            'city' => 'Hamburg',
            'country' => 'Germany',
            'country_code' => 'DE',
            'latitude' => 53.5353000,
            'longitude' => 9.9872000,
        ]);

        $sydney = Location::create([
            'name' => 'Port Botany',
            'code' => 'AUBOT',
            'type' => 'PORT',
            'city' => 'Sydney',
            'country' => 'Australia',
            'country_code' => 'AU',
            'latitude' => -33.9744000,
            'longitude' => 151.2181000,
        ]);

        // 4. Create Shipment 1: IN_TRANSIT with DELAY (Jakarta -> Rotterdam)
        $departure1 = Carbon::now()->subDays(6);
        $expectedArrival1 = Carbon::now()->addDays(8);
        $estimatedArrival1 = Carbon::now()->addDays(8)->addHours(14); // 14 hours delayed (MEDIUM severity)

        $shipment1 = Shipment::create([
            'shipment_number' => 'CTG-2026-0081',
            'customer_id' => $customer1->id,
            'carrier_id' => $maersk->id,
            'origin_id' => $jakarta->id,
            'destination_id' => $rotterdam->id,
            'cargo_type' => 'Grade-1 Arabica Gayo Green Coffee Beans',
            'weight_kg' => 24500.00,
            'volume_cbm' => 67.50,
            'current_status' => 'IN_TRANSIT',
            'expected_departure' => $departure1,
            'actual_departure' => $departure1->copy()->addHours(2),
            'expected_arrival' => $expectedArrival1,
            'estimated_arrival' => $estimatedArrival1,
            'delay_status' => 'DELAYED',
            'delay_hours' => 14,
            'delay_severity' => 'MEDIUM',
            'delay_reason' => 'Monsoon low-pressure system in Indian Ocean and bunker queue at Colombo',
            'notes' => 'Temperature-controlled reefer container set at 18C.',
        ]);

        ShipmentItem::create([
            'shipment_id' => $shipment1->id,
            'item_name' => 'Arabica Gayo Coffee GrainPro Bags (60kg)',
            'commodity_code' => '0901.11',
            'quantity' => 380,
            'unit' => 'BAG',
            'weight_kg' => 22800.00,
        ]);

        ShipmentItem::create([
            'shipment_id' => $shipment1->id,
            'item_name' => 'Specialty Peaberry Reserve Samples',
            'commodity_code' => '0901.12',
            'quantity' => 30,
            'unit' => 'BOX',
            'weight_kg' => 1700.00,
        ]);

        // Tracking events for Shipment 1
        TrackingEvent::create([
            'shipment_id' => $shipment1->id,
            'location_id' => $jakarta->id,
            'event_type' => 'BOOKED',
            'event_time' => $departure1->copy()->subDays(2),
            'location_name' => 'Jakarta Booking Office',
            'latitude' => $jakarta->latitude,
            'longitude' => $jakarta->longitude,
            'description' => 'Electronic booking confirmed with carrier vessel MV Northern Majestic voyage 2608W.',
            'created_by' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment1->id,
            'location_id' => $jakarta->id,
            'event_type' => 'PICKED_UP',
            'event_time' => $departure1->copy()->subDays(1),
            'location_name' => 'Tanjung Priok Container Terminal 1',
            'latitude' => $jakarta->latitude,
            'longitude' => $jakarta->longitude,
            'description' => 'Container sealed (Seal #MSK-881902) and loaded into yard stack awaiting vessel berth.',
            'created_by' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment1->id,
            'location_id' => $jakarta->id,
            'event_type' => 'DEPARTED',
            'event_time' => $departure1,
            'location_name' => 'Port of Tanjung Priok Outbound Channel',
            'latitude' => $jakarta->latitude,
            'longitude' => $jakarta->longitude,
            'description' => 'Vessel departed berth; maritime pilot disembarked.',
            'created_by' => $operator->id,
        ]);

        $eventTransit1 = TrackingEvent::create([
            'shipment_id' => $shipment1->id,
            'location_id' => $singapore->id,
            'event_type' => 'IN_TRANSIT',
            'event_time' => $departure1->copy()->addDays(2),
            'location_name' => 'Singapore Transshipment Hub',
            'latitude' => $singapore->latitude,
            'longitude' => $singapore->longitude,
            'description' => 'Feeder connection completed; re-stowed onto mother vessel Maersk Mc-Kinney Moller.',
            'created_by' => $operator->id,
        ]);

        // Exception for Shipment 1
        ShipmentException::create([
            'shipment_id' => $shipment1->id,
            'tracking_event_id' => $eventTransit1->id,
            'exception_type' => 'WEATHER_DELAY',
            'severity' => 'MEDIUM',
            'status' => 'INVESTIGATING',
            'description' => 'Severe weather advisory along Bay of Bengal / Malacca exit resulting in 14 knots speed limit.',
            'detected_at' => Carbon::now()->subHours(20),
            'resolved_at' => null,
            'resolution_notes' => 'Routing adjusted 40 nautical miles south to bypass squall line.',
        ]);

        // 5. Create Shipment 2: DELIVERED (Surabaya -> Singapore)
        $departure2 = Carbon::now()->subDays(10);
        $arrival2 = Carbon::now()->subDays(3);

        $shipment2 = Shipment::create([
            'shipment_number' => 'CTG-2026-0082',
            'customer_id' => $customer1->id,
            'carrier_id' => $one->id,
            'origin_id' => $surabaya->id,
            'destination_id' => $singapore->id,
            'cargo_type' => 'Organic Vanilla Pods & Cinnamon Bark',
            'weight_kg' => 8400.00,
            'volume_cbm' => 24.00,
            'current_status' => 'DELIVERED',
            'expected_departure' => $departure2,
            'actual_departure' => $departure2,
            'expected_arrival' => $arrival2,
            'estimated_arrival' => $arrival2,
            'actual_arrival' => $arrival2,
            'delay_status' => 'ON_TIME',
            'delay_hours' => 0,
            'delay_severity' => 'NONE',
            'notes' => 'Successful on-time direct delivery.',
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment2->id,
            'location_id' => $surabaya->id,
            'event_type' => 'DEPARTED',
            'event_time' => $departure2,
            'location_name' => 'Port of Tanjung Perak',
            'latitude' => $surabaya->latitude,
            'longitude' => $surabaya->longitude,
            'description' => 'Loaded onto vessel ONE Competence.',
            'created_by' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment2->id,
            'location_id' => $singapore->id,
            'event_type' => 'DELIVERED',
            'event_time' => $arrival2,
            'location_name' => 'Jurong Port Warehouse Complex',
            'latitude' => $singapore->latitude,
            'longitude' => $singapore->longitude,
            'description' => 'Consignee signed proof of delivery. Cargo inspected and accepted in pristine condition.',
            'created_by' => $operator->id,
        ]);

        // 6. Create Shipment 3: BOOKED (Jakarta -> Hamburg)
        Shipment::create([
            'shipment_number' => 'CTG-2026-0083',
            'customer_id' => $customer2->id,
            'carrier_id' => $dhl->id,
            'origin_id' => $jakarta->id,
            'destination_id' => $hamburg->id,
            'cargo_type' => 'Refined Cocoa Butter & Cocoa Liquor',
            'weight_kg' => 19800.00,
            'volume_cbm' => 45.00,
            'current_status' => 'BOOKED',
            'expected_departure' => Carbon::now()->addDays(2),
            'expected_arrival' => Carbon::now()->addDays(24),
            'estimated_arrival' => Carbon::now()->addDays(24),
            'delay_status' => 'ON_TIME',
            'delay_hours' => 0,
            'delay_severity' => 'NONE',
            'notes' => 'Customs export declaration PEB in process.',
        ]);

        // 7. Create Shipment 4: CREATED (Jakarta -> Sydney)
        Shipment::create([
            'shipment_number' => 'CTG-2026-0084',
            'customer_id' => $customer2->id,
            'carrier_id' => $one->id,
            'origin_id' => $jakarta->id,
            'destination_id' => $sydney->id,
            'cargo_type' => 'Raw Black Tea Orthodox Pekoe',
            'weight_kg' => 14200.00,
            'volume_cbm' => 38.00,
            'current_status' => 'CREATED',
            'expected_departure' => Carbon::now()->addDays(5),
            'expected_arrival' => Carbon::now()->addDays(18),
            'estimated_arrival' => Carbon::now()->addDays(18),
            'delay_status' => 'ON_TIME',
            'delay_hours' => 0,
            'delay_severity' => 'NONE',
            'notes' => 'Awaiting shipper container stuffing.',
        ]);
    }
}
