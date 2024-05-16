<?php
include 'db.php';

$message = '';

// Получаем данные для выпадающего списка "Вид изделия"
try {
    $stmt_type = $pdo->query('SELECT id, name FROM product_type');
    $product_types = $stmt_type->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Ошибка выполнения запроса: " . $e->getMessage());
}

// Получаем данные для выпадающего списка "Фамилия мастера"
try {
    $stmt_master = $pdo->query('SELECT id, surname FROM master');
    $masters = $stmt_master->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Ошибка выполнения запроса: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['weight'], $_POST['sample'], $_POST['date'], $_POST['cost'], $_POST['type_id'], $_POST['master_id'])) {
        $weight = $_POST['weight'];
        $sample = $_POST['sample'];
        $date = $_POST['date'];
        $cost = $_POST['cost'];
        $type_id = $_POST['type_id'];
        $master_id = $_POST['master_id'];

        // Проверка, что все поля не пустые
        if (!empty($weight) && !empty($sample) && !empty($date) && !empty($cost) && !empty($type_id) && !empty($master_id)) {
            try {
                // Выполнение запроса на вставку данных
                $stmt = $pdo->prepare('INSERT INTO product (weight, sample, date, cost, type_id, master_id) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$weight, $sample, $date, $cost, $type_id, $master_id]);
                $message = 'Изделие успешно добавлено';
            } catch (PDOException $e) {
                die("Ошибка выполнения запроса: " . $e->getMessage());
            }
        } else {
            $message = 'Пожалуйста, заполните все поля';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить изделие</title>
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
    <h1>Добавить изделие</h1>
    <form method="post">
        <div class="input-group">
            <label for="weight">Вес:</label>
            <input type="number" id="weight" name="weight" required>
        </div>
        <div class="input-group">
            <label for="sample">Проба:</label>
            <input type="number" id="sample" name="sample" required>
        </div>
        <div class="input-group">
            <label for="date">Дата поступления:</label>
            <input type="date" id="date" name="date" required>
        </div>
        <div class="input-group">
            <label for="cost">Стоимость:</label>
            <input type="number" id="cost" name="cost" required>
        </div>
        <div class="input-group">
            <label for="type_id">Вид изделия:</label>
            <select id="type_id" name="type_id" required>
                <?php foreach ($product_types as $type): ?>
                    <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="input-group">
            <label for="master_id">Фамилия мастера:</label>
            <select id="master_id" name="master_id" required>
                <?php foreach ($masters as $master): ?>
                    <option value="<?= $master['id'] ?>"><?= $master['surname'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="input-group">
            <button type="submit" class="button">Сохранить</button>
        </div>
        <a href="main.php" class="button">Вернуться</a>
    </form>
    <p><?php echo $message; ?></p>
</div>
</body>

</html>
