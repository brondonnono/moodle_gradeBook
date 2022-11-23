<?php
    $school_name = "CENTRE DE FORMATION ANGULAR";
    $school_logo_url = "./assets/logo/logo.png";
    $school_qrcode_img = "./assets/qrcode.png";
    $student_name = "SCOFIELD_MACBOOK237";
    $scholar_class = "NIVEAU INTERMEDIARE - NIVEAU 19";
    $userId = 9;
    require_once 'connexion.php';

    // configuration grade page sql to retrieve all students data
    $getUsers = $bdd->prepare("SELECT distinct u.* FROM mdl_user as u, mdl_role_assignments as r where u.id = r.userid and r.roleid=5 ORDER BY u.id ASC");
    $getUsers->execute();
    $users = $getUsers->fetchAll();
    echo '<br>';

    // gradebook sql to get student data from id
    $getUser = $bdd->prepare("SELECT distinct * FROM mdl_user WHERE id = ? ");
    $getUser->execute([$userId]);
    $user1 = $getUser->fetchAll();
    foreach ($user1 as $user) {
        // if ($user['id'] == 8)
        //     break;
    }
    $student_name = strtoupper($user['firstname']. ' '. $user['lastname']);

    // get courses list
    $getCourses = $bdd->prepare("SELECT * FROM mdl_course");
    $getCourses->execute();
    $coursesList = $getCourses->fetchAll();
    $getUserCoursesGrades = $bdd->prepare("SELECT g.*, c.fullname, c.shortname from mdl_grade_grades as g, mdl_course as c where userid =? and c.id = g.itemid");
    $getUserCoursesGrades->execute([$userId]);
    $userCoursesGradesRes = $getUserCoursesGrades->fetchAll();
    $userCoursesGrades = [];
    $i = 0;
    foreach($userCoursesGradesRes as $courseGrade) {
        $i++;
        $userCoursesGrades['courseid'] = $courseGrade['itemid'];
        $userCoursesGrades['grade'] = $courseGrade['finalgrade'];
        $userCoursesGrades['rawgrademax'] = $courseGrade['rawgrademax'];
        $userCoursesGrades['rawgrademin'] = $courseGrade['rawgrademin'];
        $userCoursesGrades['comment'] = $courseGrade['feedback'];
    }

    function getCorrectDecimal($grade) {
        if ($grade[2]!='.')
            return substr($grade,0,4);
        else
            return substr($grade,0,5);
    }
?>

<!Doctype HTML>
<html>

<head>
    <title>Bulletin templates</title>
    <link rel="shorcut icon" href="<?php echo $school_logo_url; ?>">
    <link rel="stylesheet" href="../theme/classic/style/moodle.css">
    <link rel="stylesheet" href="./style/main.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" charset="utf-8">
    <style type="text/css">
    @media print {
        .print-content th  {
            color: #495057 !important;
            background-color: #e9ecef !important;
            border-color: #dee2e6 !important;
        }
        table .table-secondary {
            background-color: #f1f3f5 !important;
        }
    }
  </style>
</head>

<body charset="utf-8">
    <div class="container-fluid p-2">
        <div id="page-content" class="row m-2 pb-3 d-print-block">
            <div class="mb-4 col-md-12 row">

                <table class="table">
                    <thead>
                        <th>
                            <div class="col-sm-8 text-center">
                                <img src="<?php echo $school_logo_url; ?>" alt="<?php echo $school_name; ?>" class="imgHeader">
                            </div>
                        </th>
                        <th>
                           <div class="col-sm-10 text-center">
                            <p>
                                    <?php echo $school_name; ?>
                                </p>
                                <p class="font-weight-bold">
                                    <?php echo $student_name; ?>
                                </p>
                                <p>
                                    <?php echo $scholar_class; ?>
                                </p>
                           </div>
                        </th>
                        <th>
                            <div class="col-sm-11 text-right">
                                <img src="<?php echo $school_qrcode_img; ?>" alt="QRCODE" class="imgHeader">
                            </div>
                        </th>
                    </thead>
                </table>
            </div>
            <div class="mb-4 col-md-12 table-responsive-md">
                <table class="table print-content table-bordered" id='it'>
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 30%">Matières</th>
                            <th style="width: 7%">Coefficient</th>
                            <?php
                                $nb_period = 6;
                                $sumPeriodGrade = [];
                                for ($i=1; $i<$nb_period+1; $i++) {
                                    echo '<th class="text-center" style="width: 10%">Periode '.$i.'</th>';
                                    $sumPeriodGrade[$i] = 0;
                                }
                            ?>
                            <!-- <th style="width: 10%">Periode 2</th>
                            <th style="width: 10%">Periode 3</th>
                            <th style="width: 10%">Periode 4</th>
                            <th style="width: 10%">Periode 5</th>
                            <th style="width: 10%">Periode 6</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($userCoursesGradesRes as $courseGrade) {
                                $courseNb = sizeof($userCoursesGradesRes);
                            ?>
                        <tr>
                        <?php 
                            echo '<td>'.$courseGrade['fullname'].'</td>';
                            echo '<td class="text-center font-weight-bold"> - </td>';
                            for ($i=1; $i<$nb_period+1; $i++) {
                                echo '<td class="text-center">'.getCorrectDecimal($courseGrade['finalgrade']).'</td>';
                                $sumPeriodGrade[$i] += getCorrectDecimal($courseGrade['finalgrade']);
                            }
                        }
                        ?>
                        </tr>
                        <tr class="table-secondary">
                            <td class="font-weight-bold">Moyenne globale</td>
                            <td class="moy text-center font-weight-bold"> - </td>
                            <?php
                                for ($i = 1; $i < $nb_period+1; $i++) {
                                    $sumPeriodGrade[$i] /= $courseNb;
                                    echo '<td class="moy">'.$sumPeriodGrade[$i].'</td>';
                                }
                            ?>
                        </tr>
                        <tr>
                            <td>Nombre de jours d'absence (par période)</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 border row table-responsive-md">
                <table class="table">
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <input type="submit" class="btn btn-primary print" onclick="printIt()" value="print">
    </div>
    
    <script type="text/javascript">
        function printIt() {
            var prtContent = document.getElementById("it");
            var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
            WinPrint.document.write(prtContent.innerHTML);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        }
        function alert() {
            alert('ok');
        }
    </script>

</body>

</html>