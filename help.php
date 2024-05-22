<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Справочная система</title>
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
<h1>Справочная система</h1>
<h2>Открыть учёт магазина</h2>
<p>Кнопка "Открыть учёт магазина" отображает все данные о текущих изделиях, мастерах и видах изделий в магазине.</p>

<h2>Список мастеров</h2>
<p>Кнопка "Список мастеров" отображает таблицу со всеми мастерами, их именами, фамилиями, отчествами, стажем и разрядом. Здесь также можно добавить нового мастера.</p>

<h2>Список видов изделий</h2>
<p>Кнопка "Список видов изделий" отображает таблицу со всеми видами изделий. Здесь также можно добавить новый вид изделия.</p>

<h2>Список изделий</h2>
<p>Кнопка "Список изделий" отображает таблицу со всеми изделиями, их весом, пробой и мастером, который их изготовил. Здесь также можно добавить новое изделие.</p>

<h2>Сортировка по пробе</h2>
<p>Кнопка "Отсортировать по пробе (убыванию)" сортирует все изделия в списке по пробе в порядке убывания.</p>

<h2>Показать изделия мастера</h2>
<p>Форма "Выбрать мастера" позволяет выбрать фамилию мастера из выпадающего списка и отобразить все изделия, изготовленные этим мастером.</p>

<p><a class="button" href="main.php">Вернуться на главную</a></p>
</div>
</body>

</html>
