<?php

use Illuminate\Database\Seeder;
use database\seeds\PaymentTypeSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Initializes the database 
        DB::table('tblpaymenttype')->insert(array(
             array('strPaymentTypeID'=>'1','strPaymentType'=>'Initial Bill'),
             array('strPaymentTypeID'=>'2','strPaymentType'=>'Down Payment'),
             array('strPaymentTypeID'=>'3','strPaymentType'=>'Initial Payment'),
             array('strPaymentTypeID'=>'4','strPaymentType'=>'Additional Bill'),
             array('strPaymentTypeID'=>'5','strPaymentType'=>'Additional Payment'),
             array('strPaymentTypeID'=>'6','strPaymentType'=>'Time Penalty Bill'),
             array('strPaymentTypeID'=>'7','strPaymentType'=>'Broken/Lost Penalty Bill'),
             array('strPaymentTypeID'=>'8','strPaymentType'=>'Boat Reservation Bill'),
             array('strPaymentTypeID'=>'9','strPaymentType'=>'Boat Reservation Payment'),
             array('strPaymentTypeID'=>'10','strPaymentType'=>'Extend Item Bill'),
             array('strPaymentTypeID'=>'11', 'strPaymentType'=>'Item Rental Bill'),
             array('strPaymentTypeID'=>'12', 'strPaymentType'=>'Item Rental Payment'),
             array('strPaymentTypeID'=>'13', 'strPaymentType'=>'Time Penalty Payment'),
             array('strPaymentTypeID'=>'14', 'strPaymentType'=>'Broken/Lost Penalty Payment'),
             array('strPaymentTypeID'=>'15', 'strPaymentType'=>'Extend Item Payment'),
             array('strPaymentTypeID'=>'16', 'strPaymentType'=>'Beach Activity Bill'),
             array('strPaymentTypeID'=>'17', 'strPaymentType'=>'Beach Activity Payment'),
             array('strPaymentTypeID'=>'18', 'strPaymentType'=>'Fee Bill'),
             array('strPaymentTypeID'=>'19', 'strPaymentType'=>'Fee Payment'),
             array('strPaymentTypeID'=>'20', 'strPaymentType'=>'Additional Room Bill'),
             array('strPaymentTypeID'=>'21', 'strPaymentType'=>'Additional Room Payment'),
             array('strPaymentTypeID'=>'22', 'strPaymentType'=>'Upgrade Room Bill'),
             array('strPaymentTypeID'=>'23', 'strPaymentType'=>'Upgrade Room Payment'),
             array('strPaymentTypeID'=>'24', 'strPaymentType'=>'Extend Stay Bill'),
             array('strPaymentTypeID'=>'25', 'strPaymentType'=>'Extend Stay Payment'),
             array('strPaymentTypeID'=>'26', 'strPaymentType'=>'Item Rental Package Reference'),
             array('strPaymentTypeID'=>'27', 'strPaymentType'=>'Activity Package Reference'),
             array('strPaymentTypeID'=>'28', 'strPaymentType'=>'Check out Payment'),
             array('strPaymentTypeID'=>'29', 'strPaymentType'=>'Bill Deductions')
        ));
        
        DB::table('tblWebContent')->insert(array(
            array('strPageTitle' => 'Home Page', 'strHeaderDescription' => 'Header Description', 'strHeaderImage' => '/img/header-1.jpeg'),
            array('strPageTitle' => 'Accommodation', 'strHeaderDescription' => 'Header Description', 'strHeaderImage' => '/img/header-2.jpg'),
            array('strPageTitle' => 'Packages', 'strHeaderDescription' => 'Header Description', 'strHeaderImage' => '/img/header-6.jpg'),
            array('strPageTitle' => 'Activities', 'strHeaderDescription' => 'Header Description', 'strHeaderImage' => '/img/header-7.jpg'),
            array('strPageTitle' => 'Location', 'strHeaderDescription' => 'Header Description', 'strHeaderImage' => '/img/header-3.jpg'),
            array('strPageTitle' => 'About Us', 'strHeaderDescription' => 'Header Description', 'strHeaderImage' => '/img/header-4.jpg'),
            array('strPageTitle' => 'Contact Us', 'strHeaderDescription' => 'Header Description', 'strHeaderImage' => '/img/header-5.jpg'),
        ));
        
        //for verification
        $this->command->info("Payment Type table seeded :)");
    }
}
