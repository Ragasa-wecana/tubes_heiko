<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Heiko</title>
  <link rel="shortcut icon" href="{{ asset('images/logos/favicon.png') }}" type="image/png">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes gradient-move {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .animated-gradient {
      background: linear-gradient(-45deg, #e0c3fc, #8ec5fc, #a1c4fd, #fbc2eb);
      background-size: 300% 300%;
      animation: gradient-move 10s ease infinite;
    }
  </style>
</head>

<body class="min-h-screen animated-gradient flex items-center justify-center">

  <div class="w-full max-w-md p-6 bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-xl">
    <div class="text-center mb-6">
      <a href="./index.html">
        <img src="{{ asset('images/logos/baju.PNG') }}" alt="Logo" class="mx-auto w-40">
      </a>
    </div>

    <!-- Alert Error -->
    @if ($errors->any())
      <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ url('/login') }}">
      @csrf
      <div class="mb-4">
        <label for="email" class="block text-gray-700 font-medium mb-2">Username</label>
        <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
      </div>

      <div class="mb-6">
        <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
        <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
      </div>

      <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition duration-300">
        Login
      </button>
    </form>
  </div>

</body>

</html>
