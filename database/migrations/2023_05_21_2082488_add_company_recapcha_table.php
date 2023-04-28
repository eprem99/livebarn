
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyRecapchaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organisation_settings', function (Blueprint $table) {
            $table->string('ticket_form_google_captcha')->nullable()->default(null)->after('google_recaptcha_secret');
            $table->string('lead_form_google_captcha')->nullable()->default(null)->after('ticket_form_google_captcha');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organisation_settings', function (Blueprint $table) {
            $table->dropColumn(['ticket_form_google_captcha']);
            $table->dropColumn(['lead_form_google_captcha']);
        });
    }
}
