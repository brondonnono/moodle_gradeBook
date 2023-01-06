<?php
    if(isset($_POST['print'])) {
        $school_name = "CENTRE DE FORMATION ANGULAR";
        $school_logo_url = "./assets/logo/logo.png";
        $school_qrcode_img = "./assets/qrcode.png";
        $student_name = "SCOFIELD_MACBOOK237";
        $scholar_class = "NIVEAU INTERMEDIARE - NIVEAU 19";
        $userId = $_POST['userId'];
        // $userId  = 4;
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
        foreach ($user1 as $user) {}

        $student_name = strtoupper($user['firstname']. ' '. $user['lastname']);
        // get courses list
        $getCourses = $bdd->prepare("SELECT * FROM mdl_course");
        $getCourses->execute();
        $coursesList = $getCourses->fetchAll();
        $getUserCoursesGrades = $bdd->prepare("SELECT g.*, c.fullname, c.shortname from mdl_grade_grades as g, mdl_course as c where userid =? and c.id = g.itemid");
        $getUserCoursesGrades->execute([$userId]);
        $userCoursesGradesRes = $getUserCoursesGrades->fetchAll();
        $courseNb = sizeof($userCoursesGradesRes);
        $userCoursesGrades = [];
        $i = 0;

        $getTeachers = $bdd->prepare("SELECT c.id as courseid, c.shortname AS courseShortName, 
        c.fullname AS courseFullName, cx.id AS contextid, roleid,
        u.id as userid, CONCAT(u.firstname, ' ', u.lastname) AS name
        FROM mdl_context  cx, mdl_role_assignments ra, mdl_user u, mdl_course c
        WHERE contextlevel = 50 AND cx.id = ra.contextid AND roleid = 3
        AND u.id = userid AND c.id = cx.instanceid AND c.format != 'site'");

        $getTeachers->execute();
        $teachers = $getTeachers->fetchAll();
        $teachersList = [];
        $coursesTeachers = [];
        $cours = array();
        
        if ($courseNb != 0) {
            foreach($userCoursesGradesRes as $courseGrade) {
                $i++;
                foreach ($teachers as $teacher) {
                    if ($teacher['courseShortName'] == $courseGrade['shortname']) {
                        $obj = array(
                            'courseid' => $teacher['courseid'],
                            'courseShortName' => $teacher['courseShortName'],
                            'courseFullName' => $teacher['courseFullName'],
                            'userid' => $teacher['userid'],
                            'name' => $teacher['name'],
                        );
                        array_push($cours, $obj);
                    }
                }
            }
            $teachers = array();
            foreach ($cours as $cour) {
                array_push($teachers, $cour);
            }
        } else {
            $teachers = array();
        }

        function getCorrectDecimal($grade) {
            if ($grade[2]!='.')
                return substr($grade,0,4);
            else
                return substr($grade,0,5);
        }

        function getCorrectDecimalFromHex($number, $precision = 2) {
            $prec = 10 ** $precision;
            return intdiv(round($number * $prec, PHP_ROUND_HALF_DOWN), 1) / $prec;
        }

?>

<!Doctype HTML>
<html>

<head>
    <title>Bulletin</title>
    <link rel="shorcut icon" href="<?php echo $school_logo_url; ?>">
    <link rel="stylesheet" href="../theme/classic/style/moodle.css">
    <link rel="shortcut icon" href="http://localhost/moodle/theme/image.php/boost/theme/1666443348/favicon" />
    <link rel="stylesheet" href="./style/main.css">
    <meta http-equiv="refresh" content="5; url=./index.php">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" charset="utf-8">
    <style type="text/css">
        @media print {

            .print-content th,
            .print-content>tr.table-secondary,
            .tr_moy {
                color: #495057 !important;
                text-align: center !important;
                background-color: #e9ecef !important;
                border-color: #dee2e6 !important;
            }

            .print-content>tr.table-secondary,
            .tr_moy {
                color: #495057 !important;
                background-color: #f1f3f5 !important;
                border-color: #dee2e6 !important;
            }

            .imgHeader {
                height: 100px !important;
            }
        }

        .imgHeader {
            height: 100px !important;
        }
    </style>
</head>

<body charset="utf-8" onload="window.print()" onafterprint="javascript:">
    <div class="container-fluid p-2">
        <div id="page-content" class="row m-2 pb-3 d-print-block">
            <div class="mb-4 col-md-12 row">
                <table class="table">
                    <thead>
                        <th>
                            <div class="col-sm-8 text-center">
                                <img src="<?php echo $school_logo_url; ?>" alt="<?php echo $school_name; ?>"
                                    class="imgHeader">
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $comments = [];
                            $j = 0;
                            foreach($userCoursesGradesRes as $courseGrade) {
                                $courseNb = sizeof($userCoursesGradesRes);
                            ?>
                        <tr>
                            <?php
                                $comments[$j] = $courseGrade['feedback'] == '' ? 'Pas de commentaires disponibles' : $courseGrade['feedback'];                                
                                echo '<td>'.$courseGrade['fullname'].'</td>';
                                $j++;
                                echo '<td class="text-center font-weight-bold"> - </td>';
                                for ($i=1; $i<$nb_period+1; $i++) {
                                    echo '<td class="text-center">'.getCorrectDecimal($courseGrade['finalgrade']).'</td>';
                                    $sumPeriodGrade[$i] += getCorrectDecimal($courseGrade['finalgrade']);
                                }
                            }
                        ?>
                        </tr>
                        <tr class="table-secondary tr_moy">
                            <td class="font-weight-bold">Moyenne globale</td>
                            <td class="moy text-center font-weight-bold"> - </td>
                            <?php
                                if($courseNb != 0) {
                                    for ($i = 1; $i < $nb_period+1; $i++) {
                                        $sumPeriodGrade[$i] /= $courseNb;
                                        echo '<td class="moy text-center" style="background-color: #f1f3f5;">'.getCorrectDecimalFromHex($sumPeriodGrade[$i]).'</td>';
                                    }
                                } else {
                                    for ($i = 1; $i < $nb_period+1; $i++) {
                                        echo '<td class="moy text-center" style="background-color: #f1f3f5;"> - </td>';
                                    }
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
            <div class="mb-4 col-md-12">
                <div class="border p-3">
                    <?php
                        if ($courseNb != 0) {
                            for ($i=1; $i<$nb_period+1; $i++) {
                                $j = 0;
                                $periodX = 'Période '. $i;
                                echo '<div class="mb-6"><h4><u>Commentaire des enseignants ('.$periodX.')</u></h4>';
                                foreach ($teachers as $teacher) {
                                    echo '<div class="row">';
                                    echo '<h6 class="bold py-2 col-md-4">'.$teacher['courseFullName'].' :</h6>';
                                    echo '<div class="col-md-8 py-2">'.$comments[$j].' <i>['.$teacher['name'].']</i></div>';
                                    echo '</div>';
                                    $j++;
                                    $comments[$j] = $j >= sizeof($comments) ? 'Pas de commentaires disponibles' : $comments[$j];
                                }
                                echo '</div>';
                            }
                        } else {}
                    ?>
                </div>
            </div>
            <div class="mb-4 col-md-12">
                <div class="border p-3">
                    <div class="mb-6">
                        <h4><u>Compétences acquises</u></h4>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>

<?php
}
?>