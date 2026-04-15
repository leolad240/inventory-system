<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\PurchaseOrder;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────
        // USERS
        // ─────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Daniel Admin',
            'email'    => 'admin@demo.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $staff1 = User::create([
            'name'     => 'Sarah Johnson',
            'email'    => 'sarah@demo.com',
            'password' => Hash::make('password'),
            'role'     => 'staff',
        ]);

        $staff2 = User::create([
            'name'     => 'Mike Thompson',
            'email'    => 'mike@demo.com',
            'password' => Hash::make('password'),
            'role'     => 'staff',
        ]);

        // ─────────────────────────────────────────
        // CATEGORIES
        // ─────────────────────────────────────────
        $electronics  = Category::create(['name' => 'Electronics',      'description' => 'Phones, cables, accessories & gadgets']);
        $clothing     = Category::create(['name' => 'Clothing',         'description' => 'Apparel, footwear & fashion accessories']);
        $food         = Category::create(['name' => 'Food & Beverages', 'description' => 'Groceries, snacks & drinks']);
        $stationery   = Category::create(['name' => 'Stationery',       'description' => 'Office and school supplies']);
        $household    = Category::create(['name' => 'Household',        'description' => 'Cleaning, kitchen & home essentials']);
        $health       = Category::create(['name' => 'Health & Beauty',  'description' => 'Personal care and wellness products']);

        // ─────────────────────────────────────────
        // PRODUCTS  (30 products across 6 categories)
        // ─────────────────────────────────────────
        $productData = [
            // Electronics
            ['name' => 'USB-C Cable 2m',           'sku' => 'ELEC-001', 'cat' => $electronics,  'cost' => 3.50,  'price' => 12.99, 'qty' => 150, 'reorder' => 25,  'unit' => 'piece'],
            ['name' => 'USB-A to Micro USB Cable',  'sku' => 'ELEC-002', 'cat' => $electronics,  'cost' => 2.00,  'price' => 7.99,  'qty' => 80,  'reorder' => 20,  'unit' => 'piece'],
            ['name' => 'Wireless Mouse',            'sku' => 'ELEC-003', 'cat' => $electronics,  'cost' => 8.00,  'price' => 24.99, 'qty' => 45,  'reorder' => 10,  'unit' => 'piece'],
            ['name' => 'Mechanical Keyboard',       'sku' => 'ELEC-004', 'cat' => $electronics,  'cost' => 22.00, 'price' => 69.99, 'qty' => 18,  'reorder' => 5,   'unit' => 'piece'],
            ['name' => 'Bluetooth Speaker',         'sku' => 'ELEC-005', 'cat' => $electronics,  'cost' => 15.00, 'price' => 49.99, 'qty' => 7,   'reorder' => 10,  'unit' => 'piece'],
            ['name' => 'Phone Screen Protector',    'sku' => 'ELEC-006', 'cat' => $electronics,  'cost' => 1.00,  'price' => 4.99,  'qty' => 200, 'reorder' => 40,  'unit' => 'piece'],
            ['name' => 'Power Bank 10000mAh',       'sku' => 'ELEC-007', 'cat' => $electronics,  'cost' => 12.00, 'price' => 39.99, 'qty' => 30,  'reorder' => 8,   'unit' => 'piece'],
            ['name' => 'Wireless Earbuds',          'sku' => 'ELEC-008', 'cat' => $electronics,  'cost' => 18.00, 'price' => 59.99, 'qty' => 0,   'reorder' => 5,   'unit' => 'piece'],

            // Clothing
            ['name' => 'Cotton T-Shirt (S)',        'sku' => 'CLO-001',  'cat' => $clothing,     'cost' => 4.00,  'price' => 15.99, 'qty' => 120, 'reorder' => 20,  'unit' => 'piece'],
            ['name' => 'Cotton T-Shirt (M)',        'sku' => 'CLO-002',  'cat' => $clothing,     'cost' => 4.00,  'price' => 15.99, 'qty' => 200, 'reorder' => 30,  'unit' => 'piece'],
            ['name' => 'Cotton T-Shirt (L)',        'sku' => 'CLO-003',  'cat' => $clothing,     'cost' => 4.00,  'price' => 15.99, 'qty' => 95,  'reorder' => 20,  'unit' => 'piece'],
            ['name' => 'Denim Jeans (32)',          'sku' => 'CLO-004',  'cat' => $clothing,     'cost' => 18.00, 'price' => 59.99, 'qty' => 25,  'reorder' => 8,   'unit' => 'piece'],
            ['name' => 'Denim Jeans (34)',          'sku' => 'CLO-005',  'cat' => $clothing,     'cost' => 18.00, 'price' => 59.99, 'qty' => 18,  'reorder' => 8,   'unit' => 'piece'],
            ['name' => 'Running Shoes (42)',        'sku' => 'CLO-006',  'cat' => $clothing,     'cost' => 25.00, 'price' => 79.99, 'qty' => 0,   'reorder' => 5,   'unit' => 'piece'],
            ['name' => 'Sports Cap',               'sku' => 'CLO-007',  'cat' => $clothing,     'cost' => 3.50,  'price' => 12.99, 'qty' => 60,  'reorder' => 15,  'unit' => 'piece'],

            // Food & Beverages
            ['name' => 'Mineral Water 500ml',       'sku' => 'FNB-001',  'cat' => $food,         'cost' => 0.30,  'price' => 0.99,  'qty' => 500, 'reorder' => 100, 'unit' => 'piece'],
            ['name' => 'Orange Juice 1L',           'sku' => 'FNB-002',  'cat' => $food,         'cost' => 1.20,  'price' => 3.49,  'qty' => 60,  'reorder' => 20,  'unit' => 'piece'],
            ['name' => 'Instant Noodles (pack)',    'sku' => 'FNB-003',  'cat' => $food,         'cost' => 0.25,  'price' => 0.89,  'qty' => 300, 'reorder' => 80,  'unit' => 'pack'],
            ['name' => 'Chocolate Bar 100g',        'sku' => 'FNB-004',  'cat' => $food,         'cost' => 0.50,  'price' => 1.99,  'qty' => 150, 'reorder' => 40,  'unit' => 'piece'],
            ['name' => 'Ground Coffee 250g',        'sku' => 'FNB-005',  'cat' => $food,         'cost' => 3.00,  'price' => 8.99,  'qty' => 40,  'reorder' => 15,  'unit' => 'piece'],

            // Stationery
            ['name' => 'Notebook A4 (80 pages)',    'sku' => 'STA-001',  'cat' => $stationery,   'cost' => 0.80,  'price' => 2.99,  'qty' => 300, 'reorder' => 50,  'unit' => 'piece'],
            ['name' => 'Ballpoint Pens (12pk)',     'sku' => 'STA-002',  'cat' => $stationery,   'cost' => 1.00,  'price' => 3.49,  'qty' => 4,   'reorder' => 20,  'unit' => 'pack'],
            ['name' => 'Highlighter Set 5-colour',  'sku' => 'STA-003',  'cat' => $stationery,   'cost' => 1.50,  'price' => 5.99,  'qty' => 45,  'reorder' => 10,  'unit' => 'piece'],
            ['name' => 'Stapler + 1000 staples',   'sku' => 'STA-004',  'cat' => $stationery,   'cost' => 2.50,  'price' => 9.99,  'qty' => 20,  'reorder' => 5,   'unit' => 'piece'],

            // Household
            ['name' => 'Kitchen Sponge (3pk)',      'sku' => 'HOU-001',  'cat' => $household,    'cost' => 0.60,  'price' => 2.49,  'qty' => 80,  'reorder' => 15,  'unit' => 'pack'],
            ['name' => 'Dish Soap 500ml',           'sku' => 'HOU-002',  'cat' => $household,    'cost' => 0.90,  'price' => 2.99,  'qty' => 45,  'reorder' => 15,  'unit' => 'piece'],
            ['name' => 'Laundry Detergent 1kg',     'sku' => 'HOU-003',  'cat' => $household,    'cost' => 2.00,  'price' => 6.99,  'qty' => 35,  'reorder' => 10,  'unit' => 'kg'],
            ['name' => 'Toilet Paper (12 rolls)',   'sku' => 'HOU-004',  'cat' => $household,    'cost' => 3.00,  'price' => 8.99,  'qty' => 6,   'reorder' => 15,  'unit' => 'pack'],

            // Health & Beauty
            ['name' => 'Hand Sanitizer 200ml',      'sku' => 'HLT-001',  'cat' => $health,       'cost' => 1.00,  'price' => 3.99,  'qty' => 90,  'reorder' => 20,  'unit' => 'piece'],
            ['name' => 'Paracetamol 500mg (16pk)',  'sku' => 'HLT-002',  'cat' => $health,       'cost' => 0.80,  'price' => 2.99,  'qty' => 50,  'reorder' => 15,  'unit' => 'pack'],
            ['name' => 'Shampoo 400ml',             'sku' => 'HLT-003',  'cat' => $health,       'cost' => 2.00,  'price' => 6.99,  'qty' => 40,  'reorder' => 10,  'unit' => 'piece'],
        ];

        $products = collect($productData)->map(function ($data) use ($admin) {
            $product = Product::create([
                'category_id'   => $data['cat']->id,
                'name'          => $data['name'],
                'sku'           => $data['sku'],
                'cost_price'    => $data['cost'],
                'selling_price' => $data['price'],
                'quantity'      => $data['qty'],
                'reorder_level' => $data['reorder'],
                'unit'          => $data['unit'],
                'is_active'     => true,
            ]);

            if ($data['qty'] > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id'    => $admin->id,
                    'type'       => 'adjustment',
                    'quantity'   => $data['qty'],
                    'notes'      => 'Opening stock',
                    'created_at' => now()->subDays(60),
                    'updated_at' => now()->subDays(60),
                ]);
            }

            return $product;
        });

        // ─────────────────────────────────────────
        // PURCHASE ORDERS  (5 orders)
        // ─────────────────────────────────────────
        $poData = [
            [
                'supplier' => 'TechSupply Co.',
                'email'    => 'orders@techsupply.com',
                'contact'  => '+1 555-0101',
                'status'   => 'received',
                'days_ago' => 45,
                'received_days_ago' => 38,
                'items' => [
                    [$products[0], 100, 3.50],
                    [$products[2], 30,  8.00],
                    [$products[5], 200, 1.00],
                    [$products[6], 20,  12.00],
                ],
            ],
            [
                'supplier' => 'FashionWholesale Ltd.',
                'email'    => 'wholesale@fashion.com',
                'contact'  => '+1 555-0102',
                'status'   => 'received',
                'days_ago' => 30,
                'received_days_ago' => 25,
                'items' => [
                    [$products[8],  80, 4.00],
                    [$products[9],  100, 4.00],
                    [$products[10], 60, 4.00],
                    [$products[11], 20, 18.00],
                    [$products[14], 40, 3.50],
                ],
            ],
            [
                'supplier' => 'GroceryDist Inc.',
                'email'    => 'supply@grocerydist.com',
                'contact'  => '+1 555-0103',
                'status'   => 'received',
                'days_ago' => 20,
                'received_days_ago' => 18,
                'items' => [
                    [$products[15], 500, 0.30],
                    [$products[17], 300, 0.25],
                    [$products[18], 200, 0.50],
                    [$products[19], 60,  3.00],
                ],
            ],
            [
                'supplier' => 'OfficeBasics Supplies',
                'email'    => 'orders@officebasics.com',
                'contact'  => '+1 555-0104',
                'status'   => 'ordered',
                'days_ago' => 3,
                'received_days_ago' => null,
                'items' => [
                    [$products[20], 200, 0.80],
                    [$products[21], 50,  1.00],
                    [$products[22], 40,  1.50],
                ],
            ],
            [
                'supplier' => 'HomeEssentials Ltd.',
                'email'    => 'stock@homeessentials.com',
                'contact'  => '+1 555-0105',
                'status'   => 'draft',
                'days_ago' => 1,
                'received_days_ago' => null,
                'items' => [
                    [$products[27], 50, 3.00],
                    [$products[24], 60, 0.60],
                    [$products[25], 40, 0.90],
                ],
            ],
        ];

        foreach ($poData as $i => $po) {
            $items    = $po['items'];
            $total    = collect($items)->sum(fn($it) => $it[1] * $it[2]);
            $orderAt  = now()->subDays($po['days_ago']);
            $receivedAt = $po['received_days_ago'] ? now()->subDays($po['received_days_ago']) : null;

            $order = PurchaseOrder::create([
                'po_number'        => 'PO-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'supplier_name'    => $po['supplier'],
                'supplier_email'   => $po['email'],
                'supplier_contact' => $po['contact'],
                'status'           => $po['status'],
                'total_amount'     => $total,
                'order_date'       => $orderAt,
                'expected_date'    => $orderAt->copy()->addDays(7),
                'received_date'    => $receivedAt,
                'user_id'          => $admin->id,
                'created_at'       => $orderAt,
                'updated_at'       => $receivedAt ?? $orderAt,
            ]);

            foreach ($items as [$product, $qty, $cost]) {
                $received = $po['status'] === 'received' ? $qty : 0;
                $order->items()->create([
                    'product_id'        => $product->id,
                    'quantity'          => $qty,
                    'received_quantity' => $received,
                    'unit_cost'         => $cost,
                ]);

                if ($po['status'] === 'received') {
                    StockMovement::create([
                        'product_id' => $product->id,
                        'user_id'    => $admin->id,
                        'type'       => 'purchase',
                        'quantity'   => $qty,
                        'reference'  => $order->po_number,
                        'notes'      => "Received from {$po['supplier']}",
                        'created_at' => $receivedAt,
                        'updated_at' => $receivedAt,
                    ]);
                }
            }
        }

        // ─────────────────────────────────────────
        // SALES  (40 sales spread over last 30 days)
        // ─────────────────────────────────────────
        $customers = [
            'James Osei', 'Abena Mensah', 'Kwame Asante', 'Ama Boateng',
            'Kofi Antwi', 'Akosua Frimpong', 'Yaw Darko', 'Adwoa Sarpong',
            null, null, null, null,  // walk-ins
        ];

        $staff = [$admin, $staff1, $staff2];

        $saleTemplates = [
            // High-frequency everyday items
            [[$products[15], 5],  [$products[17], 3],  [$products[18], 2]],
            [[$products[0],  1],  [$products[5],  1]],
            [[$products[9],  2],  [$products[10], 1]],
            [[$products[20], 3],  [$products[21], 1]],
            [[$products[15], 10], [$products[16], 2]],
            [[$products[24], 2],  [$products[25], 1],  [$products[27], 1]],
            [[$products[28], 2],  [$products[29], 1]],
            [[$products[18], 3],  [$products[19], 1]],
            [[$products[2],  1],  [$products[0],  2]],
            [[$products[8],  2],  [$products[14], 1]],
            // Bigger basket sales
            [[$products[3],  1],  [$products[0],  1],  [$products[5],  2]],
            [[$products[9],  3],  [$products[11], 1],  [$products[14], 2]],
            [[$products[15], 20], [$products[17], 4],  [$products[18], 6]],
            [[$products[20], 5],  [$products[22], 2],  [$products[23], 1]],
            [[$products[6],  1],  [$products[0],  1],  [$products[28], 1]],
        ];

        $saleCount = 0;
        for ($day = 29; $day >= 0; $day--) {
            // 1–3 sales per day
            $salesThisDay = rand(1, 3);
            for ($s = 0; $s < $salesThisDay; $s++) {
                $saleCount++;
                $template = $saleTemplates[array_rand($saleTemplates)];
                $customer = $customers[array_rand($customers)];
                $seller   = $staff[array_rand($staff)];
                $saleAt   = now()->subDays($day)->setTime(rand(9, 18), rand(0, 59));

                $total = collect($template)->sum(fn($it) => $it[0]->selling_price * $it[1]);

                $sale = Sale::create([
                    'sale_number'   => 'SALE-' . str_pad($saleCount, 5, '0', STR_PAD_LEFT),
                    'customer_name' => $customer,
                    'total_amount'  => $total,
                    'user_id'       => $seller->id,
                    'created_at'    => $saleAt,
                    'updated_at'    => $saleAt,
                ]);

                foreach ($template as [$product, $qty]) {
                    // Only add item if enough stock exists
                    $product->refresh();
                    $qty = min($qty, max(1, $product->quantity));

                    $sale->items()->create([
                        'product_id' => $product->id,
                        'quantity'   => $qty,
                        'unit_price' => $product->selling_price,
                    ]);

                    $product->decrement('quantity', $qty);

                    StockMovement::create([
                        'product_id' => $product->id,
                        'user_id'    => $seller->id,
                        'type'       => 'sale',
                        'quantity'   => -$qty,
                        'reference'  => $sale->sale_number,
                        'notes'      => $customer ? "Sale to {$customer}" : 'Walk-in sale',
                        'created_at' => $saleAt,
                        'updated_at' => $saleAt,
                    ]);
                }
            }
        }

        // ─────────────────────────────────────────
        // SHRINKAGE RECORDS
        // ─────────────────────────────────────────
        $shrinkages = [
            [$products[15], -8,  'Damaged — bottles crushed during delivery',      15],
            [$products[18], -5,  'Expired stock removed',                          10],
            [$products[4],  -1,  'Display unit damaged',                           20],
            [$products[27], -3,  'Packaging torn — unsellable',                    8],
            [$products[13], -2,  'Theft suspected (CCTV under review)',             5],
        ];

        foreach ($shrinkages as [$product, $qty, $note, $daysAgo]) {
            $at = now()->subDays($daysAgo);
            StockMovement::create([
                'product_id' => $product->id,
                'user_id'    => $admin->id,
                'type'       => 'shrinkage',
                'quantity'   => $qty,
                'notes'      => $note,
                'created_at' => $at,
                'updated_at' => $at,
            ]);
            $product->decrement('quantity', abs($qty));
        }

        // ─────────────────────────────────────────
        // STOCK RETURN  (1 customer return)
        // ─────────────────────────────────────────
        $returnAt = now()->subDays(4);
        StockMovement::create([
            'product_id' => $products[2]->id,
            'user_id'    => $staff1->id,
            'type'       => 'return',
            'quantity'   => 2,
            'notes'      => 'Customer return — faulty unit, resaleable',
            'created_at' => $returnAt,
            'updated_at' => $returnAt,
        ]);
        $products[2]->increment('quantity', 2);
    }
}
