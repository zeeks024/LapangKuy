<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - LapangKuy</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            margin: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 30px 20px;
        }
        .payment-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .booking-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .booking-info div {
            flex: 1;
            min-width: 150px;
            margin-bottom: 10px;
        }
        .label {
            font-weight: 600;
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .value {
            color: #333;
            font-size: 16px;
            font-weight: 500;
        }
        .status-paid {
            background: #d4edda;
            color: #155724;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            margin: 10px 0;
        }
        .payment-summary {
            background: #e8f5e8;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .payment-amount {
            font-size: 32px;
            font-weight: 700;
            color: #28a745;
            margin: 10px 0;
        }
        .receipt-section {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .receipt-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }
        .receipt-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 18px;
        }
        .button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 0;
        }
        .button:hover {
            background: #0056b3;
        }
        .button-success {
            background: #28a745;
        }
        .button-success:hover {
            background: #1e7e34;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        .footer p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #007bff;
            text-decoration: none;
        }
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
            }
            .content {
                padding: 20px 15px;
            }
            .booking-info {
                flex-direction: column;
            }
            .booking-info div {
                margin-bottom: 15px;
            }
            .payment-amount {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>💳 Pembayaran Berhasil!</h1>
            <p>Transaksi Anda telah diproses dengan sukses</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Halo <strong>{{ $booking->user->name }}</strong>,</p>
            
            <p>Selamat! Pembayaran untuk booking lapangan Anda telah <strong>berhasil diproses</strong>. Booking Anda sekarang sudah dikonfirmasi.</p>

            <!-- Payment Success Summary -->
            <div class="payment-summary">
                <div style="font-size: 48px; margin-bottom: 10px;">✅</div>
                <h2 style="margin: 0; color: #28a745;">Pembayaran Berhasil</h2>
                <div class="payment-amount">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                <p style="margin: 0; color: #666;">Transaksi telah diverifikasi</p>
            </div>

            <!-- Transaction Details -->
            <div class="receipt-section">
                <h3 style="margin: 0 0 15px 0; color: #333;">🧾 Rincian Transaksi</h3>
                
                <div class="receipt-row">
                    <span>Kode Booking:</span>
                    <span><strong>#{{ $booking->booking_code ?? 'LK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</strong></span>
                </div>
                
                @if($booking->transaction_id ?? false)
                <div class="receipt-row">
                    <span>ID Transaksi:</span>
                    <span><code>{{ $booking->transaction_id }}</code></span>
                </div>
                @endif
                
                <div class="receipt-row">
                    <span>Metode Pembayaran:</span>
                    <span>{{ $booking->payment_method ?? 'Transfer Bank' }}</span>
                </div>
                
                <div class="receipt-row">
                    <span>Tanggal Pembayaran:</span>
                    <span>{{ now()->format('d M Y, H:i') }} WIB</span>
                </div>
                
                <div class="receipt-row">
                    <span>Status:</span>
                    <span style="color: #28a745; font-weight: bold;">LUNAS</span>
                </div>
                
                <div class="receipt-row">
                    <span><strong>Total Dibayar:</strong></span>
                    <span style="color: #28a745;"><strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></span>
                </div>
            </div>

            <!-- Booking Details Card -->
            <div class="payment-card">
                <div class="status-paid">✅ Booking Dikonfirmasi & Dibayar</div>
                
                <div class="booking-info">
                    <div>
                        <div class="label">Lapangan</div>
                        <div class="value">{{ $booking->field->name }}</div>
                    </div>
                    <div>
                        <div class="label">Lokasi</div>
                        <div class="value">{{ $booking->field->location }}</div>
                    </div>
                </div>

                <div class="booking-info">
                    <div>
                        <div class="label">Tanggal</div>
                        <div class="value">{{ \Carbon\Carbon::parse($booking->date)->format('l, d M Y') }}</div>
                    </div>
                    <div>
                        <div class="label">Waktu</div>
                        <div class="value">{{ $booking->start_time }} - {{ $booking->end_time }}</div>
                    </div>
                </div>

                <div class="booking-info">
                    <div>
                        <div class="label">Durasi</div>
                        <div class="value">{{ $booking->duration }} jam</div>
                    </div>
                    <div>
                        <div class="label">Harga per Jam</div>
                        <div class="value">Rp {{ number_format($booking->field->price, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('bookings.show', $booking->id) }}" class="button button-success">
                    📋 Lihat Detail Booking
                </a>
                <br>
                <a href="#" onclick="window.print()" class="button">
                    🖨️ Cetak Struk Pembayaran
                </a>
            </div>

            <!-- Next Steps -->
            <div style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; margin: 20px 0;">
                <h3 style="margin: 0 0 10px 0; color: #1976d2;">📝 Langkah Selanjutnya:</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Simpan email ini sebagai bukti pembayaran</li>
                    <li>Datang 15 menit sebelum waktu booking</li>
                    <li>Bawa KTP/identitas untuk verifikasi</li>
                    <li>Tunjukkan kode booking kepada pemilik lapangan</li>
                </ul>
            </div>

            <!-- Contact Info -->
            @if($booking->field->owner_phone)
            <div style="background: #f1f8e9; border-left: 4px solid #4caf50; padding: 15px; margin: 20px 0;">
                <h3 style="margin: 0 0 10px 0; color: #388e3c;">📞 Kontak Pemilik Lapangan:</h3>
                <p style="margin: 0;">
                    <strong>{{ $booking->field->owner_name ?? 'Pemilik Lapangan' }}</strong><br>
                    WhatsApp: <a href="https://wa.me/62{{ ltrim($booking->field->owner_phone, '0') }}" style="color: #25d366;">{{ $booking->field->owner_phone }}</a>
                </p>
            </div>
            @endif

            <!-- Security Note -->
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 15px; margin: 20px 0;">
                <h4 style="margin: 0 0 10px 0; color: #856404;">🔒 Keamanan Transaksi</h4>
                <p style="margin: 0; font-size: 14px;">
                    Transaksi ini telah dienkripsi dan diverifikasi melalui sistem keamanan LapangKuy. 
                    Jangan bagikan kode booking atau informasi pembayaran kepada pihak yang tidak bertanggung jawab.
                </p>
            </div>

            <p>Terima kasih telah menggunakan LapangKuy! Kami berharap Anda menikmati pengalaman bermain di lapangan yang telah Anda booking.</p>
            
            <p style="margin-top: 30px;">
                Selamat bermain dan bersenang-senang!<br>
                <strong>Tim LapangKuy</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>LapangKuy</strong> - Platform Booking Lapangan Olahraga Terpercaya</p>
            <div class="social-links">
                <a href="#">📱 Instagram</a>
                <a href="#">📘 Facebook</a>
                <a href="#">🌐 Website</a>
            </div>
            <p>Email: support@lapangkuy.com | Phone: +62 812-3456-789</p>
            <p>Jika Anda tidak ingin menerima email ini lagi, <a href="#" style="color: #6c757d;">unsubscribe di sini</a></p>
        </div>
    </div>
</body>
</html>
