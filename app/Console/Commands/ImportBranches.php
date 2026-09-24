<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\BranchOfficial;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportBranches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:branches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the branches and district contact list from hezbuttawheed.org';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://hezbuttawheed.org/district-contacts/';
        $this->info("Fetching district contacts from {$url}...");

        try {
            $response = Http::get($url);
            if ($response->failed()) {
                $this->error("Failed to fetch the district contacts page.");
                return 1;
            }

            $html = $response->body();

            // Ensure schema restrictions are ignored temporarily for truncation
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            Branch::truncate();
            BranchOfficial::truncate();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

            // Parse HTML
            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
            $xpath = new \DOMXPath($dom);

            $trNodes = $xpath->query("//table[contains(@class, 'data-table')]/tbody/tr");
            $this->info("Found " . $trNodes->length . " rows on the page.");

            $importedBranchesCount = 0;
            $importedOfficialsCount = 0;

            foreach ($trNodes as $tr) {
                // Get division name from preceding h2
                $h2Node = $xpath->query("preceding::h2[1]", $tr)->item(0);
                $divisionName = $h2Node ? trim($h2Node->nodeValue) : '';

                // Get cell nodes
                $tdNodes = $xpath->query("td", $tr);
                if ($tdNodes->length < 3) {
                    continue;
                }

                $nameCell = $tdNodes->item(0);
                $branchCell = $tdNodes->item(1);
                $phoneCell = $tdNodes->item(2);

                $branchDistrict = trim($branchCell->nodeValue);
                if (empty($branchDistrict)) {
                    continue;
                }

                // Parse names and phones from child divs
                $nameDivs = $xpath->query(".//div", $nameCell);
                $phoneDivs = $xpath->query(".//div", $phoneCell);

                $officials = [];

                if ($nameDivs->length === 0) {
                    $nameText = trim($nameCell->nodeValue);
                    $phoneText = trim($phoneCell->nodeValue);

                    if (!empty($nameText)) {
                        $designation = 'দায়িত্বশীল';
                        if (strpos($nameText, '(সহকারী)') !== false) {
                            $nameText = trim(str_replace('(সহকারী)', '', $nameText));
                            $designation = 'সহকারী দায়িত্বশীল';
                        }
                        $officials[] = [
                            'name' => $nameText,
                            'phone' => $phoneText,
                            'designation' => $designation
                        ];
                    }
                } else {
                    for ($i = 0; $i < $nameDivs->length; $i++) {
                        $nameDiv = $nameDivs->item($i);
                        $phoneDiv = $phoneDivs->item($i);

                        $nameText = trim($nameDiv->nodeValue);
                        $phoneText = $phoneDiv ? trim($phoneDiv->nodeValue) : '';

                        if (empty($nameText)) {
                            continue;
                        }

                        $designation = 'দায়িত্বশীল';

                        // Extract helpers designation and clean names
                        if (strpos($nameText, '(সহকারী)') !== false) {
                            $nameText = trim(str_replace('(সহকারী)', '', $nameText));
                            $designation = 'সহকারী দায়িত্বশীল';
                        } elseif ($nameDiv->getElementsByTagName('small')->length > 0) {
                            $smallNode = $nameDiv->getElementsByTagName('small')->item(0);
                            $designation = trim($smallNode->nodeValue);
                            // Clean designation from name
                            $nameText = trim(str_replace($smallNode->nodeValue, '', $nameText));
                            $nameText = trim(str_replace(['(', ')'], '', $nameText));
                        }

                        $officials[] = [
                            'name' => $nameText,
                            'phone' => $phoneText,
                            'designation' => $designation
                        ];
                    }
                }

                if (empty($officials)) {
                    continue;
                }

                $branchName = $branchDistrict . ' জেলা কার্যালয়';
                $branchType = 'district';

                // Create branch
                $branch = Branch::create([
                    'name' => $branchName,
                    'type' => $branchType,
                    'address' => $branchDistrict . ', ' . $divisionName,
                    'phone' => $officials[0]['phone'] ?? '',
                    'contact_person_name' => $officials[0]['name'] ?? '',
                    'contact_person_designation' => $officials[0]['designation'] ?? 'জেলা দায়িত্বশীল',
                    'is_active' => true,
                    'sort_order' => $importedBranchesCount + 1,
                ]);

                $importedBranchesCount++;

                // Create officials for this branch
                foreach ($officials as $index => $off) {
                    BranchOfficial::create([
                        'branch_id' => $branch->id,
                        'name' => $off['name'],
                        'phone' => $off['phone'],
                        'designation' => $off['designation'],
                        'sort_order' => $index + 1,
                    ]);
                    $importedOfficialsCount++;
                }

                $this->info("✓ Imported Branch: {$branchName} (Division: {$divisionName}) with " . count($officials) . " officials.");
            }

            // Update coordinates using the seeder
            $this->info("Updating branch coordinates using BranchLatLngSeeder...");
            $seeder = new \Database\Seeders\BranchLatLngSeeder();
            $seeder->run();

            $this->info("Import completed successfully.");
            $this->info("Total Branches: {$importedBranchesCount}");
            $this->info("Total Officials: {$importedOfficialsCount}");
            return 0;

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
