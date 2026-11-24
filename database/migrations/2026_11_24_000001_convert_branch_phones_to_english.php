<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Convert Bengali digits to English digits while preserving hyphen format (e.g. 01892-858052)
     */
    public function up(): void
    {
        $bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $enDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $normalizePhone = function ($phone) use ($bnDigits, $enDigits) {
            if (!$phone) {
                return $phone;
            }

            // Convert Bengali digits to English
            $converted = str_replace($bnDigits, $enDigits, trim($phone));

            // Extract all digits
            $digitsOnly = preg_replace('/[^0-9]/', '', $converted);

            // Handle country code if present (+8801... or 8801...)
            if (str_starts_with($digitsOnly, '8801') && strlen($digitsOnly) === 13) {
                $digitsOnly = substr($digitsOnly, 2); // Keep 01... (11 digits)
            }

            // If it is a standard 11-digit Bangladeshi number, format with hyphen: 01892-858052
            if (strlen($digitsOnly) === 11 && str_starts_with($digitsOnly, '01')) {
                return substr($digitsOnly, 0, 5) . '-' . substr($digitsOnly, 5);
            }

            // Otherwise, keep the converted English digits and original hyphens
            $cleaned = preg_replace('/[^\d\-\+]/', '', $converted);
            return $cleaned ?: $converted;
        };

        // 1. Update branches table
        $branches = DB::table('branches')->select('id', 'phone')->whereNotNull('phone')->get();
        foreach ($branches as $branch) {
            $newPhone = $normalizePhone($branch->phone);
            if ($newPhone !== $branch->phone) {
                DB::table('branches')->where('id', $branch->id)->update(['phone' => $newPhone]);
            }
        }

        // 2. Update branch_officials table
        $officials = DB::table('branch_officials')->select('id', 'phone')->whereNotNull('phone')->get();
        foreach ($officials as $official) {
            $newPhone = $normalizePhone($official->phone);
            if ($newPhone !== $official->phone) {
                DB::table('branch_officials')->where('id', $official->id)->update(['phone' => $newPhone]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No loss of data; digits remain converted in English.
    }
};
