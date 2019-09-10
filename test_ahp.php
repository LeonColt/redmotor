<?php

/**
 * Created by PhpStorm.
 * User: LC
 * Date: 12/04/2017
 * Time: 17:26
 */

/*
require_once  'core/MySQLAccess.php';

$col = new Collection();
$col->add("a");
$col->add("b");
$col->add("c");
$col->add("d");

echo "last : ". key(end($col))."<br>";

foreach ($col as $index => $item) echo  $index." = ".$item."<br>";

echo  "<br>";

for($i = 0; $i < count($col); $i++) echo $i." = ".$col[$i]."<br>";

echo "<br>";

for ($i = $col->begin(); $i !== $col->end(); $i = $col->next()) echo  $col->key()." = ".$i."<br>";

echo "<br>";

$select = new Select("a");
$select->appendColumn("b");
$select->appendColumn("c");
$select->appendColumn("d");
$select->appendJoin(new JoinTable("e", JoinTable::INNER_JOIN));
$select->appendJoinOn("a", "b", "=", "e", "f");

echo $select;

echo "<br>";


$insert = new Insert("ta");;
$insert->appendColumnValue(new Column("a"), new Parameter($insert->getParameterVariableIntegerOrder(), "x"));
$insert->appendColumnValue(new Column("b"), new Parameter($insert->getParameterVariableIntegerOrder(), "x"));
$insert->appendColumnValue(new Column("c"), new Parameter($insert->getParameterVariableIntegerOrder(), "x"));
$insert->appendColumnValue(new Column("a"), new Parameter($insert->getParameterVariableIntegerOrder(), "x"));
$insert->appendColumnValue(new Column("b"), new Parameter($insert->getParameterVariableIntegerOrder(), "x"));
$insert->appendColumnValue(new Column("c"), new Parameter($insert->getParameterVariableIntegerOrder(), "x"));

echo  $insert;

exit();
*/

/*
const LIMIT = 10;
const MAX_COLUMN = 3;
$temp = array();
for( $i = 0; $i < LIMIT; $i++ )
    array_push($temp, ("cell ".$i));
?>
<table border="1">
    <?php
    $col = MAX_COLUMN;
    $first = true;
    for( $i = 0; $i < count($temp); $i++ ) {
        if( $col > MAX_COLUMN - 1) {
            if($first)$first = false; else echo "</tr>";
            echo "<tr>";
            $col = 0;
        }
        echo "<td>";
        echo $temp[$i];
        echo "</td>";
        $col++;
    }
    ?>
</table>
*/

var_dump($_POST);

require_once 'process/AHP.php';
$criteria = array("harga", "fitur", "teknologi");
$alternatives = array("samsung", "nokia", "sony");
$ahp = new AHP($criteria, $alternatives);
$ahp->getPairComparison(AHP::GLOBAL)->setWeightValue("harga", "fitur", (double)1.5);
$ahp->getPairComparison(AHP::GLOBAL)->setWeightValue("fitur", "harga", (double)1/1.5);
$ahp->getPairComparison(AHP::GLOBAL)->setWeightValue("harga", "teknologi", (double)4);
$ahp->getPairComparison(AHP::GLOBAL)->setWeightValue("teknologi", "harga", (double)1/4);
$ahp->getPairComparison(AHP::GLOBAL)->setWeightValue("fitur", "teknologi", (double)3);
$ahp->getPairComparison(AHP::GLOBAL)->setWeightValue("teknologi", "fitur", (double)1/3);

$pc_harga = $ahp->getPairComparison("harga");
$pc_harga->setWeightValue("samsung", "nokia", (double)4);
$pc_harga->setWeightValue("nokia", "samsung", (double)1/4);
$pc_harga->setWeightValue("samsung", "sony", (double)3);
$pc_harga->setWeightValue("sony", "samsung", (double)1/3);
$pc_harga->setWeightValue("nokia", "sony", (double)0.5);
$pc_harga->setWeightValue("sony", "nokia", (double)1/0.5);

