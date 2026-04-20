<?php

require 'config.php';

$stmt = $pdo->prepare("SELECT * FROM `clients`");
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="style.css">
<table>
    
        <th>Клиенты</th>
        <th>Действие</th>
        <th><a href="index.php" type="button" class="btn btn-danger">Вернуться</a></th>
    
    <?php foreach ($clients as $client): ?>
    <tr>
        <td><?= htmlspecialchars($client['name']) ?></td>
        <td><a href="delete.clients.php?id=<?php echo $clients['id'] ?>" type="button" class="btn btn-danger">Удалить</a></td>
        
    </tr>
    <?php endforeach; ?>
</table>

