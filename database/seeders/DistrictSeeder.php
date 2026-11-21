<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coverageNotice = 'শুধু ভৌগোলিক জেলা-সূচি। বিদ্যুৎ বিতরণ সংস্থাভিত্তিক সেবার আওতা এখনো যাচাই করা হয়নি। / Geographic district index only. Utility-specific service coverage is not yet verified.';

        $districts = [
            ['district-dhaka', 'ঢাকা / Dhaka'],
            ['district-faridpur', 'ফরিদপুর / Faridpur'],
            ['district-gazipur', 'গাজীপুর / Gazipur'],
            ['district-gopalganj', 'গোপালগঞ্জ / Gopalganj'],
            ['district-kishoreganj', 'কিশোরগঞ্জ / Kishoreganj'],
            ['district-madaripur', 'মাদারীপুর / Madaripur'],
            ['district-manikganj', 'মানিকগঞ্জ / Manikganj'],
            ['district-munshiganj', 'মুন্সিগঞ্জ / Munshiganj'],
            ['district-narayanganj', 'নারায়ণগঞ্জ / Narayanganj'],
            ['district-narsingdi', 'নরসিংদী / Narsingdi'],
            ['district-rajbari', 'রাজবাড়ী / Rajbari'],
            ['district-shariatpur', 'শরীয়তপুর / Shariatpur'],
            ['district-tangail', 'টাঙ্গাইল / Tangail'],
            ['district-bandarban', 'বান্দরবান / Bandarban'],
            ['district-brahmanbaria', 'ব্রাহ্মণবাড়িয়া / Brahmanbaria'],
            ['district-chandpur', 'চাঁদপুর / Chandpur'],
            ['district-chattogram', 'চট্টগ্রাম / Chattogram'],
            ['district-coxs-bazar', 'কক্সবাজার / Cox\'s Bazar'],
            ['district-cumilla', 'কুমিল্লা / Cumilla'],
            ['district-feni', 'ফেনী / Feni'],
            ['district-khagrachhari', 'খাগড়াছড়ি / Khagrachhari'],
            ['district-lakshmipur', 'লক্ষ্মীপুর / Lakshmipur'],
            ['district-noakhali', 'নোয়াখালী / Noakhali'],
            ['district-rangamati', 'রাঙামাটি / Rangamati'],
            ['district-bagerhat', 'বাগেরহাট / Bagerhat'],
            ['district-chuadanga', 'চুয়াডাঙ্গা / Chuadanga'],
            ['district-jashore', 'যশোর / Jashore'],
            ['district-jhenaidah', 'ঝিনাইদহ / Jhenaidah'],
            ['district-khulna', 'খুলনা / Khulna'],
            ['district-kushtia', 'কুষ্টিয়া / Kushtia'],
            ['district-magura', 'মাগুরা / Magura'],
            ['district-meherpur', 'মেহেরপুর / Meherpur'],
            ['district-narail', 'নড়াইল / Narail'],
            ['district-satkhira', 'সাতক্ষীরা / Satkhira'],
            ['district-jamalpur', 'জামালপুর / Jamalpur'],
            ['district-mymensingh', 'ময়মনসিংহ / Mymensingh'],
            ['district-netrokona', 'নেত্রকোণা / Netrokona'],
            ['district-sherpur', 'শেরপুর / Sherpur'],
            ['district-bogura', 'বগুড়া / Bogura'],
            ['district-chapainawabganj', 'চাঁপাইনবাবগঞ্জ / Chapainawabganj'],
            ['district-joypurhat', 'জয়পুরহাট / Joypurhat'],
            ['district-naogaon', 'নওগাঁ / Naogaon'],
            ['district-natore', 'নাটোর / Natore'],
            ['district-pabna', 'পাবনা / Pabna'],
            ['district-rajshahi', 'রাজশাহী / Rajshahi'],
            ['district-sirajganj', 'সিরাজগঞ্জ / Sirajganj'],
            ['district-dinajpur', 'দিনাজপুর / Dinajpur'],
            ['district-gaibandha', 'গাইবান্ধা / Gaibandha'],
            ['district-kurigram', 'কুড়িগ্রাম / Kurigram'],
            ['district-lalmonirhat', 'লালমনিরহাট / Lalmonirhat'],
            ['district-nilphamari', 'নীলফামারী / Nilphamari'],
            ['district-panchagarh', 'পঞ্চগড় / Panchagarh'],
            ['district-rangpur', 'রংপুর / Rangpur'],
            ['district-thakurgaon', 'ঠাকুরগাঁও / Thakurgaon'],
            ['district-habiganj', 'হবিগঞ্জ / Habiganj'],
            ['district-moulvibazar', 'মৌলভীবাজার / Moulvibazar'],
            ['district-sunamganj', 'সুনামগঞ্জ / Sunamganj'],
            ['district-sylhet', 'সিলেট / Sylhet'],
            ['district-barguna', 'বরগুনা / Barguna'],
            ['district-barishal', 'বরিশাল / Barishal'],
            ['district-bhola', 'ভোলা / Bhola'],
            ['district-jhalokati', 'ঝালকাঠি / Jhalokati'],
            ['district-patuakhali', 'পটুয়াখালী / Patuakhali'],
            ['district-pirojpur', 'পিরোজপুর / Pirojpur'],
        ];

        foreach ($districts as [$slug, $name]) {
            Zone::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => $coverageNotice,
                    'is_active' => true,
                ]
            );
        }
    }
}
