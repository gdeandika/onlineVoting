<?php 
    $election_id = isset($_GET['edit_election'])? $_GET['edit_election'] : null;
?>

<!--content start-->
<div class="container mb-5" style="margin-top: 3rem;">
    <div class="row">
        <div class="col-lg-12" style="min-height: 480px;">
            <div class="card">
                <div class="card-header">
                    <h3 class="p-1" style="font-weight: bold;">Edit Data Kategori</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <?php
                                if (isset($election_id)) {
                                    $id = $election_id;
                                    $data = mysqli_query($db, "SELECT * FROM elections WHERE id ='$id'");

                                    if ($data && mysqli_num_rows($data) > 0) {
                                        $d = mysqli_fetch_array($data);
                            ?>
                            <form action="" method="POST">
                                <div class="form-group m-2">
                                    <label for="">ID Voting</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($d['id']);?>" required name="id">
                                </div>
                                <div class="form-group m-2">
                                    <label for="">Judul Voting</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($d['election_topic']);?>" required name="election_topic">
                                </div>
                                <div class="form-group m-2">
                                    <label for="">No Kandidat</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($d['no_of_candidates']);?>" required name="no_of_candidates">
                                </div>
                                <div class="form-group m-2">
                                    <label for="">Tanggal Mulai</label>
                                    <input type="date" class="form-control" value="<?php echo htmlspecialchars($d['starting_date']);?>" required name="starting_date">
                                </div>
                                <div class="form-group m-2">
                                    <label for="">Tanggal Selesai</label>
                                    <input type="date" class="form-control" value="<?php echo htmlspecialchars($d['ending_date']);?>" required name="ending_date">
                                </div>
                                <input type="submit" class="btn btn-outline-success m-2 mt-2" value="Edit" name="edit">
                                
                            </form>
                            <?php
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>Data tidak ditemukan!</div>";
                                    }
                                } else {
                                    echo "<div class='alert alert-danger' role='alert'>ID tidak ditemukan!</div>";
                                }
                            ?>

                            <?php
                                if (isset($_POST['edit'])) {
                                    $id = mysqli_real_escape_string($db, $_POST['id']);
                                    $judul_voting = mysqli_real_escape_string($db, $_POST['election_topic']);
                                    $no_kandidat = mysqli_real_escape_string($db, $_POST['no_of_candidates']);
                                    $tgl_mulai = mysqli_real_escape_string($db, $_POST['starting_date']);
                                    $tgl_selesai = mysqli_real_escape_string($db, $_POST['ending_date']);
                                    

                                    $query = "UPDATE elections SET
                                                election_topic = '$judul_voting',
                                                no_of_candidates = '$no_kandidat',
                                                starting_date = '$tgl_mulai',
                                                ending_date = '$tgl_selesai'
                                            WHERE id = '$id'";

                                    if (mysqli_query($db, $query)) {
                                        echo "<div class='alert alert-success' role='alert'>Data Berhasil Dirubah!</div>";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>Error: ". mysqli_error($db). "</div>";
                                    }
                                }
                            ?>
                            
                        </div>
                        
                    </div>
                </div>
            </div>