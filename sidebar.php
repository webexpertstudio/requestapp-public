<?php
if(isset($_GET['page'])) // ako postoji promenljiva "page" prikaži sidebar
{
    switch ($_GET['page'])
    { // U zavisnosti koja je stranica u pitanju, drugačije će izgledati sidebar
        case "proizvodi":
        ?>
            <div id="sidebar">
                <button onclick="location.href='index.php?page=porudzbeniceklijent'">Sve Vaše porudžbine</button>
                <?php
                if(isset($_SESSION['korpa']))
                {
                    $sql="SELECT * FROM roba WHERE id IN (";

                    foreach ($_SESSION['korpa'] as $id => $value)
                    {
                        $sql.=$id.",";
                    }

                    $sql=substr($sql, 0, -1).") ORDER BY naziv ASC"; 
                    
                    // Zamenjeno mysql_query sa mysqli_query
                    $query = mysqli_query($conn, $sql); // Koristite vašu konekciju ovde
                    while($row = mysqli_fetch_array($query))
                    {
                    ?>
                        <p><?php echo $row['naziv'] ?> x <?php echo $_SESSION['korpa'][$row['id']]['kolicina'] ?></p>
                    <?php
                    }
                    ?>
                    <hr />
                    <tr>
                    <td colspan="3" style="text-align:right"><b>Ukupan iznos porudžbine:</b></td>
                    <td id="cena" style="font-size:16px"><b><?php echo number_format($ukupnacena, 2, ',', '.'); ?> din</b></td>
                    <td></td>
                    </tr>
                    <br/>

                    <button onclick="location.href='index.php?page=korpa'">Idi na korpu!</button>

                    <?php    
                }
                else
                {
                    echo '<br><div style="margin-top:10px">Vaša korpa je prazna.</div>'; 
                }
                ?>
  
            </div>
        <?php
        break;
        
        // Ostali slučajevi ostaju nepromenjeni

    }
}
else
{
?>
<div id="sidebar">
    <button onclick="location.href='index.php?page=porudzbeniceklijent'">Sve Vaše porudžbine</button>
    <?php
    if(isset($_SESSION['korpa']))
    {
        $sql="SELECT * FROM roba WHERE id IN (";

        foreach ($_SESSION['korpa'] as $id => $value)
        {
            $sql.=$id.",";
        }

        $sql=substr($sql, 0, -1).") ORDER BY naziv ASC"; 

        // Zamenjeno mysql_query sa mysqli_query
        $query = mysqli_query($conn, $sql); // Koristite vašu konekciju ovde
        while($row = mysqli_fetch_array($query))
        {
        ?>
            <p><?php echo $row['naziv'] ?> x <?php echo $_SESSION['korpa'][$row['id']]['kolicina'] ?></p>
        <?php
        }
        ?>
        <hr />
        <tr>
        <td colspan="3" style="text-align:right"><b>Ukupan iznos porudžbine:</b></td>
        <td id="cena" style="font-size:16px"><b><?php echo number_format($ukupnacena, 2, ',', '.'); ?> din</b></td>
        <td></td>
        </tr>
        <br/>

        <button onclick="location.href='index.php?page=korpa'">Idi na korpu!</button>

        <?php    
    }
    else
    {
        ?>
        <br><div style="margin-top:10px">Vaša korpa je prazna.</div>
        <?php
    }
    ?>
</div>
<?php
}
?>