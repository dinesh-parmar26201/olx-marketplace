<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Demo Users
        $users = [
            [
                'name' => 'Rahul Sharma',
                'email' => 'rahul@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+91 98765 43210',
                'city' => 'Mumbai',
            ],
            [
                'name' => 'Priya Patel',
                'email' => 'priya@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+91 98223 34455',
                'city' => 'Ahmedabad',
            ],
            [
                'name' => 'Vikram Reddy',
                'email' => 'vikram@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+91 99887 76655',
                'city' => 'Bangalore',
            ],
            [
                'name' => 'Ananya Sen',
                'email' => 'ananya@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+91 98112 23344',
                'city' => 'Delhi',
            ],
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $createdUsers[] = User::firstOrCreate(['email' => $userData['email']], $userData);
        }

        // 2. Create Categories & Subcategories
        $categoryData = [
            [
                'name' => 'Cars & Vehicles',
                'slug' => 'cars-and-vehicles',
                'icon' => 'fa-car',
                'description' => 'Find used cars, commercial vehicles, and automotive accessories',
                'subcategories' => [
                    ['name' => 'Cars', 'slug' => 'cars'],
                    ['name' => 'Motorcycles & Scooters', 'slug' => 'motorcycles-and-scooters'],
                    ['name' => 'Commercial Vehicles', 'slug' => 'commercial-vehicles'],
                    ['name' => 'Spare Parts & Accessories', 'slug' => 'spare-parts'],
                ]
            ],
            [
                'name' => 'Properties',
                'slug' => 'properties',
                'icon' => 'fa-building',
                'description' => 'Houses, apartments, shops, and offices for rent or sale',
                'subcategories' => [
                    ['name' => 'For Sale: Houses & Apartments', 'slug' => 'for-sale-houses-apartments'],
                    ['name' => 'For Rent: Houses & Apartments', 'slug' => 'for-rent-houses-apartments'],
                    ['name' => 'Lands & Plots', 'slug' => 'lands-plots'],
                    ['name' => 'Shops & Offices', 'slug' => 'shops-offices'],
                ]
            ],
            [
                'name' => 'Mobiles & Tablets',
                'slug' => 'mobiles-and-tablets',
                'icon' => 'fa-mobile-screen',
                'description' => 'Smartphones, iPhones, iPads, and phone accessories',
                'subcategories' => [
                    ['name' => 'Mobile Phones', 'slug' => 'mobile-phones'],
                    ['name' => 'Accessories', 'slug' => 'mobile-accessories'],
                    ['name' => 'Tablets', 'slug' => 'tablets'],
                ]
            ],
            [
                'name' => 'Electronics & Appliances',
                'slug' => 'electronics-and-appliances',
                'icon' => 'fa-tv',
                'description' => 'Laptops, TVs, audio, gaming consoles, and home appliances',
                'subcategories' => [
                    ['name' => 'Computers & Laptops', 'slug' => 'computers-laptops'],
                    ['name' => 'TVs, Video & Audio', 'slug' => 'tvs-video-audio'],
                    ['name' => 'Kitchen Appliances', 'slug' => 'kitchen-appliances'],
                    ['name' => 'Air Conditioners & Coolers', 'slug' => 'acs-coolers'],
                ]
            ],
            [
                'name' => 'Furniture & Decor',
                'slug' => 'furniture-and-decor',
                'icon' => 'fa-couch',
                'description' => 'Home & office furniture, living room sets, wardrobes, and decor',
                'subcategories' => [
                    ['name' => 'Sofa & Dining Sets', 'slug' => 'sofa-dining'],
                    ['name' => 'Beds & Wardrobes', 'slug' => 'beds-wardrobes'],
                    ['name' => 'Home Decor & Garden', 'slug' => 'home-decor'],
                    ['name' => 'Office Furniture', 'slug' => 'office-furniture'],
                ]
            ],
            [
                'name' => 'Services',
                'slug' => 'services',
                'icon' => 'fa-screwdriver-wrench',
                'description' => 'Professional services, repairs, education, logistics, and digital services',
                'subcategories' => [
                    ['name' => 'Home Repair & Cleaning', 'slug' => 'home-repair-cleaning'],
                    ['name' => 'Packers & Movers', 'slug' => 'packers-and-movers'],
                    ['name' => 'Electronics Repair', 'slug' => 'electronics-repair'],
                    ['name' => 'Web & Tech Services', 'slug' => 'web-tech-services'],
                    ['name' => 'Education & Classes', 'slug' => 'education-classes'],
                ]
            ],
            [
                'name' => 'Fashion & Beauty',
                'slug' => 'fashion-and-beauty',
                'icon' => 'fa-shirt',
                'description' => 'Clothing, watches, footwear, and beauty essentials',
                'subcategories' => [
                    ['name' => "Men's Fashion", 'slug' => 'mens-fashion'],
                    ['name' => "Women's Fashion", 'slug' => 'womens-fashion'],
                    ['name' => 'Watches & Jewelry', 'slug' => 'watches-jewelry'],
                ]
            ],
            [
                'name' => 'Jobs',
                'slug' => 'jobs',
                'icon' => 'fa-briefcase',
                'description' => 'Find local and remote job openings and freelance opportunities',
                'subcategories' => [
                    ['name' => 'Data Entry & Back Office', 'slug' => 'data-entry-backoffice'],
                    ['name' => 'Sales & Marketing', 'slug' => 'sales-marketing'],
                    ['name' => 'Delivery & Driver', 'slug' => 'delivery-driver'],
                    ['name' => 'IT & Software Engineer', 'slug' => 'it-software-engineer'],
                ]
            ],
        ];

        $categoriesMap = [];
        $subcategoriesMap = [];

        foreach ($categoryData as $catIndex => $catInfo) {
            $cat = Category::firstOrCreate(
                ['slug' => $catInfo['slug']],
                [
                    'name' => $catInfo['name'],
                    'icon' => $catInfo['icon'],
                    'description' => $catInfo['description'],
                    'sort_order' => $catIndex,
                ]
            );
            $categoriesMap[$catInfo['slug']] = $cat;

            foreach ($catInfo['subcategories'] as $subIndex => $subInfo) {
                $sub = Category::firstOrCreate(
                    ['slug' => $subInfo['slug']],
                    [
                        'name' => $subInfo['name'],
                        'parent_id' => $cat->id,
                        'icon' => $catInfo['icon'],
                        'sort_order' => $subIndex,
                    ]
                );
                $subcategoriesMap[$subInfo['slug']] = $sub;
            }
        }

        // 3. Create Sample Listings (Products & Services)
        $sampleListings = [
            [
                'title' => 'Hyundai Creta SX (O) 1.5 Petrol 2021 Mint Condition',
                'type' => 'product',
                'category_slug' => 'cars-and-vehicles',
                'subcategory_slug' => 'cars',
                'description' => "Single owner driven Hyundai Creta SX (O) top model. Fully loaded with panoramic sunroof, ventilated front seats, Bose 8-speaker sound system, wireless charging, and 10.25 inch touchscreen.\n\n- Year: 2021\n- KM Driven: 28,500 km\n- Fuel: Petrol\n- Transmission: Manual\n- Insurance: Valid till Nov 2026\n- Condition: Scratchless, company serviced\nPrice is slightly negotiable for serious buyers only.",
                'country' => 'India',
                'state' => 'Maharashtra',
                'city' => 'Mumbai',
                'area' => 'Andheri West',
                'price' => 1250000,
                'price_type' => 'negotiable',
                'condition' => 'used',
                'featured' => true,
                'user_index' => 0,
                'images' => [
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80'
                ]
            ],
            [
                'title' => 'iPhone 15 Pro Max 256GB Natural Titanium (With Bill & Box)',
                'type' => 'product',
                'category_slug' => 'mobiles-and-tablets',
                'subcategory_slug' => 'mobile-phones',
                'description' => "Apple iPhone 15 Pro Max 256GB in pristine Natural Titanium. 100% battery health, zero scratches or dents. Comes with original box, braided USB-C cable, invoice, and Spigen rugged case.\n\n- Purchased in India with official Apple warranty.\n- Face ID, Action button and cameras working perfectly.\n- Tempered glass applied since day 1.",
                'country' => 'India',
                'state' => 'Maharashtra',
                'city' => 'Mumbai',
                'area' => 'Bandra West',
                'price' => 108000,
                'price_type' => 'fixed',
                'condition' => 'used',
                'featured' => true,
                'user_index' => 0,
                'images' => [
                    'https://images.unsplash.com/photo-1695048133142-1a20484d2569?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?auto=format&fit=crop&w=800&q=80'
                ]
            ],
            [
                'title' => 'MacBook Pro 14 M2 Pro 16GB RAM 512GB SSD Space Grey',
                'type' => 'product',
                'category_slug' => 'electronics-and-appliances',
                'subcategory_slug' => 'computers-laptops',
                'description' => "Selling my sparingly used Apple MacBook Pro 14 inch with M2 Pro processor, 16GB unified memory, and 512GB ultra-fast SSD.\n\n- Liquid Retina XDR display (120Hz ProMotion)\n- Battery cycle count: only 42 cycles (98% health)\n- Includes original 67W MagSafe fast charger and box\nIdeal for coding, 4K video editing, and graphic design.",
                'country' => 'India',
                'state' => 'Karnataka',
                'city' => 'Bangalore',
                'area' => 'Koramangala',
                'price' => 135000,
                'price_type' => 'negotiable',
                'condition' => 'used',
                'featured' => true,
                'user_index' => 2,
                'images' => [
                    'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?auto=format&fit=crop&w=800&q=80'
                ]
            ],
            [
                'title' => 'Professional Packers & Movers Service - Home & Office Relocation',
                'type' => 'service',
                'category_slug' => 'services',
                'subcategory_slug' => 'packers-and-movers',
                'description' => "Trusted and reliable household shifting and commercial relocation services across India.\n\nServices Included:\n- Multi-layer bubble wrap & corrugated box packing\n- Safe loading and unloading with trained staff\n- Dedicated container vehicles for scratch-free transit\n- Transit insurance coverage\nCall now for instant free quote and doorstep inspection!",
                'country' => 'India',
                'state' => 'Karnataka',
                'city' => 'Bangalore',
                'area' => 'Indiranagar',
                'price' => 4500,
                'price_type' => 'negotiable',
                'condition' => 'not_applicable',
                'featured' => false,
                'user_index' => 2,
                'images' => [
                    'https://images.unsplash.com/photo-1600518464441-9154a4dea21b?auto=format&fit=crop&w=800&q=80',
                ]
            ],
            [
                'title' => 'Spacious 3 BHK Luxury Apartment in Satellite with 2 Car Parkings',
                'type' => 'product',
                'category_slug' => 'properties',
                'subcategory_slug' => 'for-sale-houses-apartments',
                'description' => "Prime location 3 BHK semi-furnished luxury apartment available in Satellite, Ahmedabad. East facing, 100% Vastu compliant.\n\n- Super Built-up: 1,950 sq. ft.\n- Modern modular kitchen with chimney\n- Piped gas connection & wooden flooring in master bedroom\n- Amenities: Gymnasium, 24/7 security, club house, children play area\nReady to move. Clear title documents.",
                'country' => 'India',
                'state' => 'Gujarat',
                'city' => 'Ahmedabad',
                'area' => 'Satellite',
                'price' => 8800000,
                'price_type' => 'negotiable',
                'condition' => 'used',
                'featured' => true,
                'user_index' => 1,
                'images' => [
                    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80'
                ]
            ],
            [
                'title' => 'Complete AC Repair, Gas Refill & Deep Jet Cleaning Service',
                'type' => 'service',
                'category_slug' => 'services',
                'subcategory_slug' => 'home-repair-cleaning',
                'description' => "Expert technician for all brands of Split and Window Air Conditioners (Daikin, Voltas, LG, Samsung, Hitachi).\n\nServices:\n- Full foam Jet cleaning\n- 100% pure R32/R410 gas refilling with leakage check\n- PCB board repair & capacitor replacement\n- 30-day service warranty guaranteed\nDoorstep service available within 60 minutes across Ahmedabad.",
                'country' => 'India',
                'state' => 'Gujarat',
                'city' => 'Ahmedabad',
                'area' => 'Navrangpura',
                'price' => 499,
                'price_type' => 'fixed',
                'condition' => 'not_applicable',
                'featured' => false,
                'user_index' => 1,
                'images' => [
                    'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=800&q=80',
                ]
            ],
            [
                'title' => 'Royal Enfield Classic 350 Stealth Black (Dual Channel ABS)',
                'type' => 'product',
                'category_slug' => 'cars-and-vehicles',
                'subcategory_slug' => 'motorcycles-and-scooters',
                'description' => "Royal Enfield Classic 350 Reborn edition in Stealth Black matte finish with alloy wheels and tubeless tyres.\n\n- Model: 2022\n- Odometer: 14,200 km\n- Dual channel ABS with rear disc\n- Added touring mirrors, crash guard with rope, and sump guard\n- Fully insured, all service records available at authorized showroom.",
                'country' => 'India',
                'state' => 'Delhi',
                'city' => 'Delhi',
                'area' => 'Connaught Place',
                'price' => 172000,
                'price_type' => 'negotiable',
                'condition' => 'used',
                'featured' => true,
                'user_index' => 3,
                'images' => [
                    'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80'
                ]
            ],
            [
                'title' => 'Solid Teak Wood 6-Seater Dining Table with Cushioned Chairs',
                'type' => 'product',
                'category_slug' => 'furniture-and-decor',
                'subcategory_slug' => 'sofa-dining',
                'description' => "Crafted from genuine pure Sagwan (Teak Wood) with natural honey finish and 10mm tempered glass top.\n\n- Includes 6 ergonomically designed cushioned chairs in beige upholstery\n- Excellent condition, no termite or wobbling issues\n- Dimensions: 6ft x 3.5ft\nReason for selling: Renovating home with new interior theme.",
                'country' => 'India',
                'state' => 'Delhi',
                'city' => 'Delhi',
                'area' => 'Saket',
                'price' => 24500,
                'price_type' => 'negotiable',
                'condition' => 'used',
                'featured' => false,
                'user_index' => 3,
                'images' => [
                    'https://images.unsplash.com/photo-1617806118233-18e1de247200?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1538688525198-9b88f6f53126?auto=format&fit=crop&w=800&q=80'
                ]
            ],
            [
                'title' => 'Custom Website & Mobile App Development Service',
                'type' => 'service',
                'category_slug' => 'services',
                'subcategory_slug' => 'web-tech-services',
                'description' => "Senior full-stack software engineer offering professional web development, e-commerce, and SaaS solutions.\n\nTech Stack:\n- Laravel, PHP, MySQL, REST APIs\n- React, Vue.js, Tailwind CSS\n- Flutter Mobile Apps for iOS & Android\n- Payment Gateway & SMS Integration\nFast delivery, responsive design, and 3 months free maintenance.",
                'country' => 'India',
                'state' => 'Maharashtra',
                'city' => 'Mumbai',
                'area' => 'Powai',
                'price' => 15000,
                'price_type' => 'negotiable',
                'condition' => 'not_applicable',
                'featured' => true,
                'user_index' => 0,
                'images' => [
                    'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80',
                ]
            ],
            [
                'title' => 'Sony PlayStation 5 Disc Edition with 2 DualSense Controllers & 3 Games',
                'type' => 'product',
                'category_slug' => 'electronics-and-appliances',
                'subcategory_slug' => 'tvs-video-audio',
                'description' => "Sony PS5 (Disc version) 825GB in like-new condition. Barely played 20-30 hours.\n\nIncluded in package:\n- Console with original stand, HDMI 2.1 cable, power cord\n- 2x Original Sony DualSense Wireless Controllers (White & Midnight Black)\n- 3 Disc Games: Spider-Man 2, God of War Ragnarok, and FIFA 24\nBill, box, and all packaging intact.",
                'country' => 'India',
                'state' => 'Karnataka',
                'city' => 'Bangalore',
                'area' => 'Whitefield',
                'price' => 42000,
                'price_type' => 'fixed',
                'condition' => 'used',
                'featured' => false,
                'user_index' => 2,
                'images' => [
                    'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?auto=format&fit=crop&w=800&q=80',
                ]
            ],
            [
                'title' => 'Modern 2 BHK Furnished Flat for Rent in Hinjewadi Phase 1',
                'type' => 'product',
                'category_slug' => 'properties',
                'subcategory_slug' => 'for-rent-houses-apartments',
                'description' => "Fully furnished 2 BHK apartment in a gated high-rise society right next to IT Park Hinjewadi Phase 1.\n\n- Includes: Sofa, dining table, double beds with mattresses, 2 ACs, refrigerator, washing machine, and RO water purifier\n- Society features swimming pool, clubhouse, 100% power backup, 24x7 security\n- Covered car parking included\nRent: Rs 26,000/month. Deposit: 2 months.",
                'country' => 'India',
                'state' => 'Maharashtra',
                'city' => 'Pune',
                'area' => 'Hinjewadi',
                'price' => 26000,
                'price_type' => 'fixed',
                'condition' => 'used',
                'featured' => true,
                'user_index' => 0,
                'images' => [
                    'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80',
                ]
            ],
            [
                'title' => 'Honda Activa 6G 2023 - Single Owner Low KM',
                'type' => 'product',
                'category_slug' => 'cars-and-vehicles',
                'subcategory_slug' => 'motorcycles-and-scooters',
                'description' => "Honda Activa 6G Pearl Siren Blue color. Barely ridden 6,400 KM by a college student.\n\n- Engine in flawless condition, excellent mileage (50+ kmpl)\n- Telescopic suspension and engine kill switch\n- Original 2 keys, toolkit, owner manual, and insurance valid\nSelling as relocating out of city.",
                'country' => 'India',
                'state' => 'Maharashtra',
                'city' => 'Pune',
                'area' => 'Kothrud',
                'price' => 68000,
                'price_type' => 'negotiable',
                'condition' => 'used',
                'featured' => false,
                'user_index' => 0,
                'images' => [
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                ]
            ],
        ];

        foreach ($sampleListings as $listingItem) {
            $cat = $categoriesMap[$listingItem['category_slug']];
            $sub = $subcategoriesMap[$listingItem['subcategory_slug']] ?? null;
            $user = $createdUsers[$listingItem['user_index']];

            $slug = Str::slug($listingItem['title']) . '-' . Str::random(5);

            $listing = Listing::create([
                'user_id' => $user->id,
                'type' => $listingItem['type'],
                'title' => $listingItem['title'],
                'slug' => strtolower($slug),
                'description' => $listingItem['description'],
                'category_id' => $cat->id,
                'subcategory_id' => $sub ? $sub->id : null,
                'country' => $listingItem['country'],
                'state' => $listingItem['state'],
                'city' => $listingItem['city'],
                'city_slug' => Str::slug($listingItem['city']),
                'area' => $listingItem['area'],
                'price' => $listingItem['price'],
                'price_type' => $listingItem['price_type'],
                'condition' => $listingItem['condition'],
                'status' => 'active',
                'featured' => $listingItem['featured'],
                'views_count' => rand(45, 680),
                'contact_phone' => $user->phone,
                'hide_phone' => false,
            ]);

            // Add images
            foreach ($listingItem['images'] as $imgIndex => $imgUrl) {
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image_path' => $imgUrl,
                    'is_primary' => ($imgIndex === 0),
                    'sort_order' => $imgIndex,
                ]);
            }
        }
    }
}
