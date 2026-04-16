<?php
require_once 'config.php';

// Обработка добавления записи
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    $stmt = $pdo->prepare("INSERT INTO bookings (client_id, trainer_id, hall_id, workout_id, booking_date, booking_time) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['client_id'], $_POST['trainer_id'], $_POST['hall_id'], $_POST['workout_id'], $_POST['booking_date'], $_POST['booking_time']]);
    echo "<script>alert('Запись добавлена!');</script>";
}

// Получение списков для выпадающих меню
$clients = $pdo->query("SELECT * FROM clients")->fetchAll();
$trainers = $pdo->query("SELECT * FROM trainers")->fetchAll();
$halls = $pdo->query("SELECT * FROM halls")->fetchAll();
$workouts = $pdo->query("SELECT * FROM workouts")->fetchAll();

// Получение всех записей с JOIN
$bookings = $pdo->query("
    SELECT b.*, c.name as client_name, t.name as trainer_name, h.name as hall_name, w.name as workout_name 
    FROM bookings b
    JOIN clients c ON b.client_id = c.id
    JOIN trainers t ON b.trainer_id = t.id
    JOIN halls h ON b.hall_id = h.id
    JOIN workouts w ON b.workout_id = w.id
    ORDER BY b.booking_date DESC, b.booking_time DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Фитнес-клуб - Управление тренировками</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        form { background: #f4f4f4; padding: 20px; border-radius: 5px; margin-bottom: 30px; }
        select, input, button { margin: 5px; padding: 8px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #333; color: white; }
        .form-group { display: inline-block; margin-right: 10px; }
    </style>
</head>
<body>

<h2>📅 Запись на тренировку</h2>
<form method="post">
    <div class="form-group">
        <label>Клиент:</label>
        <select name="client_id" required>
            <option value="">-- Выберите клиента --</option>
            <?php foreach ($clients as $client): ?>
                <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Тренер:</label>
        <select name="trainer_id" required>
            <option value="">-- Выберите тренера --</option>
            <?php foreach ($trainers as $trainer): ?>
                <option value="<?= $trainer['id'] ?>"><?= htmlspecialchars($trainer['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Зал:</label>
        <select name="hall_id" required>
            <option value="">-- Выберите зал --</option>
            <?php foreach ($halls as $hall): ?>
                <option value="<?= $hall['id'] ?>"><?= htmlspecialchars($hall['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Тренировка:</label>
        <select name="workout_id" required>
            <option value="">-- Выберите тип --</option>
            <?php foreach ($workouts as $workout): ?>
                <option value="<?= $workout['id'] ?>"><?= htmlspecialchars($workout['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Дата:</label>
        <input type="date" name="booking_date" required>
    </div>

    <div class="form-group">
        <label>Время:</label>
        <input type="time" name="booking_time" required>
    </div>

    <button type="submit" name="book">Записать</button>
</form>

<h2>📋 Список записей</h2>
<table>
    <table>
        <th>Клиент</th><th>Тренер</th><th>Зал</th><th>Тренировка</th><th>Дата</th><th>Время</th>
    </tr>
    <?php foreach ($bookings as $booking): ?>
    <tr>
        <td><?= htmlspecialchars($booking['client_name']) ?></td>
        <td><?= htmlspecialchars($booking['trainer_name']) ?></td>
        <td><?= htmlspecialchars($booking['hall_name']) ?></td>
        <td><?= htmlspecialchars($booking['workout_name']) ?></td>
        <td><?= $booking['booking_date'] ?></td>
        <td><?= $booking['booking_time'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<hr>
<h3>➕ Быстрое добавление новых данных</h3>
<form method="post">
    <input type="text" name="new_client" placeholder="Новый клиент">
    <button type="submit" name="add_client">Добавить клиента</button>
    <input type="text" name="new_trainer" placeholder="Новый тренер">
    <button type="submit" name="add_trainer">Добавить тренера</button>
    <input type="text" name="new_halls" placeholder="Новый зал">
    <button type="submit" name="add_halls">Добавить зал</button>
</form>
<?php
if (isset($_POST['add_client']) && !empty($_POST['new_client'])) {
    $stmt = $pdo->prepare("INSERT INTO clients (name) VALUES (?)");
    $stmt->execute([$_POST['new_client']]);
    echo "<p>Клиент добавлен. <a href=''>Обновить</a></p>";
}
?>
<?php
if (isset($_POST['add_trainer']) && !empty($_POST['new_trainer'])) {
    $stmt = $pdo->prepare("INSERT INTO trainers (name) VALUES (?)");
    $stmt->execute([$_POST['new_trainer']]);
    echo "<p>Тренер добавлен. <a href=''>Обновить</a></p>";
}
?>
<?php
if (isset($_POST['add_halls']) && !empty($_POST['new_halls'])) {
    $stmt = $pdo->prepare("INSERT INTO halls (name) VALUES (?)");
    $stmt->execute([$_POST['new_halls']]);
    echo "<p>Зал добавлен. <a href=''>Обновить</a></p>";
}
?>
</body>
</html>