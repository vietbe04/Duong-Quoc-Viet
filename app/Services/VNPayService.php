<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class VNPayService
{
    protected $tmnCode;
    protected $hashSecret;
    protected $url;
    protected $returnUrl;

    public function __construct()
    {
        $this->tmnCode = config('services.vnpay.tmn_code');
        $this->hashSecret = config('services.vnpay.hash_secret');
        $this->url = config('services.vnpay.url');
        $this->returnUrl = config('services.vnpay.return_url');
    }

    /**
     * Tạo URL thanh toán VNPay
     *
     * @param array $data
     * @return string
     */
    public function createPaymentUrl(array $data): string
    {
        $vnpTxnRef = $data['order_id'] ?? time();
        $vnpOrderInfo = $data['order_info'] ?? 'Thanh toan don hang';
        $vnpAmount = $data['amount'] * 100; // VNPay yêu cầu số tiền * 100
        $vnpLocale = $data['locale'] ?? 'vn';
        $vnpBankCode = $data['bank_code'] ?? '';
        $vnpIpAddr = $data['ip_address'] ?? request()->ip();

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $this->tmnCode,
            'vnp_Amount' => $vnpAmount,
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $vnpIpAddr,
            'vnp_Locale' => $vnpLocale,
            'vnp_OrderInfo' => $vnpOrderInfo,
            'vnp_OrderType' => 'billpayment',
            'vnp_ReturnUrl' => $this->returnUrl,
            'vnp_TxnRef' => $vnpTxnRef,
        ];

        if (!empty($vnpBankCode)) {
            $inputData['vnp_BankCode'] = $vnpBankCode;
        }

        // Sắp xếp dữ liệu theo key
        ksort($inputData);

        // Build query string
        $query = '';
        $i = 0;
        $hashdata = '';
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        // Tạo secure hash
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);
        $query .= 'vnp_SecureHash=' . $vnpSecureHash;

        $paymentUrl = $this->url . '?' . $query;

        Log::info('VNPay Payment URL created', [
            'order_id' => $vnpTxnRef,
            'amount' => $data['amount'],
        ]);

        return $paymentUrl;
    }

    /**
     * Xác thực response từ VNPay
     *
     * @param array $vnpayData
     * @return array
     */
    public function validateReturn(array $vnpayData): array
    {
        $vnpSecureHash = $vnpayData['vnp_SecureHash'] ?? '';
        
        // Loại bỏ các tham số không cần thiết cho việc tạo hash
        $inputData = [];
        foreach ($vnpayData as $key => $value) {
            if (substr($key, 0, 4) == 'vnp_') {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);
        
        ksort($inputData);
        
        $hashData = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashData .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->hashSecret);

        $isValid = $secureHash === $vnpSecureHash;
        $responseCode = $vnpayData['vnp_ResponseCode'] ?? '99';
        $transactionStatus = $vnpayData['vnp_TransactionStatus'] ?? '99';

        Log::info('VNPay Return Validation', [
            'is_valid' => $isValid,
            'response_code' => $responseCode,
            'transaction_status' => $transactionStatus,
            'order_id' => $vnpayData['vnp_TxnRef'] ?? 'N/A',
        ]);

        return [
            'is_valid' => $isValid,
            'response_code' => $responseCode,
            'transaction_status' => $transactionStatus,
            'order_id' => $vnpayData['vnp_TxnRef'] ?? null,
            'amount' => isset($vnpayData['vnp_Amount']) ? $vnpayData['vnp_Amount'] / 100 : 0,
            'bank_code' => $vnpayData['vnp_BankCode'] ?? '',
            'bank_tran_no' => $vnpayData['vnp_BankTranNo'] ?? '',
            'card_type' => $vnpayData['vnp_CardType'] ?? '',
            'pay_date' => $vnpayData['vnp_PayDate'] ?? '',
            'transaction_no' => $vnpayData['vnp_TransactionNo'] ?? '',
            'message' => $this->getResponseMessage($responseCode),
        ];
    }

    /**
     * Lấy message theo response code
     *
     * @param string $responseCode
     * @return string
     */
    public function getResponseMessage(string $responseCode): string
    {
        $messages = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường)',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng',
            '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch',
            '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa',
            '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP)',
            '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
            '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch',
            '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày',
            '75' => 'Ngân hàng thanh toán đang bảo trì',
            '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định',
            '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)',
        ];

        return $messages[$responseCode] ?? 'Lỗi không xác định';
    }

    /**
     * Kiểm tra giao dịch có thành công không
     *
     * @param string $responseCode
     * @return bool
     */
    public function isSuccessful(string $responseCode): bool
    {
        return $responseCode === '00';
    }
}
