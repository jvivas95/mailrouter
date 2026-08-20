{{-- resources/views/emails/reset-password.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restablecer contraseña — MailRouter</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', Arial, sans-serif;
      background-color: #f4f4f8;
      padding: 40px 20px;
      color: #333;
    }

    .wrapper {
      max-width: 560px;
      margin: 0 auto;
    }

    /* Header */
    .header {
      background: #1a1a2e;
      border-radius: 14px 14px 0 0;
      padding: 32px;
      text-align: center;
    }

    .logo-icon {
      width: 56px;
      height: 56px;
      background: #6c63ff;
      border-radius: 14px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      margin-bottom: 16px;
    }

    .logo-text {
      font-size: 22px;
      font-weight: 700;
      color: #ffffff;
      letter-spacing: -0.5px;
    }

    .logo-sub {
      font-size: 12px;
      color: #6b6b8a;
      margin-top: 4px;
    }

    /* Body */
    .body {
      background: #ffffff;
      padding: 36px 32px;
    }

    .greeting {
      font-size: 18px;
      font-weight: 600;
      color: #1a1a2e;
      margin-bottom: 12px;
    }

    .text {
      font-size: 14px;
      color: #555;
      line-height: 1.7;
      margin-bottom: 28px;
    }

    /* Botón */
    .btn-wrapper {
      text-align: center;
      margin-bottom: 28px;
    }

    .btn {
      display: inline-block;
      background: #6c63ff;
      color: #ffffff !important;
      text-decoration: none;
      padding: 14px 32px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 600;
      letter-spacing: 0.3px;
    }

    .btn:hover {
      background: #7b73ff;
    }

    /* Expiry notice */
    .notice {
      background: #f8f8ff;
      border: 1px solid #e8e8f0;
      border-radius: 8px;
      padding: 14px 16px;
      font-size: 12px;
      color: #888;
      margin-bottom: 24px;
      line-height: 1.6;
    }

    .notice strong {
      color: #6c63ff;
    }

    /* URL fallback */
    .url-fallback {
      font-size: 12px;
      color: #888;
      line-height: 1.6;
      margin-bottom: 8px;
    }

    .url-link {
      font-size: 11px;
      color: #6c63ff;
      word-break: break-all;
    }

    /* Footer */
    .footer {
      background: #1a1a2e;
      border-radius: 0 0 14px 14px;
      padding: 20px 32px;
      text-align: center;
    }

    .footer-text {
      font-size: 11px;
      color: #6b6b8a;
      line-height: 1.6;
    }

    .footer-brand {
      color: #6c63ff;
      font-weight: 600;
    }

    /* Divider */
    .divider {
      border: none;
      border-top: 1px solid #f0f0f8;
      margin: 24px 0;
    }
  </style>
</head>
<body>
  <div class="wrapper">

    {{-- Header --}}
    <div class="header">
      <div class="logo-icon">📨</div>
      <div class="logo-text">MailRouter</div>
      <div class="logo-sub">Sistema de gestión de correos</div>
    </div>

    {{-- Body --}}
    <div class="body">

      <p class="greeting">Hola, {{ $name }} 👋</p>

      <p class="text">
        Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en MailRouter.
        Si fuiste tú, haz clic en el botón de abajo para crear una nueva contraseña.
      </p>

      {{-- Botón principal --}}
      <div class="btn-wrapper">
        <a href="{{ $url }}" class="btn">
          🔑 Restablecer contraseña
        </a>
      </div>

      {{-- Aviso de expiración --}}
      <div class="notice">
        <strong>⏱ Enlace temporal:</strong>
        Este enlace expirará en <strong>{{ $expire }} minutos</strong>.
        Si no solicitaste el restablecimiento, puedes ignorar este correo — tu contraseña no cambiará.
      </div>

      <hr class="divider">

      {{-- URL de fallback --}}
      <p class="url-fallback">
        Si el botón no funciona, copia y pega esta URL en tu navegador:
      </p>
      <p class="url-link">{{ $url }}</p>

    </div>

    {{-- Footer --}}
    <div class="footer">
      <p class="footer-text">
        Este correo fue enviado automáticamente por
        <span class="footer-brand">MailRouter</span>.<br>
        Por favor, no respondas a este mensaje.
      </p>
    </div>

  </div>
</body>
</html>
