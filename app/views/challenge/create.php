<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>challenge</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

</head>
<body>
<form action="challenge.php" method="post">
    <h1>Ajout d'un défi</h1>

    <label for="nom">Titre du défi :</label>
    <input type="text" name="nom" id="nom" required><br>

    <label for="desc">Description :</label>
    <textarea name="desc" id="desc" required></textarea><br>

    <label for="cat">Catégorie :</label>
    <select name="cat" id="cat" required>
        <option value="">-- Choisir --</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
    </select><br>

    <label for="date">Date limite :</label>
    <input type="date" name="date" id="date" required><br>

    <input type="submit" value="Valider" class="btn">
    <input type="reset" value="Annuler" class="btn">
</form>
</body>
</html>      