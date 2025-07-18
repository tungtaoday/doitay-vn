<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_method',
        'name',
        'is_active',
        'sort_order',
        'qr_code_image',
        'bank_name',
        'bank_branch',
        'account_number',
        'account_name',
        'swift_code',
        'wallet_phone',
        'wallet_name',
        'instructions',
        'note_template',
        'min_amount',
        'max_amount',
        'processing_hours'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Static methods
    public static function getActivePaymentMethods()
    {
        return self::active()->ordered()->get();
    }

    public static function getBankTransferMethods()
    {
        return self::active()->byPaymentMethod('bank_transfer')->ordered()->get();
    }

    public static function getEWalletMethods()
    {
        return self::active()->whereIn('payment_method', ['momo', 'zalopay'])->ordered()->get();
    }

    // Instance methods
    public function getQrCodeUrl()
    {
        if ($this->qr_code_image) {
            return asset('storage/deposit_qr/' . $this->qr_code_image);
        }
        return null;
    }

    public function getInstructionsWithPlaceholders($userId, $amount = null)
    {
        $instructions = $this->instructions;
        
        if ($instructions) {
            $instructions = str_replace('[USER_ID]', $userId, $instructions);
            if ($amount) {
                $instructions = str_replace('[AMOUNT]', number_format($amount), $instructions);
            }
        }
        
        return $instructions;
    }

    public function getNoteTemplateWithPlaceholders($userId, $amount = null)
    {
        $template = $this->note_template;
        
        if ($template) {
            $template = str_replace('[USER_ID]', $userId, $template);
            if ($amount) {
                $template = str_replace('[AMOUNT]', number_format($amount), $template);
            }
        }
        
        return $template;
    }

    public function isAmountValid($amount)
    {
        return $amount >= $this->min_amount && $amount <= $this->max_amount;
    }

    public function getAmountRangeText()
    {
        return number_format($this->min_amount, 0, '.', ',') . ' - ' . 
               number_format($this->max_amount, 0, '.', ',') . ' VNĐ';
    }

    public function getProcessingTimeText()
    {
        if ($this->processing_hours < 24) {
            return $this->processing_hours . ' giờ';
        } else {
            $days = floor($this->processing_hours / 24);
            return $days . ' ngày';
        }
    }

    public function getPaymentMethodIcon()
    {
        $icons = [
            'bank_transfer' => 'las la-university',
            'momo' => 'lab la-cc-mastercard',
            'zalopay' => 'lab la-cc-visa',
            'other' => 'las la-wallet'
        ];

        return $icons[$this->payment_method] ?? 'las la-credit-card';
    }

    public function getPaymentMethodColor()
    {
        $colors = [
            'bank_transfer' => 'primary',
            'momo' => 'danger',
            'zalopay' => 'info',
            'other' => 'secondary'
        ];

        return $colors[$this->payment_method] ?? 'primary';
    }
}
