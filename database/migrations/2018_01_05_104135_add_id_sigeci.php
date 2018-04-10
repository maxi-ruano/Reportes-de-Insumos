<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdSigeci extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ansv_paises', function (Blueprint $table) {
            $table->string('id_sigeci_paises')->nullable();
        });

        DB::table('ansv_paises')->where('id_dgevyl','110')->update(['id_sigeci_paises' => '1']);
        DB::table('ansv_paises')->where('id_dgevyl','90')->update(['id_sigeci_paises' => '2']);
        DB::table('ansv_paises')->where('id_dgevyl','14')->update(['id_sigeci_paises' => '3']);
        DB::table('ansv_paises')->where('id_dgevyl','151')->update(['id_sigeci_paises' => '4']);
        DB::table('ansv_paises')->where('id_dgevyl','80')->update(['id_sigeci_paises' => '5']);
        DB::table('ansv_paises')->where('id_dgevyl','160')->update(['id_sigeci_paises' => '6']);
        DB::table('ansv_paises')->where('id_dgevyl','1')->update(['id_sigeci_paises' => '7']);
        DB::table('ansv_paises')->where('id_dgevyl','43')->update(['id_sigeci_paises' => '8']);
        DB::table('ansv_paises')->where('id_dgevyl','164')->update(['id_sigeci_paises' => '9']);
        DB::table('ansv_paises')->where('id_dgevyl','77')->update(['id_sigeci_paises' => '10']);
        DB::table('ansv_paises')->where('id_dgevyl','41')->update(['id_sigeci_paises' => '11']);
        DB::table('ansv_paises')->where('id_dgevyl','138')->update(['id_sigeci_paises' => '12']);
        DB::table('ansv_paises')->where('id_dgevyl','165')->update(['id_sigeci_paises' => '13']);
        DB::table('ansv_paises')->where('id_dgevyl','147')->update(['id_sigeci_paises' => '14']);
        DB::table('ansv_paises')->where('id_dgevyl','167')->update(['id_sigeci_paises' => '15']);
        //DB::table('ansv_paises')->where('id_dgevyl','1')->update(['id_sigeci_paises' => '16']); barein no exite ese pais en dghct
        DB::table('ansv_paises')->where('id_dgevyl','37')->update(['id_sigeci_paises' => '17']);
        DB::table('ansv_paises')->where('id_dgevyl','168')->update(['id_sigeci_paises' => '18']);
        DB::table('ansv_paises')->where('id_dgevyl','72')->update(['id_sigeci_paises' => '19']);
        DB::table('ansv_paises')->where('id_dgevyl','4')->update(['id_sigeci_paises' => '20']);
        DB::table('ansv_paises')->where('id_dgevyl','126')->update(['id_sigeci_paises' => '21']);
        DB::table('ansv_paises')->where('id_dgevyl','6')->update(['id_sigeci_paises' => '22']);
        DB::table('ansv_paises')->where('id_dgevyl','150')->update(['id_sigeci_paises' => '23']);
        DB::table('ansv_paises')->where('id_dgevyl','67')->update(['id_sigeci_paises' => '24']);
        DB::table('ansv_paises')->where('id_dgevyl','129')->update(['id_sigeci_paises' => '25']);
        DB::table('ansv_paises')->where('id_dgevyl','22')->update(['id_sigeci_paises' => '26']);
        DB::table('ansv_paises')->where('id_dgevyl','39')->update(['id_sigeci_paises' => '27']);
        DB::table('ansv_paises')->where('id_dgevyl','5')->update(['id_sigeci_paises' => '28']);
        DB::table('ansv_paises')->where('id_dgevyl','17')->update(['id_sigeci_paises' => '29']);
        DB::table('ansv_paises')->where('id_dgevyl','177')->update(['id_sigeci_paises' => '30']);
        DB::table('ansv_paises')->where('id_dgevyl','9')->update(['id_sigeci_paises' => '31']);
        DB::table('ansv_paises')->where('id_dgevyl','55')->update(['id_sigeci_paises' => '32']);
        DB::table('ansv_paises')->where('id_dgevyl','58')->update(['id_sigeci_paises' => '33']);
        DB::table('ansv_paises')->where('id_dgevyl','53')->update(['id_sigeci_paises' => '34']);
        DB::table('ansv_paises')->where('id_dgevyl','29')->update(['id_sigeci_paises' => '35']);
        DB::table('ansv_paises')->where('id_dgevyl','56')->update(['id_sigeci_paises' => '36']);
        //falta pais Dominica en DGEVYL gentilicio dominiquesa id 37 en sigeci
        DB::table('ansv_paises')->where('id_dgevyl','195')->update(['id_sigeci_paises' => '38']);
        DB::table('ansv_paises')->where('id_dgevyl','33')->update(['id_sigeci_paises' => '39']);
        DB::table('ansv_paises')->where('id_dgevyl','65')->update(['id_sigeci_paises' => '40']);
        DB::table('ansv_paises')->where('id_dgevyl','146')->update(['id_sigeci_paises' => '41']);
        DB::table('ansv_paises')->where('id_dgevyl','91')->update(['id_sigeci_paises' => '42']);
        DB::table('ansv_paises')->where('id_dgevyl','105')->update(['id_sigeci_paises' => '43']);
        DB::table('ansv_paises')->where('id_dgevyl','71')->update(['id_sigeci_paises' => '44']);
        DB::table('ansv_paises')->where('id_dgevyl','11')->update(['id_sigeci_paises' => '45']);
        DB::table('ansv_paises')->where('id_dgevyl','16')->update(['id_sigeci_paises' => '46']);
        DB::table('ansv_paises')->where('id_dgevyl','131')->update(['id_sigeci_paises' => '47']);
        DB::table('ansv_paises')->where('id_dgevyl','122')->update(['id_sigeci_paises' => '48']);
        DB::table('ansv_paises')->where('id_dgevyl','50')->update(['id_sigeci_paises' => '49']);
        DB::table('ansv_paises')->where('id_dgevyl','85')->update(['id_sigeci_paises' => '50']);
        DB::table('ansv_paises')->where('id_dgevyl','13')->update(['id_sigeci_paises' => '51']);
        DB::table('ansv_paises')->where('id_dgevyl','79')->update(['id_sigeci_paises' => '52']);
        DB::table('ansv_paises')->where('id_dgevyl','44')->update(['id_sigeci_paises' => '53']);
        DB::table('ansv_paises')->where('id_dgevyl','70')->update(['id_sigeci_paises' => '54']);
        DB::table('ansv_paises')->where('id_dgevyl','197')->update(['id_sigeci_paises' => '55']);
        DB::table('ansv_paises')->where('id_dgevyl','81')->update(['id_sigeci_paises' => '56']);
        DB::table('ansv_paises')->where('id_dgevyl','52')->update(['id_sigeci_paises' => '57']);
        DB::table('ansv_paises')->where('id_dgevyl','31')->update(['id_sigeci_paises' => '58']);
        DB::table('ansv_paises')->where('id_dgevyl','64')->update(['id_sigeci_paises' => '59']);
        DB::table('ansv_paises')->where('id_dgevyl','48')->update(['id_sigeci_paises' => '60']);
        DB::table('ansv_paises')->where('id_dgevyl','32')->update(['id_sigeci_paises' => '61']);
        DB::table('ansv_paises')->where('id_dgevyl','57')->update(['id_sigeci_paises' => '62']);
        DB::table('ansv_paises')->where('id_dgevyl','26')->update(['id_sigeci_paises' => '63']);
        DB::table('ansv_paises')->where('id_dgevyl','12')->update(['id_sigeci_paises' => '64']);
        //falta pais jamaica id sigeci 65
        //DB::table('ansv_paises')->where('id_dgevyl','1')->update(['id_sigeci_paises' => '65']);
        DB::table('ansv_paises')->where('id_dgevyl','20')->update(['id_sigeci_paises' => '66']);
        DB::table('ansv_paises')->where('id_dgevyl','120')->update(['id_sigeci_paises' => '67']);
        DB::table('ansv_paises')->where('id_dgevyl','30')->update(['id_sigeci_paises' => '68']);
        DB::table('ansv_paises')->where('id_dgevyl','101')->update(['id_sigeci_paises' => '69']);
        DB::table('ansv_paises')->where('id_dgevyl','76')->update(['id_sigeci_paises' => '70']);
        DB::table('ansv_paises')->where('id_dgevyl','54')->update(['id_sigeci_paises' => '71']);
        DB::table('ansv_paises')->where('id_dgevyl','93')->update(['id_sigeci_paises' => '72']);
        //falta pais para el gentilicio m}Maltesa
        //DB::table('ansv_paises')->where('id_dgevyl','1')->update(['id_sigeci_paises' => '73']);
        DB::table('ansv_paises')->where('id_dgevyl','49')->update(['id_sigeci_paises' => '74']);
        DB::table('ansv_paises')->where('id_dgevyl','10')->update(['id_sigeci_paises' => '75']);
        DB::table('ansv_paises')->where('id_dgevyl','89')->update(['id_sigeci_paises' => '76']);
        DB::table('ansv_paises')->where('id_dgevyl','263')->update(['id_sigeci_paises' => '77']);
        DB::table('ansv_paises')->where('id_dgevyl','212')->update(['id_sigeci_paises' => '78']);
        DB::table('ansv_paises')->where('id_dgevyl','271')->update(['id_sigeci_paises' => '79']);
        DB::table('ansv_paises')->where('id_dgevyl','214')->update(['id_sigeci_paises' => '80']);
        DB::table('ansv_paises')->where('id_dgevyl','73')->update(['id_sigeci_paises' => '81']);
        DB::table('ansv_paises')->where('id_dgevyl','51')->update(['id_sigeci_paises' => '82']);
        DB::table('ansv_paises')->where('id_dgevyl','68')->update(['id_sigeci_paises' => '83']);
        DB::table('ansv_paises')->where('id_dgevyl','23')->update(['id_sigeci_paises' => '84']);
        DB::table('ansv_paises')->where('id_dgevyl','66')->update(['id_sigeci_paises' => '85']);
        DB::table('ansv_paises')->where('id_dgevyl','34')->update(['id_sigeci_paises' => '86']);
        DB::table('ansv_paises')->where('id_dgevyl','3')->update(['id_sigeci_paises' => '87']);
        DB::table('ansv_paises')->where('id_dgevyl','7')->update(['id_sigeci_paises' => '88']);
        DB::table('ansv_paises')->where('id_dgevyl','47')->update(['id_sigeci_paises' => '89']);
        DB::table('ansv_paises')->where('id_dgevyl','15')->update(['id_sigeci_paises' => '90']);
        DB::table('ansv_paises')->where('id_dgevyl','38')->update(['id_sigeci_paises' => '91']);
        DB::table('ansv_paises')->where('id_dgevyl','63')->update(['id_sigeci_paises' => '92']);
        DB::table('ansv_paises')->where('id_dgevyl','24')->update(['id_sigeci_paises' => '93']);
        DB::table('ansv_paises')->where('id_dgevyl','225')->update(['id_sigeci_paises' => '94']);
        DB::table('ansv_paises')->where('id_dgevyl','27')->update(['id_sigeci_paises' => '95']);
        DB::table('ansv_paises')->where('id_dgevyl','227')->update(['id_sigeci_paises' => '96']);
        DB::table('ansv_paises')->where('id_dgevyl','232')->update(['id_sigeci_paises' => '97']);
        DB::table('ansv_paises')->where('id_dgevyl','230')->update(['id_sigeci_paises' => '98']);
        DB::table('ansv_paises')->where('id_dgevyl','74')->update(['id_sigeci_paises' => '99']);
        DB::table('ansv_paises')->where('id_dgevyl','127')->update(['id_sigeci_paises' => '100']);
        DB::table('ansv_paises')->where('id_dgevyl','42')->update(['id_sigeci_paises' => '101']);
        DB::table('ansv_paises')->where('id_dgevyl','45')->update(['id_sigeci_paises' => '102']);
        DB::table('ansv_paises')->where('id_dgevyl','19')->update(['id_sigeci_paises' => '103']);
        DB::table('ansv_paises')->where('id_dgevyl','36')->update(['id_sigeci_paises' => '104']);
        DB::table('ansv_paises')->where('id_dgevyl','152')->update(['id_sigeci_paises' => '105']);
        DB::table('ansv_paises')->where('id_dgevyl','155')->update(['id_sigeci_paises' => '106']);
        DB::table('ansv_paises')->where('id_dgevyl','242')->update(['id_sigeci_paises' => '107']);
        DB::table('ansv_paises')->where('id_dgevyl','46')->update(['id_sigeci_paises' => '108']);
        DB::table('ansv_paises')->where('id_dgevyl','8')->update(['id_sigeci_paises' => '109']);
        DB::table('ansv_paises')->where('id_dgevyl','82')->update(['id_sigeci_paises' => '110']);
        DB::table('ansv_paises')->where('id_dgevyl','104')->update(['id_sigeci_paises' => '111']);
        DB::table('ansv_paises')->where('id_dgevyl','2')->update(['id_sigeci_paises' => '112']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ansv_paises', function (Blueprint $table) {
            //
        });
    }
}
