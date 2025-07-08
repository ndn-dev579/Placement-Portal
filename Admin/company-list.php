<?php
require_once '../db-functions.php';
$companies = getAllCompanies();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company List</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
            padding: 20px;
            transition: 0.3s;


        }

        .card:hover {
            transform: translateY(-5px);
        }

        .logo {
            width: 120px;
            height: 80px;
            object-fit: contain;
            display: block;
            margin: 0 auto 15px;
            /* border: 1px solid rgba(11, 11, 11, 0.15); */
        }

        .card h3 {
            margin: 10px 0 5px;
            font-size: 20px;
        }

        .card p {
            font-size: 14px;
            color: #444;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 6px 12px;
            border-radius: 6px;

        }

        .card-content {
            border: rgba(89, 87, 87, 0.31) 1px solid;
            padding: .5rem 1rem;
            border-radius: 12px;

        }

        .actions {
            margin-top: 12px;
        }

        .actions a {
            text-decoration: none;
            margin-right: 10px;
            padding: 5px 10px;
            font-size: 13px;
            border-radius: 6px;
        }

        .edit {
            background-color: #17a2b8;
            color: white;
        }

        .delete {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>
    <h2 style="text-align:center; margin-bottom: 30px;">📋 Company Directory</h2>

    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == '1'): ?>
        <div style="color: green; margin-bottom: 10px;">
            ✅ Company deleted successfully!
        </div>
    <?php endif; ?>

    <div class="container">
        <?php if (empty($companies)): ?>
            <p>No companies found.</p>
        <?php else: ?>
            <?php foreach ($companies as $company): ?>
                <div class="card">
                    <img class="logo" src="/Placement-Portal/uploads/logo/<?= htmlspecialchars($company['logo_path']) ?>"
                        alt="Logo">

                    <div class="card-content">
                        <h3><?= htmlspecialchars($company['name']) ?></h3>
                        <p><?= htmlspecialchars($company['description']) ?></p>
                        <a href="<?= htmlspecialchars($company['website']) ?>" target="_blank">Visit Website</a>
                        <div class="actions">
                            <a class="edit" href="edit-company.php?id=<?= $company['id'] ?>">✏️ Edit</a>
                            <a class="delete" href="delete-company.php?id=<?= $company['id'] ?>"
                                onclick="return confirm('Are you sure you want to delete this company?')">🗑️ Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>