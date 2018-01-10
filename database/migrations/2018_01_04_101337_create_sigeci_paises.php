<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigeciPaises extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sigeci_paises', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pais');
            $table->timestamps();
        });

        DB::table('sigeci_paises')->insert(array(
          'id' => '1',
          'pais' => 'Afgana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '2',
          'pais' => 'Albanesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '3',
          'pais' => 'Alemana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '4',
          'pais' => 'Andorrana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '5',
          'pais' => 'Angoleña'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '6',
          'pais' => 'Argelina'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '7',
          'pais' => 'Argentina'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '8',
          'pais' => 'Armenia'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '9',
          'pais' => 'Arubana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '10',
          'pais' => 'Australiana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '11',
          'pais' => 'Austríaca'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '12',
          'pais' => 'Azerbaiyana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '13',
          'pais' => 'Bahameña'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '14',
          'pais' => 'Bangladesí'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '15',
          'pais' => 'Barbadense'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '16',
          'pais' => 'Bareiní'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '17',
          'pais' => 'Belga'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '18',
          'pais' => 'Beliceña'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '19',
          'pais' => 'Bielorrusa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '20',
          'pais' => 'Boliviana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '21',
          'pais' => 'Bosnia'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '22',
          'pais' => 'Brasileña'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '23',
          'pais' => 'Británica'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '24',
          'pais' => 'Búlgara'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '25',
          'pais' => 'Camerunesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '26',
          'pais' => 'Canadiense'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '27',
          'pais' => 'Checa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '28',
          'pais' => 'Chilena'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '29',
          'pais' => 'China'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '30',
          'pais' => 'Chipriota'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '31',
          'pais' => 'Colombiana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '32',
          'pais' => 'Costarricense'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '33',
          'pais' => 'Croata'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '34',
          'pais' => 'Cubana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '35',
          'pais' => 'Danesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '36',
          'pais' => 'Dominicana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '37',
          'pais' => 'Dominiquesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '38',
          'pais' => 'Ecuatoguineana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '39',
          'pais' => 'Ecuatoriana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '40',
          'pais' => 'Egipcia'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '41',
          'pais' => 'Emiratí'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '42',
          'pais' => 'Escocesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '43',
          'pais' => 'Eslovaca'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '44',
          'pais' => 'Eslovena'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '45',
          'pais' => 'Española'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '46',
          'pais' => 'Estadounidense'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '47',
          'pais' => 'Estonia'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '48',
          'pais' => 'Etíope'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '49',
          'pais' => 'Filipina'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '50',
          'pais' => 'Finlandesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '51',
          'pais' => 'Francesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '52',
          'pais' => 'Georgiana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '53',
          'pais' => 'Griega'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '54',
          'pais' => 'Guatemalteca'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '55',
          'pais' => 'Guyanesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '56',
          'pais' => 'Haitiana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '57',
          'pais' => 'Hindú'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '58',
          'pais' => 'Holandesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '59',
          'pais' => 'Hondureña'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '60',
          'pais' => 'Húngara'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '61',
          'pais' => 'Indonesia'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '62',
          'pais' => 'Irlandesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '63',
          'pais' => 'Israelí'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '64',
          'pais' => 'Italiana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '65',
          'pais' => 'Jamaiquina'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '66',
          'pais' => 'Japonesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '67',
          'pais' => 'Letona'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '68',
          'pais' => 'Libanesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '69',
          'pais' => 'Liberiana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '70',
          'pais' => 'Libia'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '71',
          'pais' => 'Lituana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '72',
          'pais' => 'Luxemburguesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '73',
          'pais' => 'Maltesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '74',
          'pais' => 'Marroquí'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '75',
          'pais' => 'Mexicana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '76',
          'pais' => 'Moldava'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '77',
          'pais' => 'Monegasca'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '78',
          'pais' => 'Mongola'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '79',
          'pais' => 'Montenegrina'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '80',
          'pais' => 'Namibia'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '81',
          'pais' => 'Neozelandesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '82',
          'pais' => 'Nicaragüense'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '83',
          'pais' => 'Nigeriana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '84',
          'pais' => 'Norcoreana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '85',
          'pais' => 'Noruega'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '86',
          'pais' => 'Panameña'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '87',
          'pais' => 'Paraguaya'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '88',
          'pais' => 'Peruana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '89',
          'pais' => 'Polaca'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '90',
          'pais' => 'Portuguesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '91',
          'pais' => 'Puertorriqueña'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '92',
          'pais' => 'Rumana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '93',
          'pais' => 'Rusa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '94',
          'pais' => 'Saharaui'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '95',
          'pais' => 'Salvadoreña'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '96',
          'pais' => 'Sancristobaleña'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '97',
          'pais' => 'Santaluciana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '98',
          'pais' => 'Sanvicentina'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '99',
          'pais' => 'Senegalesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '100',
          'pais' => 'Serbia'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '101',
          'pais' => 'Siria'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '102',
          'pais' => 'Sudafricana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '103',
          'pais' => 'Sueca'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '104',
          'pais' => 'Suiza'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '105',
          'pais' => 'Surcoreana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '106',
          'pais' => 'Surinamesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '107',
          'pais' => 'Togolesa'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '108',
          'pais' => 'Ucraniana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '109',
          'pais' => 'Venezolana'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '110',
          'pais' => 'Vietnamita'
        ));
        DB::table('sigeci_paises')->insert(array(
          'id' => '111',
          'pais' => 'Palestina'
        ));
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sigeci_paises');
    }
}
