<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General Settings
        Setting::create([
            'setting_key' => 'app_name',
            'setting_value' => 'BMKG Network Monitoring',
            'category' => 'general',
            'description' => 'Application Name',
        ]);

        Setting::create([
            'setting_key' => 'location',
            'setting_value' => 'BMKG Riau',
            'category' => 'general',
            'description' => 'Location',
        ]);

        Setting::create([
            'setting_key' => 'timezone',
            'setting_value' => 'Asia/Jakarta',
            'category' => 'general',
            'description' => 'Timezone',
        ]);

        Setting::create([
            'setting_key' => 'language',
            'setting_value' => 'id',
            'category' => 'general',
            'description' => 'Language',
        ]);

        Setting::create([
            'setting_key' => 'theme',
            'setting_value' => 'light',
            'category' => 'general',
            'description' => 'Theme',
        ]);

        // Network Settings
        Setting::create([
            'setting_key' => 'ping_enabled',
            'setting_value' => 'true',
            'category' => 'network',
            'description' => 'Ping (ICMP) Enabled',
        ]);

        Setting::create([
            'setting_key' => 'snmp_enabled',
            'setting_value' => 'true',
            'category' => 'network',
            'description' => 'SNMP Enabled',
        ]);

        Setting::create([
            'setting_key' => 'http_enabled',
            'setting_value' => 'false',
            'category' => 'network',
            'description' => 'HTTP/HTTPS Enabled',
        ]);

        Setting::create([
            'setting_key' => 'polling_interval',
            'setting_value' => '60',
            'category' => 'network',
            'description' => 'Polling Interval (seconds)',
        ]);

        Setting::create([
            'setting_key' => 'timeout',
            'setting_value' => '10',
            'category' => 'network',
            'description' => 'Timeout (seconds)',
        ]);

        Setting::create([
            'setting_key' => 'retries',
            'setting_value' => '3',
            'category' => 'network',
            'description' => 'Number of Retries',
        ]);

        // Monitoring Settings
        Setting::create([
            'setting_key' => 'data_retention',
            'setting_value' => '7',
            'category' => 'monitoring',
            'description' => 'Data Retention (days)',
        ]);

        Setting::create([
            'setting_key' => 'realtime_graphs',
            'setting_value' => 'true',
            'category' => 'monitoring',
            'description' => 'Real-time Graphs Enabled',
        ]);

        Setting::create([
            'setting_key' => 'show_trends',
            'setting_value' => 'true',
            'category' => 'monitoring',
            'description' => 'Show Trends',
        ]);

        // Notification Settings
        Setting::create([
            'setting_key' => 'email_notifications',
            'setting_value' => 'true',
            'category' => 'notifications',
            'description' => 'Email Notifications Enabled',
        ]);

        Setting::create([
            'setting_key' => 'sms_alerts',
            'setting_value' => 'true',
            'category' => 'notifications',
            'description' => 'SMS Alerts Enabled',
        ]);

        Setting::create([
            'setting_key' => 'telegram_alerts',
            'setting_value' => 'false',
            'category' => 'notifications',
            'description' => 'Telegram Alerts Enabled',
        ]);

        Setting::create([
            'setting_key' => 'alert_email',
            'setting_value' => 'admin@bmkg.local',
            'category' => 'notifications',
            'description' => 'Alert Email Address',
        ]);

        Setting::create([
            'setting_key' => 'telegram_token',
            'setting_value' => '',
            'category' => 'notifications',
            'description' => 'Telegram Bot Token',
        ]);

        Setting::create([
            'setting_key' => 'telegram_chat_id',
            'setting_value' => '',
            'category' => 'notifications',
            'description' => 'Telegram Chat ID',
        ]);

        // Security Settings
        Setting::create([
            'setting_key' => 'two_factor_auth',
            'setting_value' => 'false',
            'category' => 'security',
            'description' => 'Two Factor Authentication',
        ]);
    }
}
