<!DOCTYPE html>
<html>
<head>
<style>
body { font-family: Inter, Arial, sans-serif; background:#f4f4f4; margin:0; padding:20px; }
.container { max-width:600px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; }
.header { background:linear-gradient(135deg,#302b63,#24243e); color:#fff; padding:30px; text-align:center; }
.header h2 { margin:0; font-size:22px; }
.body { padding:30px; color:#333; line-height:1.7; }
.field { margin-bottom:15px; }
.label { font-weight:600; color:#555; font-size:13px; text-transform:uppercase; letter-spacing:0.5px; }
.value { margin-top:4px; color:#111; }
.footer { background:#f9f9f9; padding:15px 30px; text-align:center; font-size:12px; color:#999; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>⚡ Elite Digital Agency</h2>
        <p style="margin:5px 0 0;opacity:0.8;font-size:14px">New Contact Form Submission</p>
    </div>
    <div class="body">
        <div class="field">
            <div class="label">Name</div>
            <div class="value">{{ $contact->name }}</div>
        </div>
        <div class="field">
            <div class="label">Email</div>
            <div class="value"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></div>
        </div>
        @if($contact->phone)
        <div class="field">
            <div class="label">Phone</div>
            <div class="value">{{ $contact->phone }}</div>
        </div>
        @endif
        @if($contact->subject)
        <div class="field">
            <div class="label">Subject</div>
            <div class="value">{{ $contact->subject }}</div>
        </div>
        @endif
        <div class="field">
            <div class="label">Message</div>
            <div class="value" style="background:#f9f9f9;padding:15px;border-radius:8px;border-left:4px solid #302b63">
                {{ $contact->message }}
            </div>
        </div>
        <div class="field">
            <div class="label">Received</div>
            <div class="value">{{ $contact->created_at->format('M d, Y \a\t H:i') }}</div>
        </div>
    </div>
    <div class="footer">
        Elite Digital Agency Management System
    </div>
</div>
</body>
</html>
