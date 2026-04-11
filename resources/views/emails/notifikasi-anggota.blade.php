<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notifikasi->judul }}</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
        style="background-color: #f4f6f9; padding: 40px 0;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="600" cellspacing="0" cellpadding="0"
                    style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); padding: 32px 40px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: 0.5px;">
                                Koperasi CU Mentari Kasih
                            </h1>
                            <p style="margin: 6px 0 0; color: rgba(255,255,255,0.8); font-size: 13px;">
                                TP Pomalaa
                            </p>
                        </td>
                    </tr>

                    <!-- Notification Type Badge -->
                    <tr>
                        <td style="padding: 28px 40px 0;">
                            @php
                                $badgeColors = [
                                    'info' => ['bg' => '#e0f2fe', 'text' => '#0369a1', 'border' => '#7dd3fc'],
                                    'sukses' => ['bg' => '#dcfce7', 'text' => '#15803d', 'border' => '#86efac'],
                                    'peringatan' => ['bg' => '#fef9c3', 'text' => '#a16207', 'border' => '#fde047'],
                                    'bahaya' => ['bg' => '#fee2e2', 'text' => '#b91c1c', 'border' => '#fca5a5'],
                                ];
                                $tipe = $notifikasi->tipe->value;
                                $colors = $badgeColors[$tipe] ?? $badgeColors['info'];
                            @endphp
                            <span style="display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; border: 1px solid {{ $colors['border'] }};">
                                <span style="margin-right: 4px;">
                                    @switch($tipe)
                                        @case('info') ℹ️ @break
                                        @case('sukses') ✅ @break
                                        @case('peringatan') ⚠️ @break
                                        @case('bahaya') 🔴 @break
                                    @endswitch
                                </span>
                                {{ $notifikasi->tipe->label() }}
                            </span>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 20px 40px 0;">
                            <p style="margin: 0 0 6px; color: #6b7280; font-size: 13px;">
                                Halo, <strong style="color: #1f2937;">{{ $anggota->nama_lengkap }}</strong>
                            </p>
                            <h2 style="margin: 0 0 16px; color: #1f2937; font-size: 20px; font-weight: 700; line-height: 1.3;">
                                {{ $notifikasi->judul }}
                            </h2>
                            <div style="background-color: #f8fafc; border-left: 4px solid #2563eb; border-radius: 0 8px 8px 0; padding: 16px 20px;">
                                <p style="margin: 0; color: #374151; font-size: 15px; line-height: 1.7;">
                                    {{ $notifikasi->pesan }}
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Action Button -->
                    @if ($notifikasi->link)
                        <tr>
                            <td style="padding: 28px 40px 0; text-align: center;">
                                <a href="{{ $notifikasi->link }}"
                                    style="display: inline-block; padding: 14px 36px; background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: #ffffff; text-decoration: none; border-radius: 10px; font-size: 14px; font-weight: 600; letter-spacing: 0.3px; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);">
                                    Lihat Detail →
                                </a>
                            </td>
                        </tr>
                    @endif

                    <!-- Timestamp -->
                    <tr>
                        <td style="padding: 24px 40px 0;">
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                📅 {{ $notifikasi->created_at->translatedFormat('d F Y, H:i') }} WIB
                            </p>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 24px 40px 0;">
                            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 0;">
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 40px 32px; text-align: center;">
                            <p style="margin: 0 0 8px; color: #6b7280; font-size: 13px; font-weight: 600;">
                                Koperasi Credit Union (CU) Mentari Kasih TP Pomalaa
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 11px; line-height: 1.6;">
                                Email ini dikirim secara otomatis oleh sistem.<br>
                                Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Sub-footer -->
                <p style="margin: 20px 0 0; color: #9ca3af; font-size: 11px; text-align: center;">
                    &copy; {{ date('Y') }} Koperasi CU Mentari Kasih — Semua hak dilindungi.
                </p>
            </td>
        </tr>
    </table>
</body>

</html>
