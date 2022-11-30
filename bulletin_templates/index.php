<!Doctype HTML>
<html>

<head>
    <title>Génération des bulletins de notes </title>
    <link rel="stylesheet" type="text/css" href="./style/main.css">
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
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a href="#" class="nav-link active" aria-current="page">
                            Retour
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="row p-y-100">
        <aside class="col-3"></aside>
        <aside class="col-6 p-3">
            <div class="card text-center">
                <div class="card-header bg-primary text-light">
                  Sélectionner un étudiant dans la liste
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-floating mb-3">
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                              </select>
                            <label for="floatingInput">Sélectionner un etudiant</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                            <label for="floatingPassword">Password</label>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-muted">
                    <a href="#" class="btn btn-primary">Générer</a>
                </div>
              </div>
        </aside>
        <aside class="col-3"></aside>

    </section>
</body>

</html>