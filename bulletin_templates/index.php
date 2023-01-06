<?php
    require('connexion.php');
?>

<!Doctype HTML>
<html>

<head>
    <title>Génération et impression des bulletins de notes</title>
    <link rel="stylesheet" type="text/css" href="./style/main.css">
    <link rel="shortcut icon" href="../theme/image.php/boost/theme/1666443348/favicon"/>
    <link rel="stylesheet" type="text/css" href="./style/bootstrap/css/bootstrap.min.css">
    <srcipt src="./style/bootstrap/js/bootstrap.min.js"></srcipt>
</head>

<body class="bg-light">
    <header>
        <div
            class=" bg-white d-flex p-3 flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom navbar navbar-expand-lg navbar-dark bd-navbar sticky-top card-1 ">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none">
                <span class="fs-4 ">Génération de bulletin de notes</span>
            </a>
            <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">
                <!-- <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a href="../" class="nav-link active" aria-current="page"> Retour </a>
                    </li>
                </ul> -->
                <a class="btn btn-sm btn-primary" href="../"> << Retour</a>
            </nav>
        </div>
    </header>

    <section class="row p-y-100">
        <aside class="col-3"></aside>
        <aside class="col-6 p-3">
            <form method="POST" action="view_notes.php">
                <div class="card text-center">
                    <div class="card-header bg-primary text-light">
                        Sélectionner un étudiant dans la liste
                    </div>
                    <div class="card-body">
                        <div class="form-floating mb-3">
                            <select class="form-select" aria-label="Default select example" name="userId">
                                <?php 
                                    $getUser = $bdd->prepare("SELECT distinct u.id AS id, CONCAT(u.firstname, ' ', u.lastname) AS name, u.username, u.email FROM mdl_user u, mdl_role_assignments r WHERE u.id = r.userid AND r.roleid = 5");
                                    $getUser->execute();
                                    $users = $getUser->fetchAll();
                                    $nbUsers = 0;
                                    foreach ($users as $user) {
                                        $nbUsers++;
                                        echo "<option value='".$user['id']."' >".$user['name']."</option>";
                                    }
                                ?>
                            </select>
                            <label for="floatingInput">Sélectionner un étudiant</label>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        <?php
                            if ($nbUsers == 0)
                                echo '<input type="submit" value="Imprimer" name="print" class="btn btn-primary disabled"></input>';
                            else
                                echo '<input type="submit" value="Imprimer" name="print" class="btn btn-primary"></input>';  
                        ?>
                    </div>
                </div>
            </form>
        </aside>
        <aside class="col-3"></aside>
    </section>

</body>

</html>