<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Halaman Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .button-container {
            text-align: center;
        }
        button {
            background-color: #4CAF50; /* Warna hijau */
            color: white;
            border: none;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px;
            cursor: pointer;
            border-radius: 5px; /* Sudut membulat */
            transition: background-color 0.3s; /* Efek transisi */
        }
        button:hover {
            background-color: #45a049; /* Warna hijau lebih gelap saat hover */
        }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <button onclick="window.location.href='dashboard/dashboard.php'">Login Pengguna</button>
        <button onclick="window.location.href='login_adgov/check_session.php'">Login Admin Pemerintah</button>
    </div>
</body>
</html> 