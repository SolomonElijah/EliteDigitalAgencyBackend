<!DOCTYPE html>
<html>
<head>
<style>
body { font-family: Inter, Arial, sans-serif; background:#f4f4f4; margin:0; padding:20px; }
.container { max-width:620px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08); }
.header { background:linear-gradient(135deg,#302b63,#24243e); padding:30px; text-align:center; }
.header img { height:40px; }
.header h2 { color:#fff; margin:10px 0 0; font-size:20px; }
.body { padding:35px; color:#333; line-height:1.8; font-size:15px; }
.footer { background:#f9f9f9; padding:20px 30px; text-align:center; font-size:12px; color:#999; border-top:1px solid #eee; }
.unsubscribe { color:#bbb; font-size:11px; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>⚡ Elite Digital Agency</h2>
    </div>
    <div class="body">
        {!! $body !!}
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} Elite Digital Agency. All rights reserved.</p>
        <p class="unsubscribe">You're receiving this because you're a valued contact of Elite Digital Agency.</p>
    </div>
</div>
</body>
</html>
