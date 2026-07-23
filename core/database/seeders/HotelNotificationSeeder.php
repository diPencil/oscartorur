<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HotelNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'act' => 'HOTEL_BOOKING_CONFIRMED',
                'name' => 'Hotel Booking Confirmed',
                'subject' => 'Booking Confirmation - {{trx}}',
                'email_body' => '<p>Dear {{name}},</p><p>Your hotel booking for <strong>{{hotel_name}}</strong> has been confirmed successfully!</p><p><strong>Booking Details:</strong></p><ul><li>Booking Number: {{trx}}</li><li>Check In: {{check_in}}</li><li>Check Out: {{check_out}}</li><li>Rooms: {{rooms}}</li><li>Total Paid: {{price}} {{currency}}</li></ul><p>Thank you for choosing us.</p>',
                'sms_body' => 'Your hotel booking {{trx}} at {{hotel_name}} is confirmed. Check In: {{check_in}}',
                'push_title' => 'Booking Confirmed',
                'push_body' => 'Your hotel booking {{trx}} at {{hotel_name}} is confirmed.',
                'shortcodes' => '{"name":"User Name","hotel_name":"Hotel Name","trx":"Booking Number","check_in":"Check In Date","check_out":"Check Out Date","rooms":"Number of Rooms","price":"Total Price","currency":"Currency"}',
                'email_status' => 1,
                'sms_status' => 1,
                'push_status' => 1,
            ],
            [
                'act' => 'HOTEL_BOOKING_CANCELLED',
                'name' => 'Hotel Booking Cancelled',
                'subject' => 'Booking Cancelled - {{trx}}',
                'email_body' => '<p>Dear {{name}},</p><p>Your hotel booking ({{trx}}) for <strong>{{hotel_name}}</strong> has been cancelled successfully.</p><p>Cancellation Penalty: {{penalty}} {{currency}}.</p><p>Refund Amount: {{refund_amount}} {{currency}}.</p><p>If you have any questions, feel free to contact us.</p>',
                'sms_body' => 'Your hotel booking {{trx}} at {{hotel_name}} is cancelled. Refund: {{refund_amount}} {{currency}}.',
                'push_title' => 'Booking Cancelled',
                'push_body' => 'Your hotel booking {{trx}} at {{hotel_name}} is cancelled.',
                'shortcodes' => '{"name":"User Name","hotel_name":"Hotel Name","trx":"Booking Number","penalty":"Cancellation Penalty","refund_amount":"Refund Amount","currency":"Currency"}',
                'email_status' => 1,
                'sms_status' => 1,
                'push_status' => 1,
            ]
        ];

        foreach ($templates as $template) {
            \App\Models\NotificationTemplate::updateOrCreate(
                ['act' => $template['act']],
                $template
            );
        }
    }
}
