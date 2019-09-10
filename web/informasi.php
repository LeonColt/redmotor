<?php
require_once standart_path.'core/universal_methode.php';
$user = GetCurrentUser();
if($user !== null) {
    if($user->isAdmin() || $user->isSuperAdmin()) {
        if(filter_input(INPUT_POST, "info") !== null && filter_input(INPUT_POST, "fname") !== null) {
            $file = fopen("assets/FileSistem/".filter_input(INPUT_POST, "fname"), "w");
            fwrite($file, filter_input(INPUT_POST, "info"));
            fclose($file);
        }
        echo '<script src="assets/tinymce/tinymce.min.js"></script>
        <script>tinymce.init({selector:\'textarea\',
         height: 500,
         menu: {
         edit: {title: "Edit", items: "undo redo | cut copy paste pastetext | selectall"},
         insert: {title: "Insert", items: "link media | template hr"},
         view: {title: "View", items: "visualaid"},
         format: {title: "Format", items: "bold italic underline strikethrough superscript subscript | formats | removeformat"},
         table: {title: "Table", items: "inserttable tableprops deletetable | cell row column"},
         tools: {title: "Tools", items: "spellchecker code"}
         },
         plugins:"save",
         toolbar:" save bold italic underline strikethrough | alignleft aligncenter alignright alignjustify fontselect fontsizeselect | bullist numlist | undo redo | forecolor backcolor | charmap nonbreaking"});</script>';
    }
}
?>
<!-- === BEGIN CONTENT === -->
<div class="row margin-top-60 margin-horiz-20 margin-bottom-60">
    <div class="col-md-4">
        <div class="panel panel-primary invert">
            <div class="panel-heading">
                <h3 class="panel-title" align="center">
                    Jam Operasi
                </h3>
            </div>
            <div class="panel-body">
                <div>
                    <?php
                    //$str = "";
                    //write
                    $file = fopen("assets/FileSistem/info_jam.txt", "rw");
                    //fwrite($file, $str);
                    //read
                    if($file !== FALSE) {
                        while(!feof($file)) {echo fread($file, 4098);}
                        fclose($file);
                    }
                    ?>
                </div>
                <!-- End Main Column -->
                <?php
                if($user !== null) {
                    if($user->isAdmin() || $user->isSuperAdmin()) {
                        echo '<form method="POST" action="'.getUrlByRequest(RequestPageConstant::INFORMATION).'">';
                        echo "<textarea name='info'>";
                        $file = fopen("assets/FileSistem/info_jam.txt", "rw");
                        //fwrite($file, $str);
                        //read
                        if($file !== FALSE) {
                            while(!feof($file)) {echo fread($file, 4098);}
                            fclose($file);
                        }
                        echo "</textarea>";
                        echo "<button name='submitbtn' style='display: none;'></button>";
                        echo '<input type="hidden" value="info_jam.txt" name="fname">';
                        echo "</form>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-primary invert">
            <div class="panel-heading">
                <h3 class="panel-title" align="center">
                    Alamat
                </h3>
            </div>
            <div class="panel-body">
                <div>
                    <?php
                    $file = fopen("assets/FileSistem/alamat", "rw");
                    //fwrite($file, $str);
                    //read
                    if($file !== FALSE) {
                        while(!feof($file)) {echo fread($file, 4098);}
                        fclose($file);
                    }
                    ?>
                </div>
                <!-- End Main Column -->
                <?php
                if($user !== null) {
                    if($user->isAdmin() || $user->isSuperAdmin()) {
                        echo "<form method='post'>";
                        echo "<textarea name='info'>";

                        $file = fopen("assets/FileSistem/alamat", "r");
                        if($file !== FALSE) {
                            while (!feof($file)) echo fread($file, 4096);
                            fclose($file);
                        }
                        echo "</textarea>";
                        echo "<button name='submitbtn' style='display: none;'></button>";
                        echo '<input type="hidden" value="alamat" name="fname">';
                        echo "</form>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-red invert">
            <div class="panel-heading">
                <h3 class="panel-title" align="center">
                    PROMO
                </h3>
            </div>
            <div class="panel-body">
                <div>
                    <?php
                    $file = fopen("assets/FileSistem/promo", "rw");
                    //fwrite($file, $str);
                    //read
                    if($file !== FALSE) {
                        while(!feof($file)) {echo fread($file, 4098);}
                        fclose($file);
                    }
                    ?>
                </div>
                <!-- End Main Column -->
                <?php
                if($user !== null) {
                    if($user->isAdmin() || $user->isSuperAdmin()) {
                        echo "<form method='post'>";
                        echo "<textarea name='info'>";

                        $file = fopen("assets/FileSistem/promo", "r");
                        if($file !== FALSE) {
                            while (!feof($file)) echo fread($file, 4096);
                            fclose($file);
                        }
                        echo "</textarea>";
                        echo "<button name='submitbtn' style='display: none;'></button>";
                        echo '<input type="hidden" value="promo" name="fname">';
                        echo "</form>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- End Main Column -->
<!-- === END CONTENT === -->