$pc_fitur = $ahp->getPairComparison("fitur");
$pc_fitur->setWeightValue("samsung", "nokia", (double)0.5);
$pc_fitur->setWeightValue("nokia", "samsung", (double)1/0.5);
$pc_fitur->setWeightValue("samsung", "sony", (double)2);
$pc_fitur->setWeightValue("sony", "samsung", (double)1/2);
$pc_fitur->setWeightValue("nokia", "sony", (double)3);
$pc_fitur->setWeightValue("sony", "nokia", (double)1/3);

$pc_teknologi = $ahp->getPairComparison("teknologi");
$pc_teknologi->setWeightValue("samsung", "nokia", (double)0.33);
$pc_teknologi->setWeightValue("nokia", "samsung", (double)1/0.33);
$pc_teknologi->setWeightValue("samsung", "sony", (double)2);
$pc_teknologi->setWeightValue("sony", "samsung", (double)1/2);
$pc_teknologi->setWeightValue("nokia", "sony", (double)3);
$pc_teknologi->setWeightValue("sony", "nokia", (double)1/3);

$ocw = $ahp->calculate();
$data = $ahp->getPairComparison(AHP::GLOBAL);
$matrix = $data->getMatrix();
?>
<table border="1">
    <tr>
        <td></td>
        <td>harga</td>
        <td>fitur/td>
        <td>teknologi</td>
        <td>Priority Vector</td>
    </tr>
    <tr>
        <td>harga</td>
        <td><?php echo number_format($matrix->at("harga", "harga"), 4); ?></td>
        <td><?php echo number_format($matrix->at("harga", "fitur"), 4); ?></td>
        <td><?php echo number_format($matrix->at("harga", "teknologi"), 4); ?></td>
        <td><?php echo number_format($matrix->at("harga", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td>fitur</td>
        <td><?php echo number_format($matrix->at("fitur", "harga"), 4); ?></td>
        <td><?php echo number_format($matrix->at("fitur", "fitur"), 4); ?></td>
        <td><?php echo number_format($matrix->at("fitur", "teknologi"), 4); ?></td>
        <td><?php echo number_format($matrix->at("fitur", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td>teknologi</td>
        <td><?php echo number_format($matrix->at("teknologi", "harga"), 4); ?></td>
        <td><?php echo number_format($matrix->at("teknologi", "fitur"), 4); ?></td>
        <td><?php echo number_format($matrix->at("teknologi", "teknologi"), 4); ?></td>
        <td><?php echo number_format($matrix->at("teknologi", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td>jumlah</td>
        <td><?php echo number_format($matrix->at("total", "harga"), 4); ?></td>
        <td><?php echo number_format($matrix->at("total", "fitur"), 4); ?></td>
        <td><?php echo number_format($matrix->at("total", "teknologi"), 4); ?></td>
        <td><?php echo number_format($matrix->at("total", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td colspan="4">Principal Eigen Value</td>
        <td><?php echo number_format($data->getEigenValue(), 4);?></Td>
    </tr>
    <tr>
        <td colspan="4">Consistency Index</td>
        <td><?php echo number_format($data->getConsistencyIndex(), 4);?></Td>
    </tr>
    <tr>
        <td colspan="4">Consistency Ratio</td>
        <td><?php echo number_format($data->getConsistencyRatio(), 4);?></Td>
    </tr>
</table>

<?php
$data = $ahp->getPairComparison("harga");
$matrix = $data->getMatrix();
?>

<br><br>
Harga

<table border="1">
    <tr>
        <td></td>
        <td>samsung</td>
        <td>nokia</td>
        <td>sony</td>
        <td>Priority Vector</td>
    </tr>
    <tr>
        <td>harga</td>
        <td><?php echo number_format($matrix->at("samsung", "samsung"), 4); ?></td>
        <td><?php echo number_format($matrix->at("samsung", "nokia"), 4); ?></td>
        <td><?php echo number_format($matrix->at("samsung", "sony"), 4); ?></td>
        <td><?php echo number_format($matrix->at("samsung", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td>fitur</td>
        <td><?php echo number_format($matrix->at("nokia", "samsung"), 4); ?></td>
        <td><?php echo number_format($matrix->at("nokia", "nokia"), 4); ?></td>
        <td><?php echo number_format($matrix->at("nokia", "sony"), 4); ?></td>
        <td><?php echo number_format($matrix->at("nokia", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td>teknologi</td>
        <td><?php echo number_format($matrix->at("sony", "samsung"), 4); ?></td>
        <td><?php echo number_format($matrix->at("sony", "nokia"), 4); ?></td>
        <td><?php echo number_format($matrix->at("sony", "sony"), 4); ?></td>
        <td><?php echo number_format($matrix->at("sony", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td>jumlah</td>
        <td><?php echo number_format($matrix->at("total", "samsung"), 4); ?></td>
        <td><?php echo number_format($matrix->at("total", "nokia"), 4); ?></td>
        <td><?php echo number_format($matrix->at("total", "sony"), 4); ?></td>
        <td><?php echo number_format($matrix->at("total", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td colspan="4">Principal Eigen Value</td>
        <td><?php echo number_format($data->getEigenValue(), 4);?></Td>
    </tr>
    <tr>
        <td colspan="4">Consistency Index</td>
        <td><?php echo number_format($data->getConsistencyIndex(), 4);?></Td>
    </tr>
    <tr>
        <td colspan="4">Consistency Ratio</td>
        <td><?php echo number_format($data->getConsistencyRatio(), 4);?></Td>
    </tr>
</table>

<?php
$data = $ahp->getPairComparison("fitur");
$matrix = $data->getMatrix();
?>

<br><br>
Fitur

<table border="1">
    <tr>
        <td></td>
        <td>samsung</td>
        <td>nokia</td>
        <td>sony</td>
        <td>Priority Vector</td>
    </tr>
    <tr>
        <td>harga</td>
        <td><?php echo number_format($matrix->at("samsung", "samsung"), 4); ?></td>
        <td><?php echo number_format($matrix->at("samsung", "nokia"), 4); ?></td>
        <td><?php echo number_format($matrix->at("samsung", "sony"), 4); ?></td>
        <td><?php echo number_format($matrix->at("samsung", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td>fitur</td>
        <td><?php echo number_format($matrix->at("nokia", "samsung"), 4); ?></td>
        <td><?php echo number_format($matrix->at("nokia", "nokia"), 4); ?></td>
        <td><?php echo number_format($matrix->at("nokia", "sony"), 4); ?></td>
        <td><?php echo number_format($matrix->at("nokia", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td>teknologi</td>
        <td><?php echo number_format($matrix->at("sony", "samsung"), 4); ?></td>
        <td><?php echo number_format($matrix->at("sony", "nokia"), 4); ?></td>
        <td><?php echo number_format($matrix->at("sony", "sony"), 4); ?></td>
        <td><?php echo number_format($matrix->at("sony", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td>jumlah</td>
        <td><?php echo number_format($matrix->at("total", "samsung"), 4); ?></td>
        <td><?php echo number_format($matrix->at("total", "nokia"), 4); ?></td>
        <td><?php echo number_format($matrix->at("total", "sony"), 4); ?></td>
        <td><?php echo number_format($matrix->at("total", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td colspan="4">Principal Eigen Value</td>
        <td><?php echo number_format($data->getEigenValue(), 4);?></Td>
    </tr>
    <tr>
        <td colspan="4">Consistency Index</td>
        <td><?php echo number_format($data->getConsistencyIndex(), 4);?></Td>
    </tr>
    <tr>
        <td colspan="4">Consistency Ratio</td>
        <td><?php echo number_format($data->getConsistencyRatio(), 4);?></Td>
    </tr>
</table>

<?php
$data = $ahp->getPairComparison("teknologi");
$matrix = $data->getMatrix();
?>

<br><br>
Teknologi

<table border="1">
    <tr>
        <td></td>
        <td>samsung</td>
        <td>nokia</td>
        <td>sony</td>
        <td>Priority Vector</td>
    </tr>
    <tr>
        <td>harga</td>
        <td><?php echo number_format($matrix->at("samsung", "samsung"), 4); ?></td>
        <td><?php echo number_format($matrix->at("samsung", "nokia"), 4); ?></td>
        <td><?php echo number_format($matrix->at("samsung", "sony"), 4); ?></td>
        <td><?php echo number_format($matrix->at("samsung", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td>fitur</td>
        <td><?php echo number_format($matrix->at("nokia", "samsung"), 4); ?></td>
        <td><?php echo number_format($matrix->at("nokia", "nokia"), 4); ?></td>
        <td><?php echo number_format($matrix->at("nokia", "sony"), 4); ?></td>
        <td><?php echo number_format($matrix->at("nokia", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td>teknologi</td>
        <td><?php echo number_format($matrix->at("sony", "samsung"), 4); ?></td>
        <td><?php echo number_format($matrix->at("sony", "nokia"), 4); ?></td>
        <td><?php echo number_format($matrix->at("sony", "sony"), 4); ?></td>
        <td><?php echo number_format($matrix->at("sony", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td>jumlah</td>
        <td><?php echo number_format($matrix->at("total", "samsung"), 4); ?></td>
        <td><?php echo number_format($matrix->at("total", "nokia"), 4); ?></td>
        <td><?php echo number_format($matrix->at("total", "sony"), 4); ?></td>
        <td><?php echo number_format($matrix->at("total", "priority_vector"), 4); ?></td>
    </tr>
    <tr>
        <td colspan="4">Principal Eigen Value</td>
        <td><?php echo number_format($data->getEigenValue(), 4);?></Td>
    </tr>
    <tr>
        <td colspan="4">Consistency Index</td>
        <td><?php echo number_format($data->getConsistencyIndex(), 4);?></Td>
    </tr>
    <tr>
        <td colspan="4">Consistency Ratio</td>
        <td><?php echo number_format($data->getConsistencyRatio(), 4);?></Td>
    </tr>
</table>

<br><br><br>
Overall Composite Weight
<table border="1">
    <tr>
        <td>Overall Composite Weight</td>
        <td>Weight</td>
        <td>samsung</td>
        <td>nokia</td>
        <td>sony</td>
    </tr>
    <tr>
        <td>Harga</td>
        <td><?php echo number_format($ocw->at("harga", AHP::WEIGHT), 4); ?></td>
        <td><?php echo number_format($ocw->at("harga", "samsung"), 4); ?></td>
        <td><?php echo number_format($ocw->at("harga", "nokia"), 4); ?></td>
        <td><?php echo number_format($ocw->at("harga", "sony"),4); ?></td>
    </tr>
    <tr>
        <td>Fitur</td>
        <td><?php echo number_format($ocw->at("fitur", AHP::WEIGHT), 4); ?></td>
        <td><?php echo number_format($ocw->at("fitur", "samsung"), 4); ?></td>
        <td><?php echo number_format($ocw->at("fitur", "nokia"), 4); ?></td>
        <td><?php echo number_format($ocw->at("fitur", "sony"),4); ?></td>
    </tr>
    <tr>
        <td>Teknologi</td>
        <td><?php echo number_format($ocw->at("teknologi", AHP::WEIGHT), 4); ?></td>
        <td><?php echo number_format($ocw->at("teknologi", "samsung"), 4); ?></td>
        <td><?php echo number_format($ocw->at("teknologi", "nokia"), 4); ?></td>
        <td><?php echo number_format($ocw->at("teknologi", "sony"),4); ?></td>
    </tr>
    <tr>
        <td>Composite Weight</td>
        <td></td>
        <td><?php echo number_format($ocw->at(AHP::COMPOSITE_WEIGHT, "samsung"), 4); ?></td>
        <td><?php echo number_format($ocw->at(AHP::COMPOSITE_WEIGHT, "nokia"), 4); ?></td>
        <td><?php echo number_format($ocw->at(AHP::COMPOSITE_WEIGHT, "sony"),4); ?></td>
    </tr>
</table>