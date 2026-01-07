<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contact</title>
</head>
<body>
    <h1>Contact form</h1>
    <form method="POST" action="{{ route('contact.submit') }}">
        @csrf
        <div>
            <label>Name</label>
            <input type="text" name="name" value="{{ $name ?? old('name') }}">
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ $email ?? old('email') }}">
        </div>
        <button type="submit">Send</button>
    </form>
    @if(isset($name) || isset($email))
        <hr>
        <h2>Submitted data</h2>
        <p><strong>Name:</strong> {{ $name ?? '-' }}</p>
        <p><strong>Email:</strong> {{ $email ?? '-' }}</p>
    @endif
</body>
</html>