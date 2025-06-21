<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Baru Masuk - LapangKuy</title>
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
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
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
            border-left: 4px solid #ffc107;
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
        .status-pending {
            background: #fff3cd;
            color: #856404;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            margin: 10px 0;
        }
        .customer-card {
            background: #e8f4fd;
            border: 1px solid #b3d7ff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .earnings-highlight {
            background: #e8f5e8;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }
        .earnings-amount {
            font-size: 24px;
            font-weight: 700;
            color: #28a745;
        }
        .button {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 0;
        }
        .button:hover {
            background: #1e7e34;
        }
        .button-danger {
            background: #dc3545;
        }
        .button-danger:hover {
            background: #c82333;
        }
        .button-primary {
            background: #007bff;
        }
        .button-primary:hover {
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
            <h1>🔔 Booking Baru Masuk!</h1>
            <p>Ada customer yang ingin booking lapangan Anda</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Halo <strong>{{ $fieldOwner->name ?? 'Pemilik Lapangan' }}</strong>,</p>
            
            <p>Kabar gembira! Ada booking baru yang masuk untuk lapangan <strong>{{ $booking->field->name }}</strong>. Segera konfirmasi untuk mengamankan pendapatan Anda.</p>

            <!-- Booking Details Card -->
            <div class="booking-card">
                <div class="status-pending">⏰ Menunggu Konfirmasi Anda</div>
                
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
                        <div class="label">Tanggal Booking</div>
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
                        <div class="label">Tanggal Booking Dibuat</div>
                        <div class="value">{{ $booking->created_at->format('d M Y, H:i') }} WIB</div>
                    </div>
                </div>

                @if($booking->notes)
                <div class="booking-info">
                    <div style="flex: 1 1 100%;">
                        <div class="label">Catatan Customer</div>
                        <div class="value">{{ $booking->notes }}</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Customer Information -->
            <div class="customer-card">
                <h3 style="margin: 0 0 15px 0; color: #007bff;">👤 Informasi Customer</h3>
                
                <div class="booking-info">
                    <div>
                        <div class="label">Nama</div>
                        <div class="value">{{ $booking->user->name }}</div>
                    </div>
                    <div>
                        <div class="label">Email</div>
                        <div class="value">{{ $booking->user->email }}</div>
                    </div>
                </div>

                @if($booking->contact_phone)
                <div class="booking-info">
                    <div>
                        <div class="label">Nomor HP</div>
                        <div class="value">{{ $booking->contact_phone }}</div>
                    </div>
                    <div>
                        <div class="label">WhatsApp</div>
                        <div class="value">
                            <a href="https://wa.me/62{{ ltrim($booking->contact_phone, '0') }}" style="color: #25d366;">
                                Chat WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if($booking->contact_name && $booking->contact_name != $booking->user->name)
                <div class="booking-info">
                    <div style="flex: 1 1 100%;">
                        <div class="label">Nama Kontak</div>
                        <div class="value">{{ $booking->contact_name }}</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Earnings Highlight -->
            <div class="earnings-highlight">
                <div class="label">💰 Potential Earnings</div>
                <div class="earnings-amount">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">
                    Pendapatan untuk {{ $booking->duration }} jam booking
                </p>
            </div>

            <!-- Quick Actions -->
            <div style="text-align: center; margin: 30px 0;">
                <h3 style="color: #333;">⚡ Action Required - Segera Konfirmasi!</h3>
                <p style="color: #856404; font-weight: 600;">
                    ⚠️ Booking ini akan otomatis dibatalkan jika tidak dikonfirmasi dalam 2 jam
                </p>
                
                <a href="{{ route('owner.bookings') }}?booking={{ $booking->id }}&action=confirm" class="button">
                    ✅ Konfirmasi Booking
                </a>
                <br>
                <a href="{{ route('owner.bookings') }}?booking={{ $booking->id }}&action=reject" class="button button-danger">
                    ❌ Tolak Booking
                </a>
                <br>
                <a href="{{ route('owner.bookings') }}" class="button button-primary">
                    📋 Lihat Dashboard
                </a>
            </div>

            <!-- Important Notes -->
            <div style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; margin: 20px 0;">
                <h3 style="margin: 0 0 10px 0; color: #1976d2;">📋 Tips untuk Owner:</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Konfirmasi booking sesegera mungkin untuk kepuasan customer</li>
                    <li>Hubungi customer jika ada hal yang perlu diklarifikasi</li>
                    <li>Pastikan lapangan dalam kondisi baik sebelum waktu booking</li>
                    <li>Berikan pelayanan terbaik untuk mendapat review positif</li>
                </ul>
            </div>

            <!-- Contact Options -->
            <div style="background: #f1f8e9; border-left: 4px solid #4caf50; padding: 15px; margin: 20px 0;">
                <h3 style="margin: 0 0 10px 0; color: #388e3c;">📞 Hubungi Customer:</h3>
                <p style="margin: 0;">
                    @if($booking->contact_phone)
                    WhatsApp: <a href="https://wa.me/62{{ ltrim($booking->contact_phone, '0') }}" style="color: #25d366;">{{ $booking->contact_phone }}</a><br>
                    @endif
                    Email: <a href="mailto:{{ $booking->user->email }}" style="color: #007bff;">{{ $booking->user->email }}</a>
                </p>
            </div>

            <p>Jangan lewatkan kesempatan pendapatan ini! Konfirmasi booking sekarang untuk memastikan customer tidak beralih ke lapangan lain.</p>
            
            <p style="margin-top: 30px;">
                Sukses selalu untuk bisnis lapangan Anda!<br>
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
