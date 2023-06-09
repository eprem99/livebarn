<?php
/*
 * Project: Livebarn
 * Author: VECTO
 * Email: info@vecto.digital
 * Site: https://vecto.digital/
 * Last Modified: Friday, 28th April 2023
 */

use Illuminate\Database\Seeder;

class SmtpSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $smtp = new \App\SmtpSetting();
        $smtp->mail_driver = 'smtp';
        $smtp->mail_host = 'smtp.gmail.com';
        $smtp->mail_port = '465';
        $smtp->mail_username = 'myemail@gmail.com';
        $smtp->mail_password = 'mypassword';
        $smtp->mail_from_name = 'Vecto';
        $smtp->mail_encryption = 'ssl';
        $smtp->save();
    }
}
