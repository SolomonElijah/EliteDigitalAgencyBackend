<!DOCTYPE html>
<html>
<head>
<style>
body { font-family: Inter, Arial, sans-serif; background:#f4f4f4; margin:0; padding:20px; }
.container { max-width:600px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; }
.header { background:linear-gradient(135deg,#302b63,#24243e); color:#fff; padding:30px; text-align:center; }
.body { padding:30px; color:#333; line-height:1.8; }
.original { background:#f9f9f9; padding:15px; border-radius:8px; border-left:3px solid #ddd; margin-top:20px; color:#666; font-size:13px; }
.footer { background:#f9f9f9; padding:15px 30px; text-align:center; font-size:12px; color:#999; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2 style="margin:0;font-size:20px">⚡ Elite Digital Agency</h2>
    </div>
    <div class="body">
        <p>Hi {{ $contact->name }},</p>
        <p>{{ $replyMessage }}</p>
        <p>Best regards,<br><strong>Elite Digital Agency Team</strong></p>

        <div class="original">
            <strong>Your original message:</strong><br><br>
            {{ $contact->message }}
        </div>
    </div>
    <div class="footer">
        © {{ date('Y') }} Elite Digital Agency
    </div>
</div>
</body>
</html>
