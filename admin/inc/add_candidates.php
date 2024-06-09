<?php 
require_once("config.php");

// Logika untuk menghapus data
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Hapus data dari database
    $deleteQuery = mysqli_query($db, "DELETE FROM candidate_details WHERE id = '$delete_id'") or die(mysqli_error($db));
    
    if ($deleteQuery) {
        echo "<script>location.assign('index.php?addCandidatePage=1&deleted=1');</script>";
    }
}

// Menampilkan notifikasi berdasarkan parameter GET
if (isset($_GET['added'])) {
    echo '<div class="alert alert-success my-3" role="alert">Candidate has been added successfully.</div>';
} else if (isset($_GET['largeFile'])) {
    echo '<div class="alert alert-danger my-3" role="alert">Candidate image is too large, please upload small file (you can upload any image up to 2MB).</div>';
} else if (isset($_GET['invalidFile'])) {
    echo '<div class="alert alert-danger my-3" role="alert">Invalid image type (Only .jpg, .png files are allowed).</div>';
} else if (isset($_GET['failed'])) {
    echo '<div class="alert alert-danger my-3" role="alert">Image uploading failed, please try again.</div>';
} else if (isset($_GET['deleted'])) {
    echo '<div class="alert alert-success my-3" role="alert">Candidate has been deleted successfully.</div>';
}

?>

<div class="row my-3">
    <div class="col-4">
        <h3>Tambah Kandidat Baru</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="exampleInputnama" class="form-label">Topik Voting</label>
                <select class="form-control" name="election_id" required> 
                    <option value=""> pilih </option>
                    <?php 
                        $fetchingElections = mysqli_query($db, "SELECT * FROM elections") OR die(mysqli_error($db));
                        $isAnyElectionAdded = mysqli_num_rows($fetchingElections);
                        if($isAnyElectionAdded > 0) {
                            while($row = mysqli_fetch_assoc($fetchingElections)) {
                                $election_id = $row['id'];
                                $election_name = $row['election_topic'];
                                $allowed_candidates = $row['no_of_candidates'];

                                // Now checking how many candidates are added in this election 
                                $fetchingCandidate = mysqli_query($db, "SELECT * FROM candidate_details WHERE election_id = '". $election_id ."'") or die(mysqli_error($db));
                                $added_candidates = mysqli_num_rows($fetchingCandidate);

                                if($added_candidates < $allowed_candidates) {
                                    echo "<option value='$election_id'>$election_name</option>";
                                }
                            }
                        } else {
                            echo "<option value=''> Tambahkan Pemilihan Dahulu! </option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="exampleInputnama" class="form-label">Nama Kandidat</label>
                <input type="text" name="candidate_name"  class="form-control" required />
            </div>
            <div class="form-group">
                <label for="exampleInputnama" class="form-label">Foto</label>
                <input type="file" name="candidate_photo" class="form-control" required />
            </div>
            <div class="form-group">
                <label for="exampleInputnama" class="form-label">Detail</label>
                <input type="text" name="candidate_details"  class="form-control" required />
            </div>
            <input type="submit" value="Tambah" name="addCandidateBtn" class="btn btn-success" />
        </form>
    </div>   

    <div class="col-8">
        <h3>Detail Kandidat</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Details</th>
                    <th scope="col">Pemilihan</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $fetchingData = mysqli_query($db, "SELECT * FROM candidate_details") or die(mysqli_error($db)); 
                    $isAnyCandidateAdded = mysqli_num_rows($fetchingData);

                    if($isAnyCandidateAdded > 0) {
                        $sno = 1;
                        while($row = mysqli_fetch_assoc($fetchingData)) {   
                            $candidate_id= $row['id'];
                            $election_id = $row['election_id'];
                            $fetchingElectionName = mysqli_query($db, "SELECT * FROM elections WHERE id = '". $election_id ."'") or die(mysqli_error($db));
                            $execFetchingElectionNameQuery = mysqli_fetch_assoc($fetchingElectionName);
                            $election_name = $execFetchingElectionNameQuery['election_topic'];

                            $candidate_photo = $row['candidate_photo'];

                            echo "<tr>";
                            echo "<td>" . $sno++ . "</td>";
                            echo "<td><img src='$candidate_photo' class='candidate_photo' /></td>";
                            echo "<td>" . $row['candidate_name'] . "</td>";
                            echo "<td>" . $row['candidate_details'] . "</td>";
                            echo "<td>" . $election_name . "</td>";
                            echo "<td>";
                            echo "<button class='btn btn-sm btn-danger' onclick='DeleteData($candidate_id)'> Delete </button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'> Belum Ada Kandidat yang Ditambahkan. </td></tr>";
                    }
                ?>
            </tbody>    
        </table>
    </div>
</div>

<script>
    const DeleteData = (c_id) => {
        let c = confirm("Are you really want to delete it?");

        if(c == true) {
            location.assign("index.php?addCandidatePage=1&delete_id=" + c_id);
        }
    }
</script>

<?php 
if (isset($_POST['addCandidateBtn'])) {
    $election_id = mysqli_real_escape_string($db, $_POST['election_id']);
    $candidate_name = mysqli_real_escape_string($db, $_POST['candidate_name']);
    $candidate_details = mysqli_real_escape_string($db, $_POST['candidate_details']);
    $inserted_by = $_SESSION['username'];
    $inserted_on = date("Y-m-d");

    // Foto Logic Starts
    $targetted_folder = "../assets/images/candidate_photos/";
    $candidate_photo = $targetted_folder . rand(111111111, 99999999999) . "_" . rand(111111111, 99999999999) . $_FILES['candidate_photo']['name'];
    $candidate_photo_tmp_name = $_FILES['candidate_photo']['tmp_name'];
    $candidate_photo_type = strtolower(pathinfo($candidate_photo, PATHINFO_EXTENSION));
    $allowed_types = array("jpg", "png", "jpeg");        
    $image_size = $_FILES['candidate_photo']['size'];

    if ($image_size < 2000000) { // 2 MB
        if (in_array($candidate_photo_type, $allowed_types)) {
            if (move_uploaded_file($candidate_photo_tmp_name, $candidate_photo)) {
                // inserting into db
                mysqli_query($db, "INSERT INTO candidate_details(election_id, candidate_name, candidate_details, candidate_photo, inserted_by, inserted_on) VALUES('". $election_id ."', '". $candidate_name ."', '". $candidate_details ."', '". $candidate_photo ."', '". $inserted_by ."', '". $inserted_on ."')") or die(mysqli_error($db));

                echo "<script> location.assign('index.php?addCandidatePage=1&added=1'); </script>";
            } else {
                echo "<script> location.assign('index.php?addCandidatePage=1&failed=1'); </script>";                    
            }
        } else {
            echo "<script> location.assign('index.php?addCandidatePage=1&invalidFile=1'); </script>";
        }
    } else {
        echo "<script> location.assign('index.php?addCandidatePage=1&largeFile=1'); </script>";
    }

    // Foto Logic Ends
}
?>
