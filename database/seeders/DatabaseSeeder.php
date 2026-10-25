<?php

namespace Database\Seeders;

use App\Models\ModificationCategory;
use App\Models\ModificationItem;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create(['name'=>'Admin ModifLab','email'=>'admin@modiflab.test','password'=>'password','is_admin'=>true]);
        User::create(['name'=>'Demo Rider','email'=>'demo@modiflab.test','password'=>'password']);

        $vehicles = [
            ['type'=>'mobil','brand'=>'Honda','name'=>'Civic Turbo','slug'=>'honda-civic-turbo','year'=>2023,'base_price'=>525000000,'description'=>'Sedan sporty dengan potensi modifikasi tanpa batas.'],
            ['type'=>'mobil','brand'=>'Toyota','name'=>'GR 86','slug'=>'toyota-gr-86','year'=>2024,'base_price'=>998000000,'description'=>'Platform coupe yang lahir untuk tampil agresif.'],
            ['type'=>'mobil','brand'=>'Suzuki','name'=>'Jimny','slug'=>'suzuki-jimny','year'=>2024,'base_price'=>465000000,'description'=>'Ikon kompak untuk gaya urban maupun petualangan.'],
            ['type'=>'motor','brand'=>'Yamaha','name'=>'XSR 155','slug'=>'yamaha-xsr-155','year'=>2024,'base_price'=>39000000,'description'=>'Gaya heritage dengan teknologi modern.'],
            ['type'=>'motor','brand'=>'Honda','name'=>'CB150R','slug'=>'honda-cb150r','year'=>2023,'base_price'=>31000000,'description'=>'Street fighter lincah untuk eksplorasi gaya.'],
            ['type'=>'motor','brand'=>'Kawasaki','name'=>'Ninja ZX-25R','slug'=>'kawasaki-zx-25r','year'=>2024,'base_price'=>110000000,'description'=>'Supersport empat silinder berkarakter tajam.'],
        ];
        foreach($vehicles as $vehicle) Vehicle::create($vehicle);

        $categories = [
            ['name'=>'Warna','slug'=>'warna','icon'=>'◉','sort_order'=>1],['name'=>'Velg','slug'=>'velg','icon'=>'◌','sort_order'=>2],
            ['name'=>'Body Kit','slug'=>'body-kit','icon'=>'◆','sort_order'=>3],['name'=>'Performa','slug'=>'performa','icon'=>'⚡','sort_order'=>4],
        ];
        foreach($categories as $category) ModificationCategory::create($category);
        $ids = ModificationCategory::pluck('id','slug');
        $items = [
            ['warna','semua','Sunset Orange',0,'#ff5a1f','color','Solid gloss finish'],['warna','semua','Midnight Black',1500000,'#16171b','color','Deep premium black'],['warna','semua','Racing Blue',1800000,'#3267d6','color','Metallic blue finish'],['warna','semua','Arctic White',1200000,'#f3f3ef','color','Clean pearl white'],
            ['velg','semua','Street Silver',3500000,'#8c9098','wheel','Daily fitment'],['velg','semua','Forged Black',8500000,'#17181c','wheel','Lightweight forged'],['velg','semua','Bronze Racing',7200000,'#9b6b32','wheel','Motorsport look'],
            ['body-kit','mobil','Aero Street Kit',12500000,null,'bodykit','Side skirt & front lip'],['body-kit','mobil','GT Spoiler',9500000,null,'spoiler','High downforce style'],['body-kit','motor','Cafe Racer Kit',6800000,null,'bodykit','Tail tidy & belly pan'],
            ['performa','mobil','Stage 1 ECU Tune',7500000,null,'engine','Responsive power delivery'],['performa','motor','Full System Exhaust',5200000,null,'engine','Sharper sound & response'],['performa','semua','Stock Performance',0,null,'engine','Tanpa perubahan performa'],
        ];
        foreach($items as [$cat,$type,$name,$price,$color,$key,$desc]) ModificationItem::create(['category_id'=>$ids[$cat],'vehicle_type'=>$type,'name'=>$name,'slug'=>str($name)->slug().'-'.strtolower($type),'price'=>$price,'color'=>$color,'preview_key'=>$key,'description'=>$desc]);
    }
}
