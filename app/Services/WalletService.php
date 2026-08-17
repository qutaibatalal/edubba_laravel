<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Credit (top-up) the wallet and record a ledger transaction.
     */
    public static function credit(Wallet $wallet, float $amount, ?string $reference = null, ?string $description = null): Wallet
    {
        return DB::transaction(function () use ($wallet, $amount, $reference, $description) {
            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => WalletTransaction::TYPE_CREDIT,
                'amount' => $amount,
                'reference' => $reference,
                'description' => $description,
            ]);

            $wallet->increment('balance', $amount);

            return $wallet->fresh();
        });
    }

    /**
     * Debit the wallet; throws if insufficient balance.
     */
    public static function debit(Wallet $wallet, float $amount, ?string $reference = null, ?string $description = null): Wallet
    {
        return DB::transaction(function () use ($wallet, $amount, $reference, $description) {
            if ($wallet->balance < $amount) {
                throw new \DomainException('Insufficient wallet balance.');
            }

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => WalletTransaction::TYPE_DEBIT,
                'amount' => $amount,
                'reference' => $reference,
                'description' => $description,
            ]);

            $wallet->decrement('balance', $amount);

            return $wallet->fresh();
        });
    }

    /**
     * Get (or create) the wallet for a student.
     */
    public static function forStudent(int $studentId): Wallet
    {
        return Wallet::firstOrCreate(
            ['student_id' => $studentId],
            ['balance' => 0]
        );
    }
}
