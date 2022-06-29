<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,
          user-scalable=no, initial-scale=1.0,
          maximum-scale=1.0, minimum-scale=1.0"
    >
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>Procedures</title>
</head>
<body>

<?php foreach ($data as $item): ?>

<p>Номер процедуры: <?php echo $item->getNumber(); ?></p>
<p>ООС номер процедуры: <?php echo $item->getOosNumber(); ?></p>
<p>Ссылка:
    <a href="<?php echo $item->getLinkProcedure(); ?>">
        <?php echo $item->getLinkProcedure(); ?>
    </a>
</p>
<p>E-mail: <?php echo $item->getEmail(); ?></p>
<div>Документы: <br>
    <?php foreach ($item->getDocuments() as $document): ?>
    <b><?php echo $document['alias']; ?></b> -
        <a href="<?php echo $document['path']; ?>">
            <?php echo $document['path']; ?>
        </a>
        <br>
    <?php endforeach; ?>
</div>
    <hr>

<?php endforeach; ?>

</body>
</html>
