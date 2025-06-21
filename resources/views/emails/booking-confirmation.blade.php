<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Booking - LapangKuy</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .booking-card {
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
        .status-confirmed {
            background: #d4edda;
            color: #155724;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            margin: 10px 0;
        }
        .price-highlight {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }
        .price-amount {
            font-size: 24px;
            font-weight: 700;
            color: #856404;
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
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🎉 Booking Dikonfirmasi!</h1>
            <p>Terima kasih telah mempercayai LapangKuy</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Halo <strong>{{ $booking->user->name }}</strong>,</p>
            
            <p>Selamat! Booking Anda telah <strong>dikonfirmasi</strong> dan siap digunakan. Berikut adalah detail booking Anda:</p>

            <!-- Booking Details Card -->
            <div class="booking-card">
                <div class="status-confirmed">✅ Booking Dikonfirmasi</div>
                
                <div class="booking-info">
                    <div>
                        <div class="label">Kode Booking</div>
                        <div class="value">#{{ $booking->booking_code ?? 'LK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div>
                        <div class="label">Lapangan</div>
                        <div class="value">{{ $booking->field->name }}</div>
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
                        <div class="label">Lokasi</div>
                        <div class="value">{{ $booking->field->location }}</div>
                    </div>
                </div>

                @if($booking->notes)
                <div class="booking-info">
                    <div style="flex: 1 1 100%;">
                        <div class="label">Catatan</div>
                        <div class="value">{{ $booking->notes }}</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Price -->
            <div class="price-highlight">
                <div class="label">Total Pembayaran</div>
                <div class="price-amount">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
            </div>

            <!-- Action Buttons -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('bookings.show', $booking->id) }}" class="button">
                    📋 Lihat Detail Booking
                </a>
                <br>
                <a href="{{ route('fields.show', $booking->field->id) }}" class="button" style="background: #28a745;">
                    📍 Info Lapangan
                </a>
            </div>

            <!-- Important Notes -->
            <div style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; margin: 20px 0;">
                <h3 style="margin: 0 0 10px 0; color: #1976d2;">📋 Penting untuk Diperhatikan:</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Harap datang 15 menit sebelum waktu booking</li>
                    <li>Bawa dokumen identitas (KTP/SIM) untuk verifikasi</li>
                    <li>Pembatalan maksimal 4 jam sebelum jadwal booking</li>
                    <li>Hubungi pemilik lapangan jika ada kendala</li>
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

            <p>Jika Anda memiliki pertanyaan atau butuh bantuan, jangan ragu untuk menghubungi kami.</p>
            
            <p style="margin-top: 30px;">
                Selamat bermain!<br>
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
