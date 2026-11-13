<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = self::catalog();
        if (count($catalog) !== 150) {
            throw new RuntimeException('Katalog demo TERAPOS harus berisi tepat 150 barang.');
        }

        DB::transaction(function () use ($catalog) {
            $this->call(CategorySeeder::class);
            $categoryIds = Category::query()->pluck('id', 'nama_kategori');

            foreach ($catalog as $index => $item) {
                $code = 'TERA' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);
                $barcode = $this->barcodeFor($index + 1);
                $byCode = Product::query()->where('kode_produk', $code)->first();
                $byBarcode = Product::query()->where('barcode', $barcode)->first();

                if ($byCode && $byBarcode && $byCode->getKey() !== $byBarcode->getKey()) {
                    throw new RuntimeException("Kode atau barcode {$code} digunakan oleh barang yang berbeda.");
                }

                $product = $byCode ?? $byBarcode ?? new Product();
                $isNew = !$product->exists;
                if (!isset($categoryIds[$item['category']])) {
                    throw new RuntimeException("Kategori {$item['category']} tidak ditemukan.");
                }

                $buyPrice = $item['harga_beli'];
                $sellPrice = $item['harga_jual'];
                if (
                    !$isNew
                    && (float) $product->harga_jual > (float) $product->harga_beli
                ) {
                    $buyPrice = $product->harga_beli;
                    $sellPrice = $product->harga_jual;
                }

                $product->fill([
                    'kode_produk' => $code,
                    'barcode' => $barcode,
                    'category_id' => $categoryIds[$item['category']],
                    'nama_produk' => $item['name'],
                    'merk' => $item['brand'],
                    'satuan' => $item['unit'],
                    'stok_minimum' => $item['stok_minimum'],
                    'harga_beli' => $buyPrice,
                    'harga_jual' => $sellPrice,
                ]);

                if ($isNew) {
                    $product->stok = $item['stok'];
                }

                $product->save();
            }
        });
    }

    public static function catalog(): array
    {
        $items = [];
        $add = static function (
            string $name,
            string $category,
            string $brand,
            int $buyPrice,
            string $unit = 'pcs'
        ) use (&$items): void {
            $items[] = [
                'name' => $name,
                'category' => $category,
                'brand' => $brand,
                'unit' => $unit,
                'harga_beli' => $buyPrice,
            ];
        };

        $lamp = 'Lampu & Penerangan';
        $electric = 'Kelistrikan';
        $cable = 'Kabel & Aksesoris Listrik';
        $electronics = 'Elektronik Rumah Tangga';
        $home = 'Peralatan Rumah Tangga';
        $cleaning = 'Peralatan Kebersihan';
        $cleaningNeeds = 'Kebutuhan Kebersihan';
        $kitchen = 'Peralatan Dapur';
        $glassware = 'Pecah Belah';

        $add('Lampu Hannochs Sonic Kuning 15W', $lamp, 'Hannochs', 18000);
        $add('Lampu Hannochs Sonic Putih 12W', $lamp, 'Hannochs', 16000);
        $add('Stop Kontak 4 Lubang', $electric, 'Broco', 22000);
        $add('Kabel Roll 5 Meter', $cable, 'Uticon', 35000);
        $add('Sapu Lantai', $cleaning, 'Nagata', 15000);
        $add('Lampu Hannochs Sonic 5W', $lamp, 'Hannochs', 10700);

        $add('Lampu Hannochs Sonic 3W', $lamp, 'Hannochs', 9500);
        $add('Lampu Hannochs Sonic 7W', $lamp, 'Hannochs', 13800);
        $add('Lampu Hannochs Sonic 9W', $lamp, 'Hannochs', 16000);
        $add('Lampu Hannochs Sonic 12W Warm White', $lamp, 'Hannochs', 19200);
        $add('Lampu Hannochs Sonic 15W Cool Daylight', $lamp, 'Hannochs', 27000);
        $add('Lampu Hannochs Sonic 18W', $lamp, 'Hannochs', 32000);
        $add('Lampu Hannochs Sonic 20W', $lamp, 'Hannochs', 36000);
        $add('Lampu Philips Essential 5W', $lamp, 'Philips', 14000);
        $add('Lampu Philips Essential 8W', $lamp, 'Philips', 16500);
        $add('Lampu Philips Essential 10W', $lamp, 'Philips', 19000);
        $add('Lampu Philips Essential 12W', $lamp, 'Philips', 22000);
        $add('Lampu Philips Essential 14W', $lamp, 'Philips', 26000);
        $add('Lampu Panasonic LED 5W', $lamp, 'Panasonic', 15000);
        $add('Lampu Panasonic LED 7W', $lamp, 'Panasonic', 17500);
        $add('Lampu Panasonic LED 9W', $lamp, 'Panasonic', 20000);
        $add('Lampu Panasonic LED 12W', $lamp, 'Panasonic', 26000);
        $add('Lampu Osram LED 7W', $lamp, 'Osram', 18000);
        $add('Lampu Osram LED 9W', $lamp, 'Osram', 21000);
        $add('Lampu Osram LED 12W', $lamp, 'Osram', 28000);
        $add('Lampu Emergency 12 LED', $lamp, 'Krisbow', 35000);
        $add('Lampu Emergency 30 LED', $lamp, 'Krisbow', 65000);
        $add('Lampu LED Filamen 4W', $lamp, 'Philips', 12000);
        $add('Lampu Taman Solar', $lamp, 'Nexhome', 35000);
        $add('Lampu Sorot LED 20W', $lamp, 'Hannochs', 75000);
        $add('Lampu Plafon LED 18W', $lamp, 'Panasonic', 55000);
        $add('Lampu Strip LED 5 Meter', $lamp, 'Hannochs', 45000);
        $add('Lampu Tidur Sensor', $lamp, 'Nexhome', 30000);

        $add('Stop Kontak 2 Lubang', $electric, 'Broco', 15000);
        $add('Stop Kontak 6 Lubang', $electric, 'Broco', 32000);
        $add('Saklar Tunggal', $electric, 'Panasonic', 10000);
        $add('Saklar Ganda', $electric, 'Panasonic', 16000);
        $add('Saklar Seri', $electric, 'Broco', 18000);
        $add('Saklar Hotel', $electric, 'Broco', 22000);
        $add('Fitting Lampu E27', $electric, 'Eterna', 5000);
        $add('Fitting Lampu Gantung', $electric, 'Eterna', 12000);
        $add('Steker Listrik 2 Kaki', $electric, 'Broco', 5000);
        $add('Steker Universal', $electric, 'Uticon', 12000);
        $add('Terminal Listrik 3 Lubang', $electric, 'Uticon', 25000);
        $add('Terminal Listrik 5 Lubang', $electric, 'Uticon', 35000);
        $add('Terminal Listrik 6 Lubang', $electric, 'Uticon', 42000);
        $add('Adaptor USB 5V 2A', $electric, 'Vivan', 18000);
        $add('Adaptor 12V 2A', $electric, 'Kenmaster', 35000);
        $add('MCB 1 Phase 2A', $electric, 'Schneider', 32000);
        $add('MCB 1 Phase 6A', $electric, 'Schneider', 35000);

        $add('Kabel NYM 2x1.5', $cable, 'Eterna', 55000, 'meter');
        $add('Kabel NYM 2x2.5', $cable, 'Eterna', 78000, 'meter');
        $add('Kabel NYM 3x2.5', $cable, 'Supreme', 125000, 'meter');
        $add('Kabel NYA 1.5 mm', $cable, 'Supreme', 32000, 'meter');
        $add('Kabel NYA 2.5 mm', $cable, 'Supreme', 48000, 'meter');
        $add('Kabel Roll 10 Meter', $cable, 'Uticon', 55000);
        $add('Kabel Roll 15 Meter', $cable, 'Uticon', 75000);
        $add('Kabel Roll 20 Meter', $cable, 'Uticon', 98000);
        $add('Isolasi Listrik Hitam', $cable, '3M', 5000);
        $add('Isolasi Listrik Merah', $cable, '3M', 5000);
        $add('Isolasi Listrik Putih', $cable, 'Nitto', 5000);
        $add('Cable Tie 10 cm', $cable, 'Kenmaster', 8000, 'pack');
        $add('Cable Tie 20 cm', $cable, 'Kenmaster', 12000, 'pack');
        $add('Klem Kabel 5 mm', $cable, 'Kenmaster', 6000, 'pack');
        $add('Klem Kabel 10 mm', $cable, 'Kenmaster', 8000, 'pack');
        $add('Test Pen Analog', $cable, 'Tekiro', 10000);
        $add('Test Pen Digital', $cable, 'Krisbow', 25000);

        $add('Kipas Angin Meja 12 Inci', $electronics, 'Maspion', 185000);
        $add('Kipas Angin Dinding 16 Inci', $electronics, 'Miyako', 310000);
        $add('Kipas Angin Berdiri 16 Inci', $electronics, 'Cosmos', 420000);
        $add('Rice Cooker 0.6 Liter', $electronics, 'Miyako', 185000);
        $add('Rice Cooker 1.8 Liter', $electronics, 'Cosmos', 285000);
        $add('Setrika Listrik Anti Lengket', $electronics, 'Philips', 225000);
        $add('Setrika Uap Compact', $electronics, 'Panasonic', 375000);
        $add('Blender 1 Liter', $electronics, 'Miyako', 285000);
        $add('Blender 1.5 Liter', $electronics, 'Philips', 495000);
        $add('Ketel Listrik 1.8 Liter', $electronics, 'Maspion', 185000);
        $add('Dispenser Air Galon Bawah', $electronics, 'Miyako', 1250000);
        $add('Mixer Tangan 5 Kecepatan', $electronics, 'Maspion', 325000);
        $add('Chopper Elektrik 1 Liter', $electronics, 'Miyako', 265000);
        $add('Exhaust Fan 8 Inci', $electronics, 'Sekai', 175000);
        $add('Vacuum Cleaner Mini', $electronics, 'Krisbow', 425000);

        $add('Ember Plastik 10 Liter', $home, 'Lion Star', 18000);
        $add('Ember Plastik 20 Liter', $home, 'Lion Star', 28000);
        $add('Baskom Plastik 28 cm', $home, 'Lion Star', 15000);
        $add('Baskom Plastik 36 cm', $home, 'Lion Star', 23000);
        $add('Gayung Plastik', $home, 'Lion Star', 8000);
        $add('Hanger Plastik isi 12', $home, 'Hommy', 18000, 'pack');
        $add('Hanger Stainless isi 5', $home, 'Krisbow', 25000, 'pack');
        $add('Tempat Sampah 10 Liter', $home, 'Lion Star', 35000);
        $add('Tempat Sampah 20 Liter', $home, 'Lion Star', 55000);
        $add('Keranjang Laundry', $home, 'Hommy', 45000);
        $add('Keranjang Serbaguna', $home, 'Lion Star', 28000);
        $add('Rak Plastik 3 Tingkat', $home, 'Napolly', 125000);
        $add('Rak Sepatu 4 Tingkat', $home, 'Napolly', 95000);
        $add('Keset Pintu Microfiber', $home, 'Hommy', 25000);
        $add('Kotak Penyimpanan 15 Liter', $home, 'Lion Star', 42000);

        $add('Sapu Lidi', $cleaning, 'Nagata', 12000);
        $add('Sapu Nylon', $cleaning, 'Nagata', 18000);
        $add('Pel Microfiber', $cleaning, 'Krisbow', 45000);
        $add('Pel Spin Mop', $cleaning, 'Krisbow', 185000);
        $add('Kemoceng Microfiber', $cleaning, 'Nagata', 18000);
        $add('Lap Microfiber Kecil', $cleaning, 'Krisbow', 10000);
        $add('Lap Microfiber Besar', $cleaning, 'Krisbow', 18000);
        $add('Spons Cuci Piring isi 3', $cleaning, 'Scotch-Brite', 12000, 'pack');
        $add('Sikat Lantai Gagang Panjang', $cleaning, 'Nagata', 25000);
        $add('Pengki Plastik', $cleaning, 'Lion Star', 12000);
        $add('Sikat WC dengan Wadah', $cleaning, 'Lion Star', 28000);
        $add('Wiper Kaca 25 cm', $cleaning, 'Krisbow', 22000);
        $add('Sikat Serbaguna', $cleaning, 'Nagata', 12000);
        $add('Kain Pel Katun', $cleaning, 'Nagata', 16000);

        $add('Mama Lemon Sabun Cuci Piring 680 ml', $cleaningNeeds, 'Mama Lemon', 12000);
        $add('Mama Lemon Sabun Cuci Piring 1.5 Liter', $cleaningNeeds, 'Mama Lemon', 26000);
        $add('Sunlight Sabun Cuci Piring 680 ml', $cleaningNeeds, 'Sunlight', 13000);
        $add('Sunlight Sabun Cuci Piring 1.5 Liter', $cleaningNeeds, 'Sunlight', 28000);
        $add('Wipol Pembersih Lantai 780 ml', $cleaningNeeds, 'Wipol', 16000);
        $add('Super Pell Pembersih Lantai 800 ml', $cleaningNeeds, 'Super Pell', 14000);
        $add('Vixal Pembersih Kamar Mandi 750 ml', $cleaningNeeds, 'Vixal', 17000);
        $add('Bayclin Pemutih 1 Liter', $cleaningNeeds, 'Bayclin', 18000);
        $add('So Klin Pembersih Lantai 800 ml', $cleaningNeeds, 'So Klin', 14000);
        $add('Mr Muscle Pembersih Dapur 500 ml', $cleaningNeeds, 'Mr Muscle', 28000);
        $add('Stella Pengharum Ruangan Spray', $cleaningNeeds, 'Stella', 25000);
        $add('Stella Pengharum Ruangan Gel', $cleaningNeeds, 'Stella', 18000);
        $add('Mama Lemon Refill 400 ml', $cleaningNeeds, 'Mama Lemon', 8000);
        $add('Sunlight Jerigen 3.7 Liter', $cleaningNeeds, 'Sunlight', 62000);
        $add('Pembersih Kaca 500 ml', $cleaningNeeds, 'Mr Muscle', 22000);

        $add('Wajan Anti Lengket 24 cm', $kitchen, 'Maxim', 95000);
        $add('Wajan Anti Lengket 28 cm', $kitchen, 'Maxim', 135000);
        $add('Panci Stainless 16 cm', $kitchen, 'Maspion', 85000);
        $add('Panci Stainless 20 cm', $kitchen, 'Maspion', 125000);
        $add('Teko Stainless 1.5 Liter', $kitchen, 'Maspion', 75000);
        $add('Spatula Silikon', $kitchen, 'Oxone', 25000);
        $add('Spatula Kayu', $kitchen, 'Kris', 12000);
        $add('Talenan Plastik', $kitchen, 'Lion Star', 22000);
        $add('Pisau Dapur 8 Inci', $kitchen, 'Kris', 28000);
        $add('Set Pisau Dapur 3 Buah', $kitchen, 'Oxone', 85000, 'set');
        $add('Saringan Stainless', $kitchen, 'Maspion', 18000);
        $add('Saringan Minyak', $kitchen, 'Maspion', 22000);

        $add('Gelas Kaca Set 6 Buah', $glassware, 'Luminarc', 45000, 'set');
        $add('Gelas Kaca Set 12 Buah', $glassware, 'Bormioli', 85000, 'set');
        $add('Piring Makan Set 6 Buah', $glassware, 'Duralex', 95000, 'set');
        $add('Piring Dessert Set 6 Buah', $glassware, 'Duralex', 75000, 'set');
        $add('Mangkuk Kaca Set 6 Buah', $glassware, 'Luminarc', 65000, 'set');
        $add('Cangkir Keramik Set 6 Buah', $glassware, 'Mugku', 85000, 'set');
        $add('Mug Keramik', $glassware, 'Mugku', 18000);
        $add('Pitcher Kaca 1 Liter', $glassware, 'Bormioli', 55000);
        $add('Toples Kaca 1 Liter', $glassware, 'Luminarc', 35000);
        $add('Toples Kaca 2 Liter', $glassware, 'Luminarc', 50000);
        $add('Toples Kaca Set 3 Buah', $glassware, 'Bormioli', 95000, 'set');
        $add('Piring Saji Kaca', $glassware, 'Duralex', 45000);

        $categoryIndexes = [];
        foreach ($items as $index => &$item) {
            $categoryIndex = $categoryIndexes[$item['category']] ?? 0;
            $categoryIndexes[$item['category']] = $categoryIndex + 1;
            $minimum = self::minimumStock($item['category'], $item['name'], $categoryIndex);
            [$low, $high] = self::stockRange($item['category']);
            $span = $high - $low + 1;
            $item['stok'] = ($index + 1) % 17 === 0
                ? $minimum
                : $low + (($categoryIndex * 17 + $index * 3) % $span);
            $item['stok_minimum'] = $minimum;
            $item['harga_jual'] = (int) (ceil($item['harga_beli'] * 1.2 / 100) * 100);
        }
        unset($item);

        return $items;
    }

    private static function minimumStock(string $category, string $name, int $index): int
    {
        $name = strtolower($name);

        return match ($category) {
            'Lampu & Penerangan' => 10,
            'Kelistrikan' => str_contains($name, 'saklar')
                ? 8
                : (str_contains($name, 'fitting') || str_contains($name, 'steker') ? 10 : 5),
            'Kabel & Aksesoris Listrik' => str_contains($name, 'kabel nym')
                || str_contains($name, 'kabel nya')
                || str_contains($name, 'kabel roll')
                ? 3
                : 5,
            'Elektronik Rumah Tangga' => 2,
            'Peralatan Rumah Tangga' => $index % 2 === 0 ? 3 : 5,
            'Peralatan Kebersihan' => $index % 2 === 0 ? 5 : 10,
            'Kebutuhan Kebersihan' => $index % 2 === 0 ? 10 : 5,
            'Peralatan Dapur' => $index % 2 === 0 ? 3 : 5,
            'Pecah Belah' => 2,
            default => 5,
        };
    }

    private static function stockRange(string $category): array
    {
        return match ($category) {
            'Lampu & Penerangan' => [20, 100],
            'Kelistrikan' => [15, 60],
            'Kabel & Aksesoris Listrik' => [5, 40],
            'Elektronik Rumah Tangga' => [3, 20],
            'Peralatan Rumah Tangga' => [5, 40],
            'Peralatan Kebersihan' => [5, 50],
            'Kebutuhan Kebersihan' => [5, 80],
            'Peralatan Dapur' => [5, 30],
            'Pecah Belah' => [5, 25],
            default => [5, 30],
        };
    }

    private function barcodeFor(int $number): string
    {
        $body = str_pad((string) (200000000000 + $number), 12, '0', STR_PAD_LEFT);

        return $body . $this->ean13CheckDigit($body);
    }

    private function ean13CheckDigit(string $body): string
    {
        $sum = 0;
        foreach (str_split($body) as $index => $digit) {
            $sum += (int) $digit * ($index % 2 === 0 ? 1 : 3);
        }

        return (string) ((10 - ($sum % 10)) % 10);
    }
}
