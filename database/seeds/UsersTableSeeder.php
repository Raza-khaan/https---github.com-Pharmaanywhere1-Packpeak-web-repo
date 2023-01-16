<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
//for seeding users's
                    //[ 
          //      'id'             => null,
        //        'name'           =>'super Admin',
      //          'initials_name'  =>'S.A.',
               // 'first_name'     =>'super',
             //   'last_name'      =>'Admin',
           //     'email'          =>'super@gmail.in',
         //       'company_name'   =>'superAdmin',
       //         'phone'          =>'049999999999',
     //           'registration_no'=>'PHA0012350020202008',
              //  'address'        =>'Super Admin Panel',
            //    'host_name'      =>'superadmin',
          //      'password'       =>'$2y$10$40enDFKxi7XV9oN.8Tsof.1WaMkqdzKURD9/3hDHv4J/TI0HUjKeu',
        //        'website_id'     =>1,
      //          'subscription'   =>1,
    //        ],
        

        User::insert($users);
    }
}
