
<?php 
    if(isset($_GET['added']))
    {
?>
        <div class="alert alert-success my-3" role="alert">
            Voting Berhasil Ditambahkan.
        </div>
<?php 
    }else if(isset($_GET['delete_id']))
    {
        $d_id = $_GET['delete_id'];
        mysqli_query($db, "DELETE FROM elections WHERE id = '". $d_id ."'") OR die(mysqli_error($db));
?>
        <div class="alert alert-danger my-3" role="alert">
            Voting Berhasil Dihapus!
        </div>
<?php

    }
?>




<div class="row my-3">
    <div class="col-4">
        <h3>Tambah Pemilihan</h3>
        <form method="POST">
            <div class="form-group">
                <label for="exampleInputnama" class="form-label">Topik Voting</label>
                <input type="text" name="election_topic" class="form-control" required />
            </div>
            <div class="form-group">
                <label for="exampleInputnama" class="form-label">Nomor Kandidat</label>
                <input type="number" name="number_of_candidates"  class="form-control" required />
            </div>
            <div class="form-group">
                <label for="exampleInputnama" class="form-label">Tanggal Mulai</label>
                <input type="text" onfocus="this.type='Date'" name="starting_date"  class="form-control" required />
            </div>
            <div class="form-group">
                <label for="exampleInputnama" class="form-label">Tanggal Selesai</label>
                <input type="text" onfocus="this.type='Date'" name="ending_date"  class="form-control" required />
            </div>
            <input type="submit" value="Tambah Vote" name="addElectionBtn" class="btn btn-success" />
        </form>
    </div>

    <div class="col-8">
        <h3>Pemilihan Mendatang</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Pemilihan</th>
                    <th scope="col"># Kandidat</th>
                    <th scope="col">Tanggal Mulai</th>
                    <th scope="col">Tanggal Selesai</th>
                    <th scope="col">Status </th>
                    <th scope="col">Aksi </th>
                    
                </tr>
            </thead>
            <tbody>
                <?php 
                    $fetchingData = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db)); 
                    $isAnyElectionAdded = mysqli_num_rows($fetchingData);

                    if($isAnyElectionAdded > 0)
                    {
                        $sno = 1;
                        while($row = mysqli_fetch_assoc($fetchingData))
                        {
                            $election_id = $row['id'];
                ?>
                            <tr>
                                <td><?php echo $sno++; ?></td>
                                <td><?php echo $row['election_topic']; ?></td>
                                <td><?php echo $row['no_of_candidates']; ?></td>
                                <td><?php echo $row['starting_date']; ?></td>
                                <td><?php echo $row['ending_date']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td> 
                                    <a href="index.php?edit_election=<?php echo $election_id; ?>" class="btn btn-sm btn-outline-warning"> Edit </a>
                                    <button class="btn btn-sm btn-danger" onclick="DeleteData(<?php echo $election_id; ?>)"> Delete </button>
                                </td>
                            </tr>
                <?php
                        }
                    }else {
            ?>
                        <tr> 
                            <td colspan="7"> Belum terdapat pemilihan! </td>
                        </tr>
            <?php
                    }
                ?>
            </tbody>    
        </table>
    </div>
</div>


<script>
    const DeleteData = (e_id) => 
    {
        let c = confirm("Are you really want to delete it?");

        if(c == true)
        {
            location.assign("index.php?addElectionPage=1&delete_id=" + e_id);
        }
    }
</script>

<?php 

    if(isset($_POST['addElectionBtn']))
    {
        $election_topic = mysqli_real_escape_string($db, $_POST['election_topic']);
        $number_of_candidates = mysqli_real_escape_string($db, $_POST['number_of_candidates']);
        $starting_date = mysqli_real_escape_string($db, $_POST['starting_date']);
        $ending_date = mysqli_real_escape_string($db, $_POST['ending_date']);
        $inserted_by = $_SESSION['username'];
        $inserted_on = date("Y-m-d");


        $date1=date_create($inserted_on);
        $date2=date_create($starting_date);
        $diff=date_diff($date1,$date2);
        
        
        if((int)$diff->format("%R%a") > 0)
        {
            $status = "InActive";
        }else {
            $status = "Active";
        }

        // inserting into db
        mysqli_query($db, "INSERT INTO elections(election_topic, no_of_candidates, starting_date, ending_date, status, inserted_by, inserted_on) VALUES('". $election_topic ."', '". $number_of_candidates ."', '". $starting_date ."', '". $ending_date ."', '". $status ."', '". $inserted_by ."', '". $inserted_on ."')") or die(mysqli_error($db));
        
    ?>
            <script> location.assign("index.php?addElectionPage=1&added=1"); </script>
    <?php

    }






?>