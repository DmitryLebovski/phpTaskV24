<?php
include 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name'])) {
        $name = $_POST['name'];

        // Проверка, что поле не пустое
        if (!empty($name)) {
            try {
                // Выполнение запроса на вставку данных
                $stmt = $pdo->prepare('INSERT INTO product_type (name) VALUES (?)');
                $stmt->execute([$name]);
                $message = 'Тип изделия успешно добавлен';
            } catch (PDOException $e) {
                die("Ошибка выполнения запроса: " . $e->getMessage());
            }
        } else {
            $message = 'Пожалуйста, введите название типа изделия';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить тип изделия</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('background_main.png');
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            text-align: center;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .input-group input[type="text"],
        .input-group input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .input-group input[type="radio"] {
            display: none;
        }

        .input-group label {
            display: inline-block;
            padding: 8px 15px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .input-group input[type="radio"]:checked + label {
            background-color: #E2CFC0;
            color: black;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #E2CFC0;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .button:hover {
            background-color: #E2CFC0;
        }
    </style>
</head>

<body>
<div class="container">
    <h1>Добавить тип изделия</h1>
    <form method="post">
        <div class="input-group">
            <label for="name">Название типа изделия:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="input-group">
            <button type="submit" class="button">Добавить</button>
        </div>
        <a href="main.php" class="button">Вернуться</a>
        <p><?php echo $message; ?></p>
        <?php if (!empty($message) && $message === 'Данные успешно добавлены') : ?>
        <?php endif; ?>
    </form>
</div>
</body>

</html>